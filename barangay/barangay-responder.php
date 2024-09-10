<?php
// Start the session at the beginning
session_start();

// Include your database connection file
include '../connection/dbconn.php'; 

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

        $stmt = $pdo->prepare("
            SELECT c.*, b.barangay_name, cc.complaints_category, i.gender, i.place_of_birth, i.age, i.educational_background, i.civil_status,
                   GROUP_CONCAT(DISTINCT e.evidence_path SEPARATOR ',') AS evidence_paths,
                   GROUP_CONCAT(DISTINCT CONCAT(h.hearing_date, '|', h.hearing_time, '|', h.hearing_type, '|', h.hearing_status) SEPARATOR ',') AS hearing_history
            FROM tbl_complaints c
            JOIN tbl_users_barangay b ON c.barangays_id = b.barangays_id
            JOIN tbl_complaintcategories cc ON c.category_id = cc.category_id
            JOIN tbl_info i ON c.info_id = i.info_id
            LEFT JOIN tbl_evidence e ON c.complaints_id = e.complaints_id
            LEFT JOIN tbl_hearing_history h ON c.complaints_id = h.complaints_id
            WHERE c.status = 'Approved' AND b.barangay_name = ?
            GROUP BY c.complaints_id
            ORDER BY c.date_filed ASC
            LIMIT ?, ?
        ");

        $stmt->bindParam(1, $barangay_name, PDO::PARAM_STR);
        $stmt->bindParam(2, $start_from, PDO::PARAM_INT);
        $stmt->bindParam(3, $results_per_page, PDO::PARAM_INT);

        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            echo "<tr><td colspan='4'>No complaints found.</td></tr>";
        } else {
            $rowNumber = $start_from + 1; // Initialize row number

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $complaint_id = htmlspecialchars($row['complaints_id']);
                $complaint_name = htmlspecialchars($row['complaint_name']);
                $complaint_description = htmlspecialchars($row['complaints']);
                $complaint_category = htmlspecialchars($row['complaints_category']);
                $complaint_barangay = htmlspecialchars($row['barangay_name']);
                $complaint_contact = htmlspecialchars($row['cp_number']);
                $complaint_person = htmlspecialchars($row['complaints_person']);
                $complaint_gender = htmlspecialchars($row['gender']);
                $complaint_birth_place = htmlspecialchars($row['place_of_birth']);
                $complaint_age = htmlspecialchars($row['age']);
                $complaint_education = htmlspecialchars($row['educational_background']);
                $complaint_civil_status = htmlspecialchars($row['civil_status']);
                $complaint_evidence = htmlspecialchars($row['evidence_paths']);
                $complaint_date_filed = htmlspecialchars($row['date_filed']);
                $complaint_status = htmlspecialchars($row['status']);
                $complaint_hearing_status = htmlspecialchars($row['hearing_history']);

                echo "<tr>";
                echo "<td>{$rowNumber}</td>"; // Display row number
                echo "<td>{$complaint_name}</td>";
                echo "<td><button type='button' class='btn btn-primary view-details-btn' 
                            data-id='{$complaint_id}' data-name='{$complaint_name}' data-description='{$complaint_description}' 
                            data-category='{$complaint_category}' data-barangay='{$complaint_barangay}' 
                            data-contact='{$complaint_contact}' data-person='{$complaint_person}' 
                            data-gender='{$complaint_gender}' data-birth_place='{$complaint_birth_place}' 
                            data-age='{$complaint_age}' data-education='{$complaint_education}' 
                            data-civil_status='{$complaint_civil_status}' data-evidence_paths='{$complaint_evidence}' 
                            data-date_filed='{$complaint_date_filed}' data-status='{$complaint_status}' 
                            data-hearing_history='{$complaint_hearing_status}' 
                            data-bs-toggle='modal' data-bs-target='#complaintModal'>View Details</button></td>";
                echo "</tr>";

                $rowNumber++; // Increment row number
            }
        }
    } catch (PDOException $e) {
        echo "<tr><td colspan='4'>Error fetching complaints: " . $e->getMessage() . "</td></tr>";
    }
}

