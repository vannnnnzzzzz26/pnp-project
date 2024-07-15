<?php
// Ensure session is started at the very beginning
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
$extensionName = isset($_SESSION['extension_name']) ? $_SESSION['extension_name'] : '';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$barangay = isset($_SESSION['barangays_id']) ? $_SESSION['barangays_id'] : '';
$pic_data = isset($_SESSION['pic_data']) ?  : '';


// Define pagination variables
$results_per_page = 10; // Number of results per page

// Determine current page
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;

// Calculate the SQL LIMIT starting number for the results on the displaying page
$start_from = ($page - 1) * $results_per_page;

// Initialize variables for user information
$email = '';
$firstName = '';
$middleName = '';
$lastName = '';
$extensionName = '';
$accountType = '';
$barangays="";

// Check if user information is available in session
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $firstName = $_SESSION['first_name'] ?? '';
    $middleName = $_SESSION['middle_name'] ?? '';
    $lastName = $_SESSION['last_name'] ?? '';
    $extensionName = $_SESSION['extension_name'] ?? '';
    $accountType = $_SESSION['accountType'] ?? '';
    $barangays = $_SESSION['barangays_id'] ?? '';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Logs - Settled Complaints</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
<style>
    table, th, td {
    border: none;
}
table {
    border-collapse: collapse;
    width: 100%;
}

</style>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">Excel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                
            </div>
        </div>
    </nav>

    
    <!-- Sidebar -->
 
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

    <!-- Page Content -->
    <div class="content">
        <div class="container">
            <h2 class="mt-3 mb-4">Barangay Logs - Settled Complaints</h2>
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
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                                    <?php
// Start the session at the beginning
// Start the session at the beginning
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include your database connection file
include_once 'dbconn.php';

// Fetch barangay name if not already set in session
if (!isset($_SESSION['barangay_name']) && isset($_SESSION['barangays_id'])) {
    $stmt = $pdo->prepare("SELECT barangay_name FROM tbl_users_barangay WHERE barangays_id = ?");
    $stmt->execute([$_SESSION['barangays_id']]);
    $_SESSION['barangay_name'] = $stmt->fetchColumn();
}
$results_per_page = 10;

// Determine current page
$page = !isset($_GET['page']) || !is_numeric($_GET['page']) || $_GET['page'] <= 0 ? 1 : $_GET['page'];

// Calculate the SQL LIMIT starting number for the results on the displaying page
$start_from = ($page - 1) * $results_per_page;

try {
    $barangay_name = $_SESSION['barangay_name'] ?? '';

    // Display complaints data with pagination, including settled complaints
    $stmt = $pdo->prepare("
        SELECT c.*, b.barangay_name, cc.complaints_category
        FROM tbl_complaints c
        JOIN tbl_users_barangay b ON c.barangays_id = b.barangays_id
        JOIN tbl_complaintcategories cc ON c.category_id = cc.category_id
        WHERE (c.status = 'settled' ) AND b.barangay_name = ?
        ORDER BY c.date_filed ASC
        LIMIT ?, ?
    ");
    
    $stmt->bindParam(1, $barangay_name, PDO::PARAM_STR);
    $stmt->bindParam(2, $start_from, PDO::PARAM_INT);
    $stmt->bindParam(3, $results_per_page, PDO::PARAM_INT);
    
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        echo "<tr><td colspan='9'>No complaints found.</td></tr>";
    } else {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Extract each field from the $row array
            $complaint_id = htmlspecialchars($row['complaints_id']);
            $complaint_name = htmlspecialchars($row['complaint_name']);
            $complaints = htmlspecialchars($row['complaints']);
            $date_filed = htmlspecialchars($row['date_filed']);
            $category_name = htmlspecialchars($row['complaints_category']);
            $cp_number = htmlspecialchars($row['cp_number']);
            $complaints_person = htmlspecialchars($row['complaints_person']);

            echo "<tr>
                <td>{$complaint_id}</td>
                <td>{$complaint_name}</td>
                <td>{$complaints}</td>
                <td>{$category_name}</td>
                <td>{$barangay_name}</td>
                <td>{$cp_number}</td>
                <td>{$complaints_person}</td>
                <td>{$date_filed}</td>
                <td>
                    <button type='button' class='btn btn-sm btn-info' onclick='loadComplaintDetails({$complaint_id})'>View Details</button>
                </td>
            </tr>";
        }
    }
} catch (PDOException $e) {
    echo "<tr><td colspan='9'>Error fetching complaints: " . $e->getMessage() . "</td></tr>";
}
?>


                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <?php
                    // Pagination links
                    $stmt_count = $pdo->query("SELECT COUNT(*) AS total FROM tbl_complaints WHERE status = 'settled'");
                    $row_count = $stmt_count->fetch(PDO::FETCH_ASSOC);
                    $total_pages = ceil($row_count['total'] / $results_per_page);

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

    <!-- Modal for Viewing Complaint Details -->
    <div class="modal fade" id="viewComplaintModal" tabindex="-1" aria-labelledby="viewComplaintModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewComplaintModalLabel">Complaint Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="complaintDetails">Loading...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <script src="script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.all.min.js"></script>
    <script>
        function loadComplaintDetails(complaintId) {
            let url = `barangaydetails.php?id=${complaintId}`;

            fetch(url)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('complaintDetails').innerHTML = data;
                    $('#viewComplaintModal').modal('show');
                })
                .catch(error => console.error('Error fetching complaint details:', error));
        }

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
</body>
</html>
