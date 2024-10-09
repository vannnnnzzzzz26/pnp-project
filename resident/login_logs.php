<?php
include '../connection/dbconn.php';
session_start();

$cp_number = isset($_SESSION['cp_number']) ? $_SESSION['cp_number'] : '';
$firstName = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : '';
$middleName = isset($_SESSION['middle_name']) ? $_SESSION['middle_name'] : '';
$lastName = isset($_SESSION['last_name']) ? $_SESSION['last_name'] : '';
$extensionName = isset($_SESSION['extension_name']) ? $_SESSION['extension_name'] : '';

// Check if the user is logged in and has the correct permissions (optional)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the logged-in user's ID from the session
$user_id = $_SESSION['user_id'];

// Define the number of logs per page
$logs_per_page = 10;

// Get the current page from the URL, if not set default to page 1
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

// Calculate the starting row for the query
$offset = ($page - 1) * $logs_per_page;

// Fetch the total number of login logs for pagination calculation
$total_stmt = $pdo->prepare("SELECT COUNT(*) FROM tbl_login_logs WHERE user_id = :user_id");
$total_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$total_stmt->execute();
$total_logs = $total_stmt->fetchColumn();

// Fetch the login logs for the current page
$stmt = $pdo->prepare("SELECT tbl_login_logs.*, tbl_users.cp_number 
                       FROM tbl_login_logs
                       JOIN tbl_users ON tbl_login_logs.user_id = tbl_users.user_id
                       WHERE tbl_login_logs.user_id = :user_id
                       ORDER BY tbl_login_logs.login_time DESC
                       LIMIT :limit OFFSET :offset");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindValue(':limit', $logs_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate the total number of pages
$total_pages = ceil($total_logs / $logs_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Login Logs</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">    <link rel="stylesheet" href="../styles/style.css"> <!-- Add your custom CSS file here if needed -->
</head>
<body>


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
    
    <?php 
include '../includes/resident-nav.php';
include '../includes/resident-bar.php';
?>


   <center><div class="content">
    <div class="container mt-5">
        <h1 class="text-center mb-4"> Login Logs</h1>
        <div class="table">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center">Login Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <!-- Format login time to 12-hour format with AM/PM -->
                            <td class="text-center"><?= htmlspecialchars(date('F j, Y, g:i A', strtotime($log['login_time']))) ?></td>
                </tr>
          <?php endforeach; ?>
                </tbody>
            </table>
        </div>


        <nav>
            <ul class="pagination justify-content-center">
                <!-- Previous button -->
                <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>

                <!-- Page numbers -->
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <!-- Next button -->
                <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div></center>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="../scripts/script.js"></script>




<script>
$(document).ready(function() {
    $.ajax({
        url: "your-ajax-handler.php",  // Replace with your server-side PHP script
        type: "GET",  // Use "GET" or "POST" depending on what your PHP script expects
        success: function(response) {
            // Process the response and update the page content
            // Example: Updating a div with the response
            $("#content-area").html(response);
        },
        error: function(xhr, status, error) {
            // Handle errors
            console.log("Error: " + error);
        }
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
            // Correct URL for logout
            window.location.href = "../reg/logout.php";
        }
    });
}
</script>

</body>
</html>