// Handle status update submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $complaint_id = $_POST['complaint_id'];
    $new_status = $_POST['new_status'];

    try {
        $responds = '';
        if ($new_status === 'settled_in_barangay') {
            $responds = 'barangay';
        } elseif ($new_status === 'pnp') {
            $responds = 'pnp';
        }

        $stmt = $pdo->prepare("UPDATE tbl_complaints SET status = ?, responds = ? WHERE complaints_id = ?");
        $stmt->execute([$new_status, $responds, $complaint_id]);

        header("Location: {$_SERVER['PHP_SELF']}?page={$page}");
        exit();
    } catch (PDOException $e) {
        echo "Error updating status: " . $e->getMessage();
    }
}

// Handle hearing update submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_hearing'])) {
    $complaint_id = $_POST['complaint_id'];
    $hearing_date = $_POST['hearing_date'];
    $hearing_time = $_POST['hearing_time'];
    $hearing_type = $_POST['hearing_type'];
    $hearing_status = $_POST['hearing_status'];

    try {
        // Delete existing hearing history for this complaint
        $stmt = $pdo->prepare("DELETE FROM tbl_hearing_history WHERE complaints_id = ?");
        $stmt->execute([$complaint_id]);

        // Insert new hearing details
        $stmt = $pdo->prepare("INSERT INTO tbl_hearing_history (complaints_id, hearing_date, hearing_time, hearing_type, hearing_status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$complaint_id, $hearing_date, $hearing_time, $hearing_type, $hearing_status]);

        header("Location: {$_SERVER['PHP_SELF']}?page={$page}");
        exit();
    } catch (PDOException $e) {
        echo "Error updating hearing details: " . $e->getMessage();
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
            text-align: center;
        }

        table {
    table-layout: fixed;
    width: 100%; /* Make table span the entire width */
  }
  th, td {
    text-align: center; /* Align content in the center */
    vertical-align: middle; /* Align content vertically in the middle */
  }
  th {
    width: 33%; /* Set equal width for each column */
  }
  td {
    word-wrap: break-word; /* Ensure long text breaks to fit in cells */
  }
</style>
<body>

    
<?php 

include '../includes/navbar.php';
include '../includes/sidebar.php';
include '../includes/edit-profile.php';
?>
    <!-- Page Content -->
    <div class="content">
    <div class="container">
        <h2 class="mt-3 mb-4">Uploaded Complaints</h2>
        
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th> <!-- Added for row numbers -->
                
                    <th>Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php displayComplaints($pdo, $start_from, $results_per_page); ?>
            </tbody>
        </table>
    </div>
</div>




    <div class="modal fade" id="viewComplaintModal" tabindex="-1" aria-labelledby="viewComplaintModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewComplaintModalLabel">Complaint Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Existing fields -->

                <div id="hearingSection" style="display: none;">
    <h5 class="mt-4">Set Hearing Date and Time</h5>
    <form id="hearingForm">
        <div class="mb-3">
            <label for="hearing-date" class="form-label">Hearing Date</label>
            <input type="date" class="form-control" id="hearing-date" name="hearing_date" >
        </div>
        <div class="mb-3">
            <label for="hearing-time" class="form-label">Hearing Time</label>
            <input type="time" class="form-control" id="hearing-time" name="hearing_time" >
        </div>
        <div class="mb-3">
            <label for="hearing-type" class="form-label">Hearing Type</label>
            <select class="form-select" id="hearing-type" name="hearing_type" >
                <option value="" disabled selected>Select Hearing Type</option>
                <option value="First Hearing">First Hearing</option>
                <option value="Second Hearing">Second Hearing</option>
                <option value="Third Hearing">Third Hearing</option>
            </select>
        </div>

        <div class="mb-3">
                            <label for="hearing-status" class="form-label">Hearing Status</label>
                            <select class="form-select" id="hearing-status" name="hearing_status" >
                                <option value="" disabled selected>Select Hearing Status</option>
                                <option value="Attended">Attended</option>
                                <option value="Not Attended">Not Attended</option>
                                <option value="Not Resolved">Not Resolved</option>
                            </select>
                        </div>
        <button type="submit" class="btn btn-primary">Set Hearing</button>
    </form>
</div>


                <!-- Additional fields as needed -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>





            <nav>
                <ul class="pagination justify-content-center">
                    <?php
                    $pagination_range = 2; // Range of pages to display before and after the current page
                    for ($i = max(1, $page - $pagination_range); $i <= min($page + $pagination_range, $total_pages); $i++) {
                        $active = $i == $page ? 'active' : '';
                        echo "<li class='page-item {$active}'><a class='page-link' href='barangay-responder.php?page={$i}'>{$i}</a></li>";
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </div>


    <?php

include 'complaints_viewmodal.php';

?>

    <!-- Complaint Modal -->






    <!-- Bootstrap JS and dependencies -->
   
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../scripts/script.js"></script>



<!-- Bootstrap JavaScript link -->
 
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>

    
  <script>
             document.addEventListener('DOMContentLoaded', function () {
        var profilePic = document.querySelector('.profile');
        var editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));

        profilePic.addEventListener('click', function () {
            editProfileModal.show();
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
    const viewButtons = document.querySelectorAll('.view-details-btn');
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Populate modal with data
            document.getElementById('modal-name').textContent = this.dataset.name;
            document.getElementById('modal-description').textContent = this.dataset.description;
            document.getElementById('modal-category').textContent = this.dataset.category;
            document.getElementById('modal-barangay').textContent = this.dataset.barangay;
            document.getElementById('modal-contact').textContent = this.dataset.contact;
            document.getElementById('modal-person').textContent = this.dataset.person;
            document.getElementById('modal-gender').textContent = this.dataset.gender;
            document.getElementById('modal-birth_place').textContent = this.dataset.birth_place;
            document.getElementById('modal-age').textContent = this.dataset.age;
            document.getElementById('modal-education').textContent = this.dataset.education;
            document.getElementById('modal-civil_status').textContent = this.dataset.civil_status;
            document.getElementById('modal-date_filed').textContent = this.dataset.date_filed;
            document.getElementById('modal-status').textContent = this.dataset.status;
         

            // Handle hearing history display
            var hearingHistoryHtml = '';
            if (this.dataset.hearing_history) {
                var hearings = this.dataset.hearing_history.split(',');
                hearingHistoryHtml = '<h5>Hearing History:</h5><table class="table"><thead><tr><th>Date</th><th>Time</th><th>Type</th><th>Status</th></tr></thead><tbody>';
                hearings.forEach(function (hearing) {
                    var details = hearing.split('|');
                    hearingHistoryHtml += `
                        <tr>
                            <td>${details[0]}</td>
                            <td>${details[1]}</td>
                            <td>${details[2]}</td>
                            <td>${details[3]}</td>
                        </tr>
                    `;
                });
                hearingHistoryHtml += '</tbody></table>';
            } else {
                hearingHistoryHtml = '<p>No hearing history available.</p>';
            }
            document.getElementById('modalHearingHistorySection').innerHTML = hearingHistoryHtml;

            // Handle evidence display
            var evidenceHtml = '<h5>Evidence:</h5>';
            if (this.dataset.evidence_paths) {
                var evidencePaths = this.dataset.evidence_paths.split(',').map(path => path.trim());
                if (evidencePaths.length > 0) {
                    evidenceHtml += '<ul>';
                    evidencePaths.forEach(function (path) {
                        evidenceHtml += `<li><a href="../uploads/${path}" target="_blank">View Evidence</a></li>`;
                    });
                    evidenceHtml += '</ul>';
                } else {
                    evidenceHtml += '<p>No evidence available.</p>';
                }
                document.getElementById('modalEvidenceSection').style.display = 'block';
            } else {
                evidenceHtml += '<p>No evidence available.</p>';
                document.getElementById('modalEvidenceSection').style.display = 'block';
            }
            document.getElementById('modalEvidenceSection').innerHTML = evidenceHtml;

            // Store the complaint ID in the modal for use later
            document.getElementById('complaintModal').setAttribute('data-complaint-id', this.dataset.id);
        });
    });



    // Handle Move to PNP button click
    document.getElementById('moveToPnpBtn').addEventListener('click', function() {
        updateComplaintStatus('pnp');
    });

    // Handle Settle in Barangay button click
    document.getElementById('settleInBarangayBtn').addEventListener('click', function() {
        updateComplaintStatus('settled_in_barangay');
    });

    function updateComplaintStatus(newStatus) {
        const complaintId = document.getElementById('complaintModal').getAttribute('data-complaint-id');
        
        fetch('update_complaint_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `complaint_id=${complaintId}&new_status=${newStatus}`
        })
        .then(response => response.text())
        .then(result => {
            alert(result); // Display success or error message
            window.location.reload(); // Reload the page to reflect changes
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
});



