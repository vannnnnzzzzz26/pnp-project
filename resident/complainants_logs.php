<?php

include '../connection/dbconn.php';
include '../resident/notifications.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize variables from session data
$firstName = $_SESSION['first_name'];
$middleName = $_SESSION['middle_name'];
$lastName = $_SESSION['last_name'];
$extensionName = isset($_SESSION['extension_name']) ? $_SESSION['extension_name'] : '';
$cp_number = isset($_SESSION['cp_number']) ? $_SESSION['cp_number'] : '';
$barangay = isset($_SESSION['barangays_id']) ? $_SESSION['barangays_id'] : '';
$pic_data = isset($_SESSION['pic_data']) ? $_SESSION['pic_data'] : '';

// Construct user's full name
$userFullName = $firstName . ' ' . $middleName . ' ' . $lastName;
if (!empty($extensionName)) {
    $userFullName .= ' ' . $extensionName;
}

$stmt = $pdo->prepare("
    SELECT c.*, cc.complaints_category, b.barangay_name, 
           GROUP_CONCAT(DISTINCT e.evidence_path) AS evidence_paths,
           GROUP_CONCAT(DISTINCT CONCAT(h.hearing_date, '|', h.hearing_time, '|', h.hearing_type, '|', h.hearing_status)) AS hearing_history
    FROM tbl_complaints c
    LEFT JOIN tbl_complaintcategories cc ON c.category_id = cc.category_id
    LEFT JOIN tbl_users_barangay b ON c.barangays_id = b.barangays_id
    LEFT JOIN tbl_evidence e ON c.complaints_id = e.complaints_id
    LEFT JOIN tbl_hearing_history h ON c.complaints_id = h.complaints_id
    WHERE c.complaint_name = ?
    GROUP BY c.complaints_id
");

$stmt->execute([$userFullName]);
$complaints = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Debug: Check the evidence paths
foreach ($complaints as $complaint) {
    // You can add debug output here if needed
    // echo "Evidence Paths: " . $complaint['evidence_paths'];
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaints Status</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
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
}


.table thead th {
            background-color: #082759;

            color: #ffffff;
            text-align: center;
        }

    </style>
<body>

<?php 
include '../includes/resident-nav.php';
include '../includes/resident-bar.php';
?>

<div class="content" id="content">
    <div class="container mt-4">
        <h1 class="text-center">Complaints Status</h1>
        <div class="row justify-content-center">
            <div class="col-md-9 mx-auto">
                <?php if (empty($complaints)): ?>
                    <div class="alert alert-info text-center" role="alert">
                        You haven't submitted any complaints yet.
                    </div>
                <?php else: ?>
                    <div class="table-responsive"> <!-- Added to make the table responsive -->
                        <table class="table table-striped table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>#</th> <!-- Row number -->
                                    <th scope="col">Complaint Name</th>
                                    <th scope="col">date filed</th>
                                    <th scope="col">Ano (What)</th>
                                    <th scope="col">Saan (Where)</th>
                                    <th scope="col">Kailan (When)</th>
                                    <th scope="col">Paano (How)</th>
                                    <th scope="col">Bakit (Why)</th>
                                    <th scope="col">View Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $rowNumber = 1; // Initialize row number
                                foreach ($complaints as $complaint): ?>
                                    <tr>
                                        <td><?php echo $rowNumber++; ?></td> <!-- Display row number -->
                                        <td><?php echo htmlspecialchars($complaint['complaint_name']); ?></td>
                                        <td><?php echo htmlspecialchars($complaint['date_filed']); ?></td>
                                        <td><?php echo htmlspecialchars($complaint['ano']); ?></td>
                                        <td><?php echo htmlspecialchars($complaint['saan']); ?></td>
                                        <td><?php echo htmlspecialchars($complaint['kailan']); ?></td>
                                        <td><?php echo htmlspecialchars($complaint['paano']); ?></td>
                                        <td><?php echo htmlspecialchars($complaint['bakit']); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewComplaintModal" data-complaint='<?php echo htmlspecialchars(json_encode($complaint), ENT_QUOTES, 'UTF-8'); ?>'>View</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>




<!-- Complaint Details Modal -->
<div class="modal fade" id="viewComplaintModal" tabindex="-1" aria-labelledby="viewComplaintModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Make modal larger -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewComplaintModalLabel">Complaint Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <!-- First Column -->
                        <div class="col-md-6">
                            <p><strong>Date Filed:</strong> <span id="modalDateFiled"></span></p>
                            <p><strong>Complaint Name:</strong> <span id="modalComplaintName"></span></p>
                            <p><strong>Category:</strong> <span id="modalCategory"></span></p>
                            <p><strong>Barangay:</strong> <span id="modalBarangay"></span></p>
                            <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                            <p><strong>Complaints Person:</strong> <span id="modalComplaintsPerson"></span></p>
                        </div>

                        <!-- Second Column -->
                        <div class="col-md-6">
                            <p><strong>Complaint Description:</strong> <span id="modalComplaintDescription"></span></p>
                            <p><strong>Ano (What):</strong> <span id="modalAno"></span></p>
                            <p><strong>Saan (Where):</strong> <span id="modalSaan"></span></p>
                            <p><strong>Kailan (When):</strong> <span id="modalKailan"></span></p>
                            <p><strong>Paano (how):</strong> <span id="modalPaano"></span></p>
                            <p><strong>Bakit (why):</strong> <span id="modalBakit"></span></p>


                            
                        <div class="col-md-6">
                            <h6><strong>Hearing History</strong></h6>
                            <div id="modalHearingHistorySection">
                                <!-- Hearing history will be populated here -->
                            </div>
                        </div>
                        </div>
                    </div>

                    

                    <!-- Evidence Section -->
                 
                        <div class="col-md-12">
                            <h6><strong>Evidence</strong></h6>
                            <div id="modalEvidenceSection" style="display: none;">
                                <ul id="modalEvidenceList">
                                    <!-- Evidence list will be populated here -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




   
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../scripts/script.js"></script>



<!-- Bootstrap JavaScript link -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.all.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var complaintModal = document.getElementById('viewComplaintModal');
    
    complaintModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var complaint = JSON.parse(button.getAttribute('data-complaint'));

        // Populate modal with complaint details
        document.getElementById('modalDateFiled').textContent = complaint.date_filed || 'N/A';
        document.getElementById('modalComplaintName').textContent = complaint.complaint_name || 'N/A';
        document.getElementById('modalComplaintDescription').textContent = complaint.complaints || 'N/A';
        document.getElementById('modalCategory').textContent = complaint.complaints_category || 'N/A';
        document.getElementById('modalBarangay').textContent = complaint.barangay_name || 'N/A';
        document.getElementById('modalStatus').textContent = complaint.status || 'N/A';
        document.getElementById('modalComplaintsPerson').textContent = complaint.complaints_person || 'N/A';
        document.getElementById('modalAno').textContent = complaint.ano || 'N/A';
        document.getElementById('modalSaan').textContent = complaint.saan || 'N/A';
        document.getElementById('modalKailan').textContent = complaint.kailan || 'N/A';
        document.getElementById('modalPaano').textContent = complaint.paano || 'N/A';
        document.getElementById('modalBakit').textContent = complaint.bakit || 'N/A';
 
        // Hearing Details
        var hearingHistoryHtml = '';
        if (complaint.hearing_history) {
            var hearings = complaint.hearing_history.split(',');
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

        // Evidence
        var evidenceHtml = '<h5>Evidence:</h5>';
        if (complaint.evidence_paths) {
            var evidencePaths = complaint.evidence_paths.split(',').map(path => path.trim());
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
    });
});



    document.addEventListener("DOMContentLoaded", function () {
    const notificationButton = document.getElementById('notificationButton');
    const modalBody = document.getElementById('notificationModalBody');

    // Function to fetch notifications
    function fetchNotifications() {
        return fetch('notifications.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const notificationCount = data.notifications.length;
                const notificationCountBadge = document.getElementById("notificationCount");

                // Update notification count
                if (notificationCount > 0) {
                    notificationCountBadge.textContent = notificationCount;
                    notificationCountBadge.classList.remove("d-none");
                } else {
                    notificationCountBadge.textContent = "0";
                    notificationCountBadge.classList.add("d-none");
                }

                // Populate notifications
                let notificationListHtml = '';
                if (notificationCount > 0) {
                    data.notifications.forEach(notification => {
                        notificationListHtml += `
                                  <div class="dropdown-item" 
     data-id="${notification.id}" 
     data-status="${notification.status}" 
     data-hearing-type="${notification.hearing_type}" 
     data-hearing-date="${notification.hearing_date}" 
     data-hearing-time="${notification.hearing_time}" 
     data-hearing-status="${notification.hearing_status}">
    Status: ${notification.status}<br>
    Hearing Type: ${notification.hearing_type}<br>
    Date: ${notification.hearing_date}<br>
    Time: ${notification.hearing_time}<br>
    Hearing Status: ${notification.hearing_status}
     <hr>
</div>

                        `;
                    });
                } else {
                    // If no new notifications
                    notificationListHtml = '<div class="dropdown-item text-center">No new notifications</div>';
                }

                // Update the popover content
                const popoverInstance = bootstrap.Popover.getInstance(notificationButton);
                if (popoverInstance) {
                    popoverInstance.setContent({
                        '.popover-body': notificationListHtml
                    });
                } else {
                    // Initialize the popover
                    new bootstrap.Popover(notificationButton, {
                        html: true,
                        content: function () {
                            return `<div class="popover-content">${notificationListHtml}</div>`;
                        },
                        container: 'body'
                    });
                }

                // Attach click event listeners to notifications
                document.querySelectorAll('.popover-content .dropdown-item').forEach(item => {
                    item.addEventListener('click', function () {
                        openNotificationDetail(this);
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

    // Function to open notification detail in a modal
    

    // Initialize or refresh the popover when needed
    fetchNotifications();

    // Mark notifications as read when the popover is shown
    notificationButton.addEventListener('shown.bs.popover', function () {
        markNotificationsAsRead();
    });

    function markNotificationsAsRead() {
        // Make an AJAX request to the server to mark notifications as read
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
                // Handle the response, e.g., update the UI to reflect read notifications
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
