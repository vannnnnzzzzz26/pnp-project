<?php
// Start output buffering
ob_start();

// Include your database connection file
include_once 'dbconn.php';

// Function to display basic complaint information in the table
function displayComplaintDetails($pdo) {
    try {
        // Fetch settled complaints from tbl_complaints table with additional information
        $stmt = $pdo->prepare("
            SELECT c.complaints_id, c.complaint_name, b.barangay_name
            FROM tbl_complaints c
            LEFT JOIN tbl_users_barangay b ON c.barangays_id = b.barangays_id
            WHERE c.responds = 'pnp'
            ORDER BY c.date_filed ASC
        ");
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Display complaint details
            $complaint_id = $row['complaints_id'];
            $complaint_name = htmlspecialchars($row['complaint_name']);
            $barangay_name = htmlspecialchars($row['barangay_name']);

            echo "<tr>";
            echo "<td>{$complaint_name}</td>";
            echo "<td>{$barangay_name}</td>";
            echo "<td><button type='button' class='btn btn-sm btn-info' onclick='loadComplaintDetails({$complaint_id})'>View Details</button></td>";
            echo "</tr>";
        }
    } catch (PDOException $e) {
        echo "<tr><td colspan='3'>Error fetching PNP complaints logs: " . $e->getMessage() . "</td></tr>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PNP Complaints Logs</title>
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

    <!-- Sidebar -->
    <div style="margin-top: 3rem;" class="sidebar bg-dark" id="sidebar">
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
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="pnp.php">
                    <i class="bi bi-file-earmark-text large-icon"></i><span class="nav-text">Complaints</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="pnplogs.php">
                    <i class="bi bi-file-earmark-text large-icon"></i><span class="nav-text">Complaints Logs</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="pnp-announcement.php">
                    <i class="bi bi-check-square-fill large-icon"></i><span class="nav-text">Announcement</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">
                    <i class="bi bi-graph-up"></i><span class="nav-text">Dashboard</span>
                </a>
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

    <!-- Page Content -->
    <div class="content">
        <div class="container">
            <h2 class="mt-3 mb-4">PNP Complaints Logs</h2>
            <div class="table">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Barangay</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Function call to display PNP complaints logs
                        displayComplaintDetails($pdo);
                        ?>
                    </tbody>
                </table>
            </div>
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

    <!-- jQuery and Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.all.min.js"></script>
    <script src="script.js"></script>

    <!-- JavaScript to handle modal content dynamically -->
    <script>
        function loadComplaintDetails(complaintId) {
            let url = `pnpdetails.php?id=${complaintId}`;

            fetch(url)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('complaintDetails').innerHTML = data;
                    // Show the modal
                    var complaintModal = new bootstrap.Modal(document.getElementById('viewComplaintModal'));
                    complaintModal.show();
                })
                .catch(error => {
                    console.error('Error fetching complaint details:', error);
                    document.getElementById('complaintDetails').innerHTML = "Error loading details.";
                });
        }
    </script>

    <!-- Toggle Sidebar Script -->
    <script>
    

        function confirmLogout() {
            Swal.fire({
                title: 'Are you sure you want to logout?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, logout',
                cancelButtonText: 'No, stay logged in',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        }
    </script>
</body>
</html>
