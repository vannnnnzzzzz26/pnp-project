<?php
// Start output buffering
ob_start();

// Include your database connection file
include_once 'dbconn.php';

// Function to display detailed complaint information in a modal
function displayComplaintDetails($pdo) {
    try {
        // Fetch settled complaints from tbl_complaints table
        $stmt = $pdo->prepare("
            SELECT c.complaints_id, c.complaint_name, c.complaints, c.date_filed, c.status, c.category_id, c.barangays_id, c.cp_number, c.complaints_person, b.barangay_name, cat.complaints_category
            FROM tbl_complaints c
            LEFT JOIN tbl_users_barangay b ON c.barangays_id = b.barangays_id
            LEFT JOIN tbl_complaintcategories cat ON c.category_id = cat.category_id
            WHERE c.responds = 'pnp'
            ORDER BY c.date_filed ASC
        ");
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Display complaint details
            $complaint_id = $row['complaints_id'];
            $complaint_name = htmlspecialchars($row['complaint_name']);
            $complaints = htmlspecialchars($row['complaints']);
            $date_filed = htmlspecialchars($row['date_filed']);
            $status = htmlspecialchars($row['status']);
            $category_name = htmlspecialchars($row['complaints_category']);
            $barangay_name = htmlspecialchars($row['barangay_name']);
            $cp_number = !empty($row['cp_number']) ? htmlspecialchars($row['cp_number']) : '-';
            $complaints_person = !empty($row['complaints_person']) ? htmlspecialchars($row['complaints_person']) : '-';

            echo "<tr>";
            echo "<td>{$complaint_id}</td>";
            echo "<td>{$complaint_name}</td>";
            echo "<td>{$complaints}</td>";
            echo "<td>{$category_name}</td>";
            echo "<td>{$barangay_name}</td>";
            echo "<td>{$cp_number}</td>";
            echo "<td>{$complaints_person}</td>";
            echo "<td>{$date_filed}</td>";
            echo "<td><button type='button' class='btn btn-sm btn-info' onclick='loadComplaintDetails({$complaint_id})'>View Details</button></td>"; // Modified button to trigger modal
            echo "</tr>";
        }
    } catch (PDOException $e) {
        echo "<tr><td colspan='9'>Error fetching PNP complaints logs: " . $e->getMessage() . "</td></tr>";
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

    <!-- Page Content -->
 


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
                <a class="nav-link" href=""><i class="bi bi-graph-up"></i><span class="nav-text">Dashboard</span></a>
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
            <h2 class="mt-3 mb-4">PNP Complaints Logs</h2>
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
<!-- JavaScript to handle modal content dynamically -->
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
                    $('#viewComplaintModal').modal('show'); // Ensure $() refers to jQuery
                })
                .catch(error => console.error('Error fetching complaint details:', error));
        }
    </script>

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

</body>
</html>
