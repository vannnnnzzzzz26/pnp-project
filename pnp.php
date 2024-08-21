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

    <!-- Sidebar -->
    <div style="margin-top: 3rem;" class="sidebar bg-dark" id="sidebar">
        <!-- Toggle button inside sidebar -->
        <button class="sidebar-toggler" type="button" onclick="toggleSidebar()">
            <i class="bi bi-grid-fill large-icon"></i><span class="nav-text menu-icon-text">Menu</span>
        </button>

        <!-- User Information -->
        <div class="user-info px-3 py-2 text-center">
            <?php
            session_start();
            if (isset($_SESSION['email'])) {
                $email = $_SESSION['email'];
                $firstName = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : '';
                $middleName = isset($_SESSION['middle_name']) ? $_SESSION['middle_name'] : '';
                $lastName = isset($_SESSION['last_name']) ? $_SESSION['last_name'] : '';
                $extensionName = isset($_SESSION['extension_name']) ? $_SESSION['extension_name'] : '';
                $accountType = isset($_SESSION['accountType']) ? $_SESSION['accountType'] : '';
                if (isset($_SESSION['pic_data'])) {
                    $pic_data = $_SESSION['pic_data'];
                    echo "<img class='profile' src='$pic_data' alt='Profile Picture'>";
                }
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

    <div class="content">
        <div class="container">
            <h2 class="mt-3 mb-4">PNP Complaints</h2>
            <div class="table">
                <table class="table table-striped table-bordered table-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    include_once 'dbconn.php';

                    function displayPNPComplaints($pdo) {
                        try {
                            $stmt = $pdo->prepare("
                                SELECT c.complaints_id, c.complaint_name, c.date_filed, c.status, 
                                       c.barangays_id, c.cp_number, c.complaints_person
                                FROM tbl_complaints c
                                WHERE c.status = 'pnp'
                                ORDER BY c.date_filed ASC
                            ");
                            $stmt->execute();

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $complaint_id = $row['complaints_id'];
                                $complaint_name = htmlspecialchars($row['complaint_name']);

                                if (!empty($row['barangays_id'])) {
                                    $stmtBar = $pdo->prepare("SELECT barangay_name FROM tbl_users_barangay WHERE barangays_id = ?");
                                    $stmtBar->execute([$row['barangays_id']]);
                                    $barangay_name = htmlspecialchars($stmtBar->fetchColumn());
                                } else {
                                    $barangay_name = 'Unknown';
                                }
                                
                                $address = $barangay_name;

                                echo "<tr>";
                                echo "<td>{$complaint_name}</td>";
                                echo "<td>{$address}</td>";
                                echo "<td><button class='btn btn-info btn-sm' data-bs-toggle='modal' data-bs-target='#viewDetailsModal' data-id='{$complaint_id}'>View Details</button></td>";
                                echo "</tr>";
                            }
                        } catch (PDOException $e) {
                            echo "<tr><td colspan='3'>Error fetching PNP complaints: " . $e->getMessage() . "</td></tr>";
                        }
                    }

                    displayPNPComplaints($pdo);
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for Viewing Details -->
    <div class="modal fade" id="viewDetailsModal" tabindex="-1" aria-labelledby="viewDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewDetailsModalLabel">Complaint Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="modalContent">
                        <!-- Content will be loaded here via JavaScript -->
                    </div>
                </div>
                <div class="modal-footer">

                <button type="button" class="btn btn-success" id="settleComplaintBtn">Settle Complaint</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="script.js"></script>
    <script>
 document.addEventListener('DOMContentLoaded', function () {
    var viewDetailsButtons = document.querySelectorAll('button[data-bs-target="#viewDetailsModal"]');

    viewDetailsButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var complaintId = this.getAttribute('data-id');

            fetch('get_complaint_details.php?id=' + complaintId)
                .then(response => response.json())
                .then(data => {
                    var modalContent = document.getElementById('modalContent');
                    if (data.error) {
                        modalContent.innerHTML = `<p>Error: ${data.error}</p>`;
                    } else {
                        modalContent.innerHTML = `
                            <p><strong>Complaint Name:</strong> ${data.complaint_name}</p>
                            <p><strong>Description:</strong> ${data.description}</p>
                            <p><strong>Date Filed:</strong> ${data.date_filed}</p>
                            <p><strong>Category:</strong> ${data.category}</p>
                            <p><strong>Barangay:</strong> ${data.barangay_name}</p>
                            <p><strong>Contact Number:</strong> ${data.cp_number}</p>
                            <p><strong>Complaints Person:</strong> ${data.complaints_person}</p>
                            <p><strong>Gender:</strong> ${data.gender}</p>
                            <p><strong>Place of Birth:</strong> ${data.place_of_birth}</p>
                            <p><strong>Age:</strong> ${data.age}</p>
                            <p><strong>Educational Background:</strong> ${data.educational_background}</p>
                            <p><strong>Civil Status:</strong> ${data.civil_status}</p>
                           
                        `;

                        // Add complaint ID to the settle button
                        var settleButton = document.getElementById('settleComplaintBtn');
                        settleButton.setAttribute('data-id', complaintId);
                    }
                })
                .catch(error => {
                    var modalContent = document.getElementById('modalContent');
                    modalContent.innerHTML = `<p>Error fetching details: ${error}</p>`;
                });
        });
    });


    // Handle "Settle Complaint" button click with SweetAlert
    document.getElementById('settleComplaintBtn').addEventListener('click', function () {
        var complaintId = this.getAttribute('data-id');

        if (complaintId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to settle this complaint?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, settle it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('settle_complaint.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ id: complaintId })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Settled!',
                                'The complaint has been settled.',
                                'success'
                            ).then(() => {
                                location.reload(); // Refresh the page to update the complaints table
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                'Failed to settle the complaint: ' + data.error,
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        Swal.fire(
                            'Error!',
                            'An error occurred: ' + error.message,
                            'error'
                        );
                    });
                }
            });
        }
    });
});


     
    </script>
</body>
</html>
