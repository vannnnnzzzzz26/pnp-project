<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PNP Complaints</title>
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
            <!-- Button to toggle sidebar visibility -->
            <button class="navbar-toggler" type="button" onclick="toggleSidebar()">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>


    <!-- Page Content -->
   
    <!-- Sidebar -->
    <div  style="margin-top: 3rem;" class="sidebar bg-dark" id="sidebar">
        <!-- Toggle button inside sidebar -->
        <button class="sidebar-toggler" type="button" onclick="toggleSidebar()">
        <i class="bi bi-grid-fill large-icon"></i><span class="nav-text menu-icon-text">Menu</span>
        </button>

        <!-- User Information -->
        <div class="user-info px-3 py-2 text-center">
        <?php
    // Ensure session is started
    session_start();

    // Check if user information is available in session
    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        $firstName = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : '';
        $middleName = isset($_SESSION['middle_name']) ? $_SESSION['middle_name'] : '';
        $lastName = isset($_SESSION['last_name']) ? $_SESSION['last_name'] : '';
        $extensionName = isset($_SESSION['extension_name']) ? $_SESSION['extension_name'] : '';
        $accountType = isset($_SESSION['accountType']) ? $_SESSION['accountType'] : '';
        // Display user profile picture if available
        if (isset($_SESSION['pic_data'])) {
            $pic_data = $_SESSION['pic_data'];
            echo "<img class='profile' src='$pic_data' alt='Profile Picture'>";
        }

        // Display user information with CSS class
    }
    ?>
            <p class='white-text'> <?php echo $_SESSION['accountType']; ?></p>
            <h5 class='white-text'>User Information</h5>
            <p class='white-text'><?php echo $email; ?></p>
            <p class='white-text'><?php echo "$firstName $middleName $lastName $extensionName"; ?></p>
        </div>

        <!-- Menu items -->
        <div class="menu-header">
            <h4 class="white-text">Menu</h4>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item menu-item">
                <a class="nav-link active" href="pnp.php"><i class="bi bi-house-door-fill"></i><span class="nav-text">Complaints</span></a>
            </li>
            <li class="nav-item menu-item">
                <a class="nav-link" href="pnplogs.php"><i class="bi bi-journal-text"></i><span class="nav-text">Complaints Logs</span></a>
            </li>
            <li class="nav-item menu-item">
                <a class="nav-link" href=""><i class="bi bi-graph-up"></i><span class="nav-text">Dashboard </span></a>
            </li>
        </ul>

        <!-- Logout Form -->
        <form action="logout.php" method="post" id="logoutForm">
            <div class="logout-btn">
                <button type="button" class="btn btn-danger btn-sm" onclick="confirmLogout()">
                    <i class="bi bi-box-arrow-left"></i><span class="nav-text">Logout</span>
                </button>
            </div>
        </form>
    </div>
    <div class="content">
        <div class="container">
            <h2 class="mt-3 mb-4">PNP Complaints</h2>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-center">
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

                        // Include your database connection file
                        include_once 'dbconn.php';

                        // Function to display PNP complaints
                        function displayPNPComplaints($pdo) {
                            try {
                                // Fetch PNP complaints from tbl_complaints table only
                                $stmt = $pdo->prepare("
                                    SELECT c.complaints_id, c.complaint_name, c.complaints, c.date_filed, c.status, c.category_id, c.barangays_id, c.cp_number, c.complaints_person, c.responds
                                    FROM tbl_complaints c
                                    WHERE c.status = 'pnp'
                                    ORDER BY date_filed ASC
                                ");
                                $stmt->execute();

                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    // Display complaint details
                                    $complaint_id = $row['complaints_id'];
                                    $complaint_name = htmlspecialchars($row['complaint_name']);
                                    $complaints = htmlspecialchars($row['complaints']);
                                    $date_filed = htmlspecialchars($row['date_filed']);
                                    $status = htmlspecialchars($row['status']);
                                    $category_id = $row['category_id'];
                                    $barangay_name = '';
                                    $cp_number = '';
                                    $complaints_person = '';

                                    if (!empty($row['barangay_id'])) {
                                        // Fetch barangay name if available
                                        $stmtBar = $pdo->prepare("SELECT barangay_name FROM tbl_users_barangay WHERE barangays_id = ?");
                                        $stmtBar->execute([$row['barangays_id']]);
                                        $barangay_name = htmlspecialchars($stmtBar->fetchColumn());
                                    }

                                    if (!empty($row['cp_number'])) {
                                        // Fetch contact number if available
                                        $cp_number = htmlspecialchars($row['cp_number']);
                                    }

                                    if (!empty($row['complaints_person'])) {
                                        // Fetch complaints person if available
                                        $complaints_person = htmlspecialchars($row['complaints_person']);
                                    }

                                    // Fetch category name
                                    $stmtCat = $pdo->prepare("SELECT complaints_category FROM tbl_complaintcategories WHERE category_id = ?");
                                    $stmtCat->execute([$category_id]);
                                    $category_name = htmlspecialchars($stmtCat->fetchColumn());

                                    // Determine the appropriate option in the status dropdown
                                    $optionSettled = ($status === 'settled') ? 'selected' : '';
                                    $optionPending = ($status === 'pending') ? 'selected' : '';

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
                                    echo "<td>";
                                    
                                    // Display appropriate action based on status
                                    if ($status === 'pnp') {
                                        echo "<form action='{$_SERVER['PHP_SELF']}' method='post'>";
                                        echo "<input type='hidden' name='complaint_id' value='{$complaint_id}'>";
                                        echo "<select name='new_status' class='form-select'>";
                                        echo "<option value='unresolved' {$optionPending}>Pending</option>";
                                        echo "<option value='settled' {$optionSettled}>Settle</option>";
                                        echo "</select>";
                                        echo "<button type='submit' name='update_status' class='btn btn-sm btn-primary'>Update</button>";
                                        echo "</form>";
                                    } elseif ($status === 'settled') {
                                        echo "Settled"; // Display different action for settled complaints
                                    }
                                    
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } catch (PDOException $e) {
                                echo "<tr><td colspan='10'>Error fetching PNP complaints: " . $e->getMessage() . "</td></tr>";
                            }
                        }

                        // Handle status update submission
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
                            $complaint_id = $_POST['complaint_id'];
                            $new_status = $_POST['new_status'];

                            try {
                                if ($new_status === 'settled') { // Only update if 'settled' is selected
                                    // Update status and responds in tbl_complaints
                                    $stmtUpdate = $pdo->prepare("UPDATE tbl_complaints SET status = ?, responds = 'pnp' WHERE complaints_id = ?");
                                    $stmtUpdate->execute([$new_status, $complaint_id]);
                                }

                                // Redirect to prevent resubmission on page refresh
                                header("Location: {$_SERVER['PHP_SELF']}");
                                exit();
                            } catch (PDOException $e) {
                                echo "Error updating status: " . $e->getMessage();
                            }
                        }

                        // Function call to display PNP complaints
                        displayPNPComplaints($pdo);

                        // End output buffering and flush output
                        ob_end_flush();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+SBgBEd9FHfVf0tj+3/Jp7ldO+tJsGz5gYTKwQ4gDlHhT56" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-z4K1jpxJ2r5LkhRGsqw5YRt5P/joWtDgLJRBg/EjFggQFS2Ua90kE/KcZkshNoE5" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.all.min.js"></script>

</body>
</html>
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