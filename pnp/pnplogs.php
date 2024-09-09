
<?php

// Start output buffering
session_start();
$firstName = $_SESSION['first_name'];
$middleName = $_SESSION['middle_name'];
$lastName = $_SESSION['last_name'];
$extensionName = $_SESSION['extension_name'] ?? '';
$email = $_SESSION['email'] ?? '';
$barangay_name = $_SESSION['barangay_name'] ?? '';
$pic_data = $_SESSION['pic_data'] ?? '';

include '../connection/dbconn.php'; 

// Function to display basic complaint information in the table
function displayComplaintDetails($pdo, $search_query = '') {
    try {
        // Prepare the search query
        $search_query = '%' . $search_query . '%';

        // Fetch complaints from tbl_complaints table with additional information and search filter
        $stmt = $pdo->prepare("
            SELECT c.complaints_id, c.complaint_name, b.barangay_name
            FROM tbl_complaints c
            LEFT JOIN tbl_users_barangay b ON c.barangays_id = b.barangays_id
            WHERE c.responds = 'pnp'
            AND (c.complaint_name LIKE ? OR b.barangay_name LIKE ?)
            ORDER BY c.date_filed ASC
        ");

        // Bind search parameters
        $stmt->bindParam(1, $search_query, PDO::PARAM_STR);
        $stmt->bindParam(2, $search_query, PDO::PARAM_STR);

        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            echo "<tr><td colspan='3'>No complaints found.</td></tr>";
        } else {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Display complaint details
                $complaint_id = htmlspecialchars($row['complaints_id']);
                $complaint_name = htmlspecialchars($row['complaint_name']);
                $barangay_name = htmlspecialchars($row['barangay_name']);

                echo "<tr>";
                echo "<td>{$complaint_name}</td>";
                echo "<td>{$barangay_name}</td>";
                echo "<td><button type='button' class='btn btn-sm btn-info' onclick='loadComplaintDetails({$complaint_id})'>View Details</button></td>";
                echo "</tr>";
            }
        }
    } catch (PDOException $e) {
        echo "<tr><td colspan='3'>Error fetching PNP complaints logs: " . $e->getMessage() . "</td></tr>";
    }
}

// Get the search query from the GET request if available
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
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
    <link rel="stylesheet" type="text/css" href="../styles/style.css">
</head>


<style>
.popover-content {
    background-color: #343a40; /* Dark background to contrast with white */
    color: #ffffff; /* White text color */
    padding: 10px; /* Add some padding */
    border: 1px solid #495057; /* Optional: border for better visibility */
    border-radius: 5px; /* Optional: rounded corners */
    max-height: 300px; /* Ensure it doesn't grow too large */
    overflow-y: auto; /* Add vertical scroll if needed */
}

/* Adjust the arrow for the popover to ensure it points correctly */
.popover .popover-arrow {
    border-top-color: #343a40; /* Match the background color */
}

    </style>
<body>
<?php 

include '../includes/pnp-nav.php';
include '../includes/pnp-bar.php';
?>

    <!-- Page Content -->
    <div class="content">
        <div class="container">
            <h2 class="mt-3 mb-4">PNP Complaints Logs</h2>
            
            <!-- Search Form -->
           
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
                        // Function call to display PNP complaints logs with search filter
                        displayComplaintDetails($pdo, $search_query);
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="hearingHistoryModal" tabindex="-1" aria-labelledby="hearingHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="hearingHistoryModalLabel">Hearing History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Type</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="hearingHistoryTableBody">
                        <!-- Hearing history rows will be populated here -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProfileForm" action="update_profile.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="editFirstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="editFirstName" name="first_name" value="<?php echo htmlspecialchars($firstName); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="editMiddleName" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="editMiddleName" name="middle_name" value="<?php echo htmlspecialchars($middleName); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="editLastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="editLastName" name="last_name" value="<?php echo htmlspecialchars($lastName); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="editExtensionName" class="form-label">Extension Name</label>
                        <input type="text" class="form-control" id="editExtensionName" name="extension_name" value="<?php echo htmlspecialchars($extensionName); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="editProfilePic" class="form-label">Profile Picture</label>
                        <input type="file" class="form-control" id="editProfilePic" name="profile_pic">
                    </div>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
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
    <script src="../scripts/script.js"></script>

    <!-- JavaScript to handle modal content dynamically -->
    <script>