document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners to all view-details buttons
    const viewButtons = document.querySelectorAll('.view-details-btn');
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const complaintId = this.dataset.id;
            const status = this.dataset.status;

            // Set the complaint ID as a data attribute on the modal
            document.getElementById('viewComplaintModal').setAttribute('data-complaint-id', complaintId);

            // Show the hearing section if the complaint status is 'Approved'
            document.getElementById('hearingSection').style.display = status === 'Approved' ? 'block' : 'none';

            // Fetch existing hearing details
            fetch(`set_hearing.php?complaint_id=${complaintId}`)
                .then(response => response.json())
                .then(data => {
                    if (Array.isArray(data) && data.length > 0) {
                        const hearing = data[0];
                        document.getElementById('hearing-date').value = hearing.hearing_date || '';
                        document.getElementById('hearing-time').value = hearing.hearing_time || '';
                        document.getElementById('hearing-type').value = hearing.hearing_type || '';
                        document.getElementById('hearing-status').value = hearing.hearing_status || '';
                    } else {
                        document.getElementById('hearing-date').value = '';
                        document.getElementById('hearing-time').value = '';
                        document.getElementById('hearing-type').value = '';
                        document.getElementById('hearing-status').value = '';
                    }
                })
                .catch(error => console.error('Error fetching hearing details:', error));

            // Show the modal
            const modal = new bootstrap.Modal(document.getElementById('viewComplaintModal'));
            modal.show();
        });
    });

    // Handle form submission for setting hearing details
    document.getElementById('hearingForm').addEventListener('submit', function(event) {
        event.preventDefault();
        setHearingDetails();
    });

    function setHearingDetails() {
        const complaintId = document.getElementById('viewComplaintModal').getAttribute('data-complaint-id');
        const hearingDate = document.getElementById('hearing-date').value;
        const hearingTime = document.getElementById('hearing-time').value;
        const hearingType = document.getElementById('hearing-type').value;
        const hearingStatus = document.getElementById('hearing-status').value;

        // Send the form data to the server
        fetch('set_hearing.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `complaint_id=${complaintId}&hearing_date=${hearingDate}&hearing_time=${hearingTime}&hearing_type=${hearingType}&hearing_status=${hearingStatus}`
        })
        .then(response => response.text())
        .then(result => {
            alert(result); // Display success or error message
            window.location.reload(); // Reload the page to reflect changes
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
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



document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.view-details-btn').forEach(button => {
        button.addEventListener('click', function() {
            const complaintId = this.dataset.id;
            
            // Fetch hearing history
            fetch(`set_hearing.php?complaint_id=${complaintId}`)
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