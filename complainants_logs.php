<?php
session_start();
include 'dbconn.php'; // Include your database connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize variables from session data
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$firstName = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : '';
$middleName = isset($_SESSION['middle_name']) ? $_SESSION['middle_name'] : '';
$lastName = isset($_SESSION['last_name']) ? $_SESSION['last_name'] : '';
$extensionName = isset($_SESSION['extension_name']) ? $_SESSION['extension_name'] : '';

// Construct user's full name
$userFullName = $firstName . ' ' . $middleName . ' ' . $lastName;
if (!empty($extensionName)) {
    $userFullName .= ' ' . $extensionName;
}

try {
    // Fetch complaints for the logged-in user
    $stmt = $pdo->prepare("
        SELECT c.*, cc.complaints_category, b.barangay_name,
               CASE
                   WHEN c.status = 'settled_pnp' THEN 'Settled in PNP'
                   WHEN c.status = 'pending_pnp' THEN 'Pending in PNP'
                   WHEN c.status = 'settled_barangay' THEN 'Settled in Barangay'
                   ELSE c.status
               END AS status_text
        FROM tbl_complaints c
        LEFT JOIN tbl_complaintcategories cc ON c.category_id = cc.category_id
        LEFT JOIN tbl_users_barangay b ON c.barangays_id = b.barangays_id
        WHERE c.complaint_name = ?
    ");
    $stmt->execute([$userFullName]);
    $complaints = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='alert alert-danger' role='alert'>Error: " . $e->getMessage() . "</div>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaints Status</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<style>
    table, th, td {
    border: none;
}
table {
    border-collapse: collapse;
    width: 100%;
}

</style>

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

<div  style="margin-top: 3rem;" class="sidebar bg-dark" id="sidebar">
    <!-- Toggle button inside sidebar -->
    <button class="sidebar-toggler" type="button" onclick="toggleSidebar()">
        <i class="bi bi-grid-fill large-icon"></i><span class="nav-text menu-icon-text">Menu</span>
    </button>

    <!-- User Information -->
    <div class="user-info px-3 py-2 text-center">
        <!-- Your PHP session-based content -->
        <?php
        if (isset($_SESSION['pic_data'])) {
            $pic_data = $_SESSION['pic_data'];
            echo "<img class='profile' src='$pic_data' alt='Profile Picture'>";
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
            <a class="nav-link active" href="index.php"><i class="bi bi-house-door-fill"></i><span class="nav-text">Complaints</span></a>
        </li>
        <li class="nav-item menu-item">
            <a class="nav-link" href="complainants_logs.php"><i class="bi bi-journal-text"></i><span class="nav-text">Complaints Logs</span></a>
        </li>
        <li class="nav-item menu-item">
            <a class="nav-link" href=""><i class="bi bi-person-check-fill"></i><span class="nav-text">Complaints Responder</span></a>
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
<!-- Page Content -->
<div class="content" id="content">
    <div class="container mt-4">
        <h1 class="text-center">Complaints Status</h1>
        <div class="row">
            <div class="col-md-9">
                <?php if (empty($complaints)): ?>
                    <div class="alert alert-info text-center" role="alert">
                        You haven't submitted any complaints yet.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">Date Filed</th>
                                    <th scope="col">Complaint Name</th>
                                    
                                    <th scope="col">Complaint Description</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Barangay</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Complaints Person</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($complaints as $complaint): ?>
                                    <tr>
                                        <td><?php echo $complaint['date_filed']; ?></td>
                                        <td><?php echo $complaint['complaint_name']; ?></td>
                                        <td><?php echo $complaint['complaints']; ?></td>
                                        <td><?php echo $complaint['complaints_category']; ?></td>
                                        <td><?php echo $complaint['barangay_name']; ?></td>
                                        <td><?php echo $complaint['status_text']; ?></td>
                                        <td><?php echo $complaint['complaints_person']; ?></td>
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
    <script src="script.js"></script>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.all.min.js"></script>
</body>
</html>

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
            window.location.href = "login.php?logout=<?php echo $_SESSION['user_id']; ?>";
        }
    });
}
</script>
