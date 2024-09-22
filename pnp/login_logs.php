<?php
include '../connection/dbconn.php';
session_start();

$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
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
$stmt = $pdo->prepare("SELECT tbl_login_logs.*, tbl_users.email 
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
include '../includes/pnp-bar.php';
include '../includes/pnp-nav.php';

?>

   <div class="content">
    <div class="container mt-5">
        <h1 class="text-center mb-4">My Login Logs</h1>
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
</div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../scripts/script.js"></script>

</body>
</html>
