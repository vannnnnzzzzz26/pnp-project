<?php
// Start the session at the beginning
session_start();

// Include your database connection file
include_once 'dbconn.php';

// Fetch barangay name if not already set in session
if (!isset($_SESSION['barangay_name']) && isset($_SESSION['barangays_id'])) {
    $stmt = $pdo->prepare("SELECT barangay_name FROM tbl_users_barangay WHERE barangays_id = ?");
    $stmt->execute([$_SESSION['barangays_id']]);
    $_SESSION['barangay_name'] = $stmt->fetchColumn();
}

$firstName = $_SESSION['first_name'];
$middleName = $_SESSION['middle_name'];
$lastName = $_SESSION['last_name'];
$extensionName = $_SESSION['extension_name'] ?? '';
$email = $_SESSION['email'] ?? '';
$barangay_name = $_SESSION['barangay_name'] ?? '';
$pic_data = $_SESSION['pic_data'] ?? '';

$results_per_page = 10; 

// Determine current page
$page = !isset($_GET['page']) || !is_numeric($_GET['page']) || $_GET['page'] <= 0 ? 1 : $_GET['page'];

// Calculate the SQL LIMIT starting number for the results on the displaying page
$start_from = ($page - 1) * $results_per_page;

function displayComplaints($pdo, $start_from, $results_per_page) {
    try {
        $barangay_name = $_SESSION['barangay_name'] ?? '';

        // Display approved and unresolved complaints
        $stmt = $pdo->prepare("
        SELECT c.*, b.barangay_name, cc.complaints_category
        FROM tbl_complaints c
        JOIN tbl_users_barangay b ON c.barangays_id = b.barangays_id
        JOIN tbl_complaintcategories cc ON c.category_id = cc.category_id
        WHERE (c.status IN ('Approved')) AND b.barangay_name = ?
        ORDER BY c.date_filed ASC
        LIMIT ?, ?
    ");
    
        $stmt->bindParam(1, $barangay_name, PDO::PARAM_STR);
        $stmt->bindParam(2, $start_from, PDO::PARAM_INT);
        $stmt->bindParam(3, $results_per_page, PDO::PARAM_INT);
        
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            echo "<tr><td colspan='10'>No complaints found.</td></tr>";
        } else {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Extract each field from the $row array
                $complaint_id = $row['complaints_id'];
                $complaint_name = htmlspecialchars($row['complaint_name']);
                $complaints = htmlspecialchars($row['complaints']);
                $date_filed = htmlspecialchars($row['date_filed']);
                $status = htmlspecialchars($row['status']);

                $category_name = htmlspecialchars($row['complaints_category']);
                $barangay_name = htmlspecialchars($row['barangay_name']);
                $cp_number = htmlspecialchars($row['cp_number']);
                $complaints_person = htmlspecialchars($row['complaints_person']);

                echo "<tr>";
                echo "<td>{$complaint_id}</td>";
                echo "<td>{$complaint_name}</td>";
                echo "<td>{$complaints}</td>";
                echo "<td>{$category_name}</td>";
                echo "<td>{$barangay_name}</td>";
                echo "<td>{$cp_number}</td>";
                echo "<td>{$complaints_person}</td>";
                echo "<td>{$date_filed}</td>";
                echo "<td>{$status}</td>";

                // Form to update status
                echo "<td>";
                // Display complaints table with status update form
echo "<form action='{$_SERVER['PHP_SELF']}' method='post'>";
echo "<input type='hidden' name='complaint_id' value='{$complaint_id}'>";
echo "<select name='new_status' class='form-select'>";
echo "<option value='unresolved' " . ($status === 'unresolved' || $status === 'unresolved' ? 'selected' : '') . ">Unresolved</option>";
echo "<option value='settled' " . ($status === 'settled' ? 'selected' : '') . ">Settled</option>";
echo "<option value='pnp' " . ($status === 'pnp' ? 'selected' : '') . ">Move to PNP</option>";
echo "</select>";
echo "<button type='submit' name='update_status' class='btn btn-sm btn-primary'>Update</button>";
echo "</form>";

                echo "</td>";

                echo "</tr>";
            }
        }
    } catch (PDOException $e) {
        echo "<tr><td colspan='10'>Error fetching complaints: " . $e->getMessage() . "</td></tr>";
    }
}

// Handle status update submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $complaint_id = $_POST['complaint_id'];
    $new_status = $_POST['new_status'];

    // Update status in the database
    try {
        $responds = '';

        // Update responds based on new status
        if ($new_status === 'settled') {
            $responds = 'barangay';
        } elseif ($new_status === 'pnp') {
            $responds = 'pnp';
        }

        $stmt = $pdo->prepare("UPDATE tbl_complaints SET status = ?, responds = ? WHERE complaints_id = ?");
        $stmt->execute([$new_status, $responds, $complaint_id]);

        // Redirect to prevent resubmission on page refresh
        header("Location: {$_SERVER['PHP_SELF']}?page={$page}");
        exit();
    } catch (PDOException $e) {
        echo "Error updating status: " . $e->getMessage();
    }
}