document.addEventListener('DOMContentLoaded', function () {
    var hearingHistoryModal = document.getElementById('hearingHistoryModal');
    
    hearingHistoryModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var complaintId = button.getAttribute('data-complaint-id');

        // Fetch hearing history
        fetch(`hearing.php?complaint_id=${complaintId}`)
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('hearingHistoryTableBody');
                tableBody.innerHTML = ''; // Clear existing rows

                if (data.error) {
                    console.error('Error:', data.error);
                    return;
                }

                data.forEach(hearing => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${hearing.hearing_date}</td>
                        <td>${hearing.hearing_time}</td>
                        <td>${hearing.hearing_type}</td>
                        <td>${hearing.hearing_status}</td>
                    `;
                    tableBody.appendChild(row);
                });
            })
            .catch(error => console.error('Fetch error:', error));
    });
});


        var profilePic = document.querySelector('.profile');
        var editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));

        profilePic.addEventListener('click', function () {
            editProfileModal.show();
        });
    
     
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



        document.addEventListener("DOMContentLoaded", function () {
    const notificationButton = document.getElementById('notificationButton');
    const modalBody = document.getElementById('notificationModalBody');

    function fetchNotifications() {
        return fetch('notifications.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json().catch(() => ({ success: false }))) // Handle JSON parsing errors
        .then(data => {
            if (data.success) {
                const notificationCount = data.notifications.length;
                const notificationCountBadge = document.getElementById("notificationCount");

                if (notificationCount > 0) {
                    notificationCountBadge.textContent = notificationCount;
                    notificationCountBadge.classList.remove("d-none");
                } else {
                    notificationCountBadge.textContent = "0";
                    notificationCountBadge.classList.add("d-none");
                }

                let notificationListHtml = '';
                if (notificationCount > 0) {
                    data.notifications.forEach(notification => {
                        notificationListHtml += `
                            <div class="dropdown-item" 
                                 data-id="${notification.complaints_id}" 
                                 data-status="${notification.status}" 
                                 data-complaint-name="${notification.complaint_name}" 
                                 data-barangay-name="${notification.barangay_name}">
                                Complaint: ${notification.complaint_name}<br>
                                Barangay: ${notification.barangay_name}<br>
                                Status: ${notification.status}
                            </div>
                        `;
                    });
                } else {
                    notificationListHtml = '<div class="dropdown-item text-center">No new notifications</div>';
                }

                const popoverInstance = bootstrap.Popover.getInstance(notificationButton);
                if (popoverInstance) {
                    popoverInstance.setContent({
                        '.popover-body': notificationListHtml
                    });
                } else {
                    new bootstrap.Popover(notificationButton, {
                        html: true,
                        content: function () {
                            return `<div class="popover-content">${notificationListHtml}</div>`;
                        },
                        container: 'body'
                    });
                }

                document.querySelectorAll('.popover-content .dropdown-item').forEach(item => {
                    item.addEventListener('click', function () {
                        const notificationId = this.getAttribute('data-id');
                        markNotificationAsRead(notificationId);
                    });
                });
            } else {
                console.error("Failed to fetch notifications");
            }
        })
        .catch(error => {
            console.error("Error fetching notifications:", error);
        });
    }

    function markNotificationAsRead(notificationId) {
        fetch('notifications.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ notificationId: notificationId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Notification marked as read');
                fetchNotifications(); // Refresh notifications
            } else {
                console.error("Failed to mark notification as read");
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    }

    fetchNotifications();

    notificationButton.addEventListener('shown.bs.popover', function () {
        markNotificationsAsRead();
    });

    function markNotificationsAsRead() {
        fetch('notifications.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ markAsRead: true })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const badge = document.querySelector(".badge.bg-danger");
                if (badge) {
                    badge.classList.add("d-none");
                }
            } else {
                console.error("Failed to mark notifications as read");
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    }
});
    </script>

    <!-- Toggle Sidebar Script -->
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
                window.location.href = " ../reg/login.php?logout=<?php echo $_SESSION['user_id']; ?>";
            }
        });
        }
    </script>
</body>
</html>
