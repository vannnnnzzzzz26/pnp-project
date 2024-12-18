
<?php 


session_start(); 
include '../includes/bypass.php';


$firstName = $_SESSION['first_name'] ?? '';
$middleName = $_SESSION['middle_name'] ?? '';
$lastName = $_SESSION['last_name'] ?? '';
$extensionName = $_SESSION['extension_name'] ?? '';
$email = $_SESSION['email'] ?? '';
$barangay_name = $_SESSION['barangay_name'] ?? '';
$pic_data = $_SESSION['pic_data'] ?? '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PNP Complaints</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../styles/style.css">
</head>

<style>
.popover-content {
    background-color: whitesmoke; 
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


.sidebar-toggler {
    display: flex;
    align-items: center;
    padding: 10px;
    background-color: transparent; /* Changed from #082759 to transparent */
    border: none;
    cursor: pointer;
    color: white;
    text-align: left;
    width: auto; /* Adjust width automatically */
}
.sidebar{
  background-color: #082759;
}
.navbar{
  background-color: #082759;

}

.navbar-brand{
color: whitesmoke;
margin-left: 5rem;
}

.table thead th {
            background-color: #082759;

            color: #ffffff;
        
        }

      
    </style>
<body>
 
<?php 

include '../includes/pnp-nav.php';
include '../includes/pnp-bar.php';
?>
<center>
<div class="content">
    <div class="container">
        <h2 class="mt-3 mb-4">Complaints</h2>

       <!-- Filter Row for Barangay and Category -->
<div class="row mb-3">
   <!-- Filter Row for Barangay, Category, and Date Range -->
<div class="row mb-3">
    <!-- Dropdown Filter for Barangay -->
    <div class="col-md-6">
        <label for="barangayFilter" class="form-label">Filter by Barangay</label>
        <select id="barangayFilter" class="form-select">
            <option value="">All Barangays</option>
            <!-- Add all barangay options -->
            <option value="Angoluan">Angoluan</option>
            <option value="Annafunan">Annafunan</option>
            <option value="Arabiat">Arabiat</option>
            <!-- Add all the other barangays here -->
            <option value="Villa Ysmael (formerly T. Belen)">Villa Ysmael (formerly T. Belen)</option>
        </select>
    </div>

    <!-- Dropdown Filter for Category -->
    <div class="col-md-6">
        <label for="categoryFilter" class="form-label">Filter by Category</label>
        <select id="categoryFilter" class="form-select">
            <option value="">All Categories</option>
            <?php
            // Fetch categories from tbl_complaintcategories
            $stmtCat = $pdo->prepare("SELECT complaints_category FROM tbl_complaintcategories");
            $stmtCat->execute();
            while ($category = $stmtCat->fetch(PDO::FETCH_ASSOC)) {
                $categoryName = htmlspecialchars($category['complaints_category']);
                echo "<option value=\"{$categoryName}\">{$categoryName}</option>";
            }
            ?>
        </select>
    </div>
</div>

<!-- Filter Row for Date Range (From and To) -->
<div class="row mb-3">
    <!-- Date From -->
    <div class="col-md-6">
        <label for="dateFrom" class="form-label">From Date</label>
        <input type="date" id="dateFrom" class="form-control">
    </div>

    <!-- Date To -->
    <div class="col-md-6">
        <label for="dateTo" class="form-label">To Date</label>
        <input type="date" id="dateTo" class="form-control">
    </div>
</div>


        <div class="table">
            <table class="table table-striped table-bordered table-center" id="complaintsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Date Filed</th>
                        <th>Address</th>
                        <th>Category</th> <!-- Add Category Column -->
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
    // Function to display PNP complaints
    function displayPNPComplaints($pdo) {
        try {
            // Prepare the SQL query
            $stmt = $pdo->prepare("
                SELECT c.complaints_id, c.complaint_name, c.date_filed, c.status, 
                       c.barangays_id, c.complaints_person, cat.complaints_category
                FROM tbl_complaints c
                JOIN tbl_complaintcategories cat ON c.category_id = cat.category_id
                WHERE c.responds = 'pnp'
                ORDER BY c.date_filed ASC
            ");
            $stmt->execute();
    
            // Check if there are any rows returned
            if ($stmt->rowCount() > 0) {
                $row_number = 1; // Initialize the row number
    
                // Loop through the results and display each complaint
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $complaint_id = $row['complaints_id'];
                    $complaint_name = htmlspecialchars($row['complaint_name']);
                    $category = htmlspecialchars($row['complaints_category']);
                    $date_filed = htmlspecialchars($row['date_filed']);
    
                    // Fetch barangay name based on barangays_id
                    if (!empty($row['barangays_id'])) {
                        $stmtBar = $pdo->prepare("SELECT saan FROM tbl_users_barangay WHERE barangays_id = ?");
                        $stmtBar->execute([$row['barangays_id']]);
                        $barangay_name = htmlspecialchars($stmtBar->fetchColumn());
                    } else {
                        $barangay_name = 'Unknown';
                    }
    
                    $address = $barangay_name;
    
                    // Display the table row
                    echo "<tr>";
                    echo "<td>{$row_number}</td>";
                    echo "<td>{$complaint_name}</td>";
                    echo "<td>{$date_filed}</td>";
                    echo "<td class='barangay'>{$address}</td>";
                    echo "<td class='category'>{$category}</td>"; // Display category
                    echo "<td><button class='btn btn-info btn-sm' data-bs-toggle='modal' data-bs-target='#viewDetailsModal' data-id='{$complaint_id}'>View Details</button></td>";
                    echo "</tr>";
    
                    $row_number++;
                }
            } else {
                echo "<tr><td colspan='6' class='text-center'>No record found</td></tr>";
            }
        } catch (PDOException $e) {
            echo "<tr><td colspan='6' class='text-center'>Error fetching PNP complaints: " . $e->getMessage() . "</td></tr>";
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

  </center>


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






    <!-- Bootstrap JS -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="../scripts/script.js"></script>
    <script>


document.getElementById('barangayFilter').addEventListener('change', filterTable);
document.getElementById('categoryFilter').addEventListener('change', filterTable);
document.getElementById('dateFrom').addEventListener('change', filterTable);
document.getElementById('dateTo').addEventListener('change', filterTable);

function filterTable() {
    const barangayFilter = document.getElementById('barangayFilter').value.toLowerCase();
    const categoryFilter = document.getElementById('categoryFilter').value.toLowerCase();
    const dateFrom = document.getElementById('dateFrom').value;
    const dateTo = document.getElementById('dateTo').value;

    const rows = document.querySelectorAll('#complaintsTable tbody tr');
    let visibleRowCount = 0; // Track the number of visible rows

    // Remove "No record found" row if it exists before filtering
    let noRecordRow = document.querySelector('#noRecordRow');
    if (noRecordRow) {
        noRecordRow.remove();
    }

    rows.forEach(row => {
        const barangay = row.querySelector('.barangay').textContent.toLowerCase();
        const category = row.querySelector('.category').textContent.toLowerCase();
        const dateFiled = row.querySelector('td:nth-child(3)').textContent;

        // Check if the row matches the date range, barangay, and category filters
        let dateMatch = true;
        if (dateFrom && dateTo) {
            const filedDate = new Date(dateFiled);
            const fromDate = new Date(dateFrom);
            const toDate = new Date(dateTo);
            dateMatch = filedDate >= fromDate && filedDate <= toDate;
        }

        // Apply filters
        if ((barangayFilter === "" || barangay.includes(barangayFilter)) &&
            (categoryFilter === "" || category.includes(categoryFilter)) &&
            dateMatch) {
            row.style.display = '';
            visibleRowCount++; // Increment the visible row count
        } else {
            row.style.display = 'none';
        }
    });

    // Check if any row is visible, otherwise display the "No record found" message
    const tableBody = document.querySelector('#complaintsTable tbody');
    if (visibleRowCount === 0) {
        noRecordRow = document.createElement('tr');
        noRecordRow.id = 'noRecordRow';
        noRecordRow.innerHTML = '<td colspan="6" class="text-center">No record found</td>';
        tableBody.appendChild(noRecordRow);
    }
}


document.addEventListener("DOMContentLoaded", function () {
    const notificationButton = document.getElementById('notificationButton');
    const notificationCountBadge = document.getElementById("notificationCount");

    function fetchNotifications() {
        return fetch('notifications.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const notificationCount = data.notifications.length;
                updateNotificationBadge(notificationCount);
                updatePopoverContent(data.notifications);
            } else {
                console.error("Failed to fetch notifications");
            }
        })
        .catch(error => {
            console.error("Error fetching notifications:", error);
        });
    }

    function updateNotificationBadge(count) {
        notificationCountBadge.textContent = count > 0 ? count : "0";
        notificationCountBadge.classList.toggle("d-none", count === 0);
    }

    function updatePopoverContent(notifications) {
        let notificationListHtml = notifications.length > 0 ?
            notifications.map(notification => `
                <div class="dropdown-item" data-id="${notification.complaints_id}">
                    Complaint: ${notification.complaint_name}<br>
                    Barangay: ${notification.barangay_name}<br>
                    Status: ${notification.status}
                    <hr>
                </div>
            `).join('') :
            '<div class="dropdown-item text-center">No new notifications</div>';

        const popoverInstance = bootstrap.Popover.getInstance(notificationButton);
        if (popoverInstance) {
            popoverInstance.setContent({ '.popover-body': notificationListHtml });
        } else {
            new bootstrap.Popover(notificationButton, {
                html: true,
                content: function () {
                    return `<div class="popover-content">${notificationListHtml}</div>`;
                },
                container: 'body'
            });
        }

        // Add click event listener to mark as read
        document.querySelectorAll('.popover-content .dropdown-item').forEach(item => {
            item.addEventListener('click', function () {
                const notificationId = this.getAttribute('data-id');
                markNotificationAsRead(notificationId);
            });
        });
    }

    function markNotificationAsRead(notificationId) {
  
        fetch('notifications.php?action=update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ notificationId, userId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchNotifications(); // Refresh notifications
            } else {
                console.error("Failed to mark notification as read");
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    }

    // Fetch notifications when the page loads
    fetchNotifications();
});





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
                        var evidenceHtml = '';

                        if (data.evidence && data.evidence.length > 0) {
                            evidenceHtml = '<h5>Evidence:</h5><ul>';
                            data.evidence.forEach(function(evidencePath) {
                                evidenceHtml += `<li><a href="../uploads/${evidencePath}" target="_blank">View Evidence</a></li>`;
                            });
                            evidenceHtml += '</ul>';
                        } else {
                            evidenceHtml = '<p>No evidence available.</p>';
                        }

                        var hearingHistoryHtml = '';

                        if (data.hearing_history && data.hearing_history.length > 0) {
                            hearingHistoryHtml = '<h5>Hearing History:</h5><table class="table"><thead><tr><th>Date</th><th>Time</th><th>Type</th><th>Status</th></tr></thead><tbody>';
                            data.hearing_history.forEach(function(hearing) {
                                hearingHistoryHtml += `
                                    <tr>
                                        <td>${hearing.hearing_date}</td>
                                        <td>${hearing.hearing_time}</td>
                                        <td>${hearing.hearing_type}</td>
                                        <td>${hearing.hearing_status}</td>
                                    </tr>
                                `;
                            });
                            hearingHistoryHtml += '</tbody></table>';
                        } else {
                            hearingHistoryHtml = '<p>No hearing history available.</p>';
                        }

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
                             <p><strong>Nationality:</strong> ${data.nationality}</p>

                            ${evidenceHtml}
                            ${hearingHistoryHtml}
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


   



document.addEventListener('DOMContentLoaded', function () {
        var profilePic = document.querySelector('.profile');
        var editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));

        profilePic.addEventListener('click', function () {
            editProfileModal.show();
        });
    });

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
                                <hr>
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