// Pagination
$stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM tbl_complaints c JOIN tbl_users_barangay b ON c.barangays_id = b.barangays_id WHERE (c.status = 'Approved' OR c.status = 'unresolved') AND b.barangay_name = ?");
$stmt->execute([$barangay_name]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_pages = ceil($row['total'] / $results_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uploaded Complaints</title>
    <!-- Bootstrap CSS -->
     
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
   
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">Excel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Add Navbar items if needed -->
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="content">
        <div class="container">
            <h2 class="mt-3 mb-4">Uploaded Complaints</h2>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Barangay</th>
                            <th>Contact Number</th>
                            <th>Complaints Person</th>
                            <th>Date Filed</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Start output buffering
                        ob_start();

                        // Check if PDO connection is established
                        if (!isset($pdo)) {
                            echo "<tr><td colspan='10'>PDO connection not established.</td></tr>";
                        } else {
                            displayComplaints($pdo, $start_from, $results_per_page);
                        }

                        // End output buffering and flush output
                        ob_end_flush();
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <?php
                    // Pagination links
                    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM tbl_complaints WHERE status NOT IN ('settled', 'pnp')");
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $total_pages = ceil($row['total'] / $results_per_page);

                    // Previous page link
                    if ($page > 1) {
                        echo "<li class='page-item'><a class='page-link' href='{$_SERVER['PHP_SELF']}?page=" . ($page - 1) . "'>Previous</a></li>";
                    } else {
                        echo "<li class='page-item disabled'><a class='page-link' href='#'>Previous</a></li>";
                    }

                    // Numbered page links
                    for ($i = 1; $i <= $total_pages; $i++) {
                        if ($i == $page) {
                            echo "<li class='page-item active'><a class='page-link' href='#'>$i</a></li>";
                        } else {
                            echo "<li class='page-item'><a class='page-link' href='{$_SERVER['PHP_SELF']}?page=$i'>$i</a></li>";
                        }
                    }

                    // Next page link
                    if ($page < $total_pages) {
                        echo "<li class='page-item'><a class='page-link' href='{$_SERVER['PHP_SELF']}?page=" . ($page + 1) . "'>Next</a></li>";
                    } else {
                        echo "<li class='page-item disabled'><a class='page-link' href='#'>Next</a></li>";
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </div>

       
<div  style="margin-top: 3rem;" class="sidebar bg-dark" id="sidebar">
    <!-- Toggle button inside sidebar -->
    <button class="sidebar-toggler" type="button" onclick="toggleSidebar()">
        <i class="bi bi-grid-fill large-icon"></i><span class="nav-text menu-icon-text">Menu</span>
    </button>

    <!-- User Information -->
    <div class="user-info px-3 py-2 text-center">
        <!-- Your PHP session-based content -->
        <?php
        if (isset($_SESSION['pic_data'])) {
            $pic_data = $_SESSION['pic_data'];
            echo "<img class='profile' src='$pic_data' alt='Profile Picture'>";
        }
        ?>
        <p class='white-text'> <?php echo $_SESSION['accountType']; ?></p>
        <h5 class="white-text"><?php echo "$firstName $middleName $lastName $extensionName"; ?></h5>
        <p class="user-email white-text"><?php echo "$email"; ?></p>
    </div>
    
    <!-- Sidebar Links -->
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="manage-complaints.php">
                <i class="bi bi-file-earmark-text large-icon"></i><span class="nav-text">Complaints</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="barangay-responder.php">
                <i class="bi bi-file-earmark-text large-icon"></i><span class="nav-text">Complaints Logs</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="barangaylogs.php">
            <i class="bi bi-check-square-fill large-icon"></i><span class="nav-text">Complaints Responder</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="barangay-official.php">
                <i class="bi bi-person large-icon"></i><span class="nav-text">Barangay Official</span>
            </a>
        </li>
     
    </ul>
    
    <!-- Logout -->
               <!-- Logout Form -->
        <form action="logout.php" method="post" id="logoutForm">
            <div class="logout-btn">
                <button type="button" class="btn btn-danger btn-sm" onclick="confirmLogout()">
                    <i class="bi bi-box-arrow-left"></i><span class="nav-text">Logout</span>
                </button>
            </div>
        </form>
</div>
    
    <script>
    function confirmLogout() {
        Swal.fire({
            title: "Are you sure?",
            text: "You will be logged out.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#212529",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, logout"
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to logout URL
                document.getElementById('logoutForm').submit();
            }
        });
    }
    </script>
 <script src="script.js"></script>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+H9RHlVho9Uv95TE0Yjl0w9utO6oLjGwkskDZ3M2vpXskxq" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-7F5kFf1FyZ0QOW+D5FlrbkVCyImqH8R0b79Teja2tvw5StyiJ6Tga4G+M8C5vQgq" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.all.min.js"></script>
</body>
</html>