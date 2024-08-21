<?php
// Start the session
session_start();

// Include your database connection file
include_once 'dbconn.php';

// Fetch barangay name if not already set in session
if (!isset($_SESSION['barangay_name']) && isset($_SESSION['barangays_id'])) {
    $stmt = $pdo->prepare("SELECT barangay_name FROM tbl_users_barangay WHERE barangays_id = ?");
    $stmt->execute([$_SESSION['barangays_id']]);
    $_SESSION['barangay_name'] = $stmt->fetchColumn();
}

// Initialize user information
$email = $_SESSION['email'] ?? '';
$firstName = $_SESSION['first_name'] ?? '';
$middleName = $_SESSION['middle_name'] ?? '';
$lastName = $_SESSION['last_name'] ?? '';
$extensionName = $_SESSION['extension_name'] ?? '';
$barangay = $_SESSION['barangays_id'] ?? '';
$pic_data = $_SESSION['pic_data'] ?? '';

// Define pagination variables
$results_per_page = 10; // Number of results per page

// Determine current page
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;

// Calculate the SQL LIMIT starting number for the results on the displaying page
$start_from = ($page - 1) * $results_per_page;
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
    <style>
        table, th, td {
            border: none;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
    </style>
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
                <!-- Navbar content if needed -->
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div style="margin-top: 3rem;" class="sidebar bg-dark" id="sidebar">
        <button class="sidebar-toggler" type="button" onclick="toggleSidebar()">
            <i class="bi bi-grid-fill large-icon"></i><span class="nav-text menu-icon-text">Menu</span>
        </button>

        <!-- User Information -->
        <div class="user-info px-3 py-2 text-center">
            <?php if (!empty($pic_data)): ?>
                <img class="profile" src="<?php echo htmlspecialchars($pic_data); ?>" alt="Profile Picture">
            <?php endif; ?>
            <p class="white-text"><?php echo htmlspecialchars($_SESSION['accountType'] ?? ''); ?></p>
            <h5 class="white-text"><?php echo htmlspecialchars("$firstName $middleName $lastName $extensionName"); ?></h5>
            <p class="user-email white-text"><?php echo htmlspecialchars($email); ?></p>
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
        
            <table class="table table-bordered table-hover">
            <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                          
                            <th>Barangay</th>
                            
                            <th>Status</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    try {
                        $barangay_name = $_SESSION['barangay_name'] ?? '';

                        // Fetch complaints data with pagination
                        $stmt = $pdo->prepare("
                        SELECT c.*, b.barangay_name, cc.complaints_category, i.gender, i.place_of_birth, i.age, i.educational_background, i.civil_status, e.evidence_path
                        FROM tbl_complaints c
                        JOIN tbl_users_barangay b ON c.barangays_id = b.barangays_id
                        JOIN tbl_complaintcategories cc ON c.category_id = cc.category_id
                        JOIN tbl_info i ON c.info_id = i.info_id
                        LEFT JOIN tbl_evidence e ON c.complaints_id = e.complaints_id
                        WHERE (c.status = 'settled_in_barangay') AND b.barangay_name = ?
                        ORDER BY c.date_filed ASC
                        LIMIT ?, ?
                    ");
                    
                        $stmt->bindParam(1, $barangay_name, PDO::PARAM_STR);
                        $stmt->bindParam(2, $start_from, PDO::PARAM_INT);
                        $stmt->bindParam(3, $results_per_page, PDO::PARAM_INT);
                        $stmt->execute();

                        if ($stmt->rowCount() == 0) {
                            echo "<tr><td colspan='16'>No complaints found.</td></tr>";
                        } else {
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $complaint_id = htmlspecialchars($row['complaints_id']);
                                $complaint_name = htmlspecialchars($row['complaint_name']);
                                $complaints = htmlspecialchars($row['complaints']);
                                $date_filed = htmlspecialchars($row['date_filed']);
                                $category_name = htmlspecialchars($row['complaints_category']);
                                $cp_number = htmlspecialchars($row['cp_number']);
                                $complaints_person = htmlspecialchars($row['complaints_person']);
                                $gender = htmlspecialchars($row['gender']);
                                $place_of_birth = htmlspecialchars($row['place_of_birth']);
                                $age = htmlspecialchars($row['age']);
                                $educational_background = htmlspecialchars($row['educational_background']);
                                $civil_status = htmlspecialchars($row['civil_status']);
                                $evidence_path = htmlspecialchars($row['evidence_path']);
                            
                                echo "<tr>
                                    <td>{$complaint_id}</td>
                                    <td>{$complaint_name}</td>
                                    
                                    <td>{$barangay_name}</td>
                              
                                    <td>
                                        <button type='button' class='btn btn-sm btn-info' onclick='loadComplaintDetails({$complaint_id})'>View Details</button>
                                    </td>
                                </tr>";
                            }
                            
                            
                        }
                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php
                    // Calculate total pages
                    $stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM tbl_complaints c JOIN tbl_users_barangay b ON c.barangays_id = b.barangays_id WHERE c.status = 'settled_in_barangay' AND b.barangay_name = ?");
                    $stmt->execute([$barangay_name]);
                    $total_results = $stmt->fetchColumn();
                    $total_pages = ceil($total_results / $results_per_page);

                    if ($page > 1) {
                        echo "<li class='page-item'><a class='page-link' href='?page=" . ($page - 1) . "'>Previous</a></li>";
                    }
                    for ($i = 1; $i <= $total_pages; $i++) {
                        $active = ($i == $page) ? 'active' : '';
                        echo "<li class='page-item $active'><a class='page-link' href='?page=$i'>$i</a></li>";
                    }
                    if ($page < $total_pages) {
                        echo "<li class='page-item'><a class='page-link' href='?page=" . ($page + 1) . "'>Next</a></li>";
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </div>

    <!-- Modal for Viewing Complaint Details -->
    <div class="modal fade" id="viewComplaintModal" tabindex="-1" aria-labelledby="viewComplaintModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewComplaintModalLabel">Complaint Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="complaintDetails">
                    <!-- Complaint details will be loaded here -->
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
            fetch(`barangaydetails.php?id=${complaintId}`)
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
