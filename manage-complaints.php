<?php
// Start session and include database connection
session_start();
require 'dbconn.php';

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['barangay_name']) && isset($_SESSION['barangays_id'])) {
    $stmt = $pdo->prepare("SELECT barangay_name FROM tbl_users_barangay WHERE barangays_id = ?");
    $stmt->execute([$_SESSION['barangays_id']]);
    $_SESSION['barangay_name'] = $stmt->fetchColumn();
}

$firstName = $_SESSION['first_name'];
$middleName = $_SESSION['middle_name'];
$lastName = $_SESSION['last_name'];
$extensionName = isset($_SESSION['extension_name']) ? $_SESSION['extension_name'] : '';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$barangay_name = isset($_SESSION['barangay_name']) ? $_SESSION['barangay_name'] : '';
$pic_data = isset($_SESSION['pic_data']) ? $_SESSION['pic_data'] : '';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['complaint_id']) && isset($_POST['action'])) {
    try {
        $complaint_id = $_POST['complaint_id'];
        $action = $_POST['action'];
        $status = ($action == 'approve') ? 'Approved' : 'Unresolved'; // Adjust status values as needed

        // Update complaint status
        $stmt = $pdo->prepare("UPDATE tbl_complaints SET status = ? WHERE complaints_id = ?");
        $stmt->execute([$status, $complaint_id]);

        // Set success message using session
        $_SESSION['success'] = "Complaint status updated successfully.";

        // Redirect to manage complaints page to prevent form resubmission (optional)
        header("Location: manage-complaints.php");
        exit();
    } catch (PDOException $e) {
        // Set error message using session
        $_SESSION['error'] = "Error updating complaint status: " . $e->getMessage();

        // Redirect to manage complaints page to prevent form resubmission (optional)
        header("Location: manage-complaints.php");
        exit();
    }
}

// Fetch complaints with status 'Approved' or 'Unresolved' from the user's barangay
// Fetch complaints excluding 'Approved' ones from the user's barangay
try {
    $stmt = $pdo->prepare("SELECT c.*, u.barangay_name, i.image_path
                           FROM tbl_complaints c 
                           LEFT JOIN tbl_users_barangay u ON c.barangays_id = u.barangays_id 
                           LEFT JOIN tbl_image i ON c.image_id = i.image_id
                           WHERE c.status = 'Unresolved' 
                           AND u.barangay_name = ?");
    $stmt->execute([$barangay_name]);
    $complaints = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error'] = "Error fetching complaints: " . $e->getMessage();
    $complaints = []; // Initialize complaints array if fetch fails
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Complaints</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> <!-- Adjust as per your CSS file -->
    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">Excel</a>
        <!-- Button to toggle sidebar visibility -->
        <button class="navbar-toggler" type="button" onclick="toggleSidebar()">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>
<div class="content">
<div class="container mt-5">
    <h1>Manage Complaints</h1>

    <!-- Display success or error messages using SweetAlert -->
    <script>
        // Function to show SweetAlert success message
        <?php if (isset($_SESSION['success'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '<?php echo $_SESSION['success']; ?>',
            showConfirmButton: false,
            timer: 1500
        });
        <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        // Function to show SweetAlert error message
        <?php if (isset($_SESSION['error'])): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '<?php echo $_SESSION['error']; ?>',
            showConfirmButton: false,
            timer: 1500
        });
        <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </script>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Complaint Name</th>
                <th>Complaints</th>
                <th>Date Filed</th>
                <th>Category</th>
                <th>Barangay</th>
                <th>Contact Number</th>
                <th>Complaints Person</th>
                <th>Status</th>
                <th>Image</th> <!-- New column for Image -->
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($complaints as $complaint): ?>
                <tr>
                    <td><?php echo htmlspecialchars($complaint['complaints_id']); ?></td>
                    <td><?php echo htmlspecialchars($complaint['complaint_name']); ?></td>
                    <td><?php echo htmlspecialchars($complaint['complaints']); ?></td>
                    <td><?php echo htmlspecialchars($complaint['date_filed']); ?></td>
                    <td><?php echo htmlspecialchars($complaint['category_id']); ?></td>
                    <td><?php echo htmlspecialchars($complaint['barangay_name']); ?></td>
                    <td><?php echo htmlspecialchars($complaint['cp_number']); ?></td>
                    <td><?php echo htmlspecialchars($complaint['complaints_person']); ?></td>
                    <td><?php echo htmlspecialchars($complaint['status']); ?></td>
                    <td>
                        <?php if (!empty($complaint['image_path'])): ?>
                            <img src="<?php echo htmlspecialchars($complaint['image_path']); ?>" alt="Complaint Image" class="img-fluid complaint-image" style="width: 100px; height: 100px; cursor: pointer; border-radius: 50px;">
                        <?php else: ?>
                            <span>No Image</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <form action="manage-complaints.php" method="post" style="display: inline-block;">
                            <input type="hidden" name="complaint_id" value="<?php echo htmlspecialchars($complaint['complaints_id']); ?>">
                            <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                        </form>
                        <form action="manage-complaints.php" method="post" style="display: inline-block;">
                            <input type="hidden" name="complaint_id" value="<?php echo htmlspecialchars($complaint['complaints_id']); ?>">
                            <button type="submit" name="action" value="reject" class="btn btn-warning btn-sm">Reject</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Complaint Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="modalImage" src="" alt="Complaint Image" class="img-fluid">
            </div>
        </div>
    </div>
</div>

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
        <h5 class="white-text"><?php echo "$firstName $middleName $lastName $extensionName"; ?></h5>
        <p class="user-email white-text"><?php echo "$email"; ?></p>
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
               <!-- Logout Form -->
        <form action="logout.php" method="post" id="logoutForm">
            <div class="logout-btn">
                <button type="button" class="btn btn-danger btn-sm" onclick="confirmLogout()">
                    <i class="bi bi-box-arrow-left"></i><span class="nav-text">Logout</span>
                </button>
            </div>
        </form>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // JavaScript to handle image click and show modal
    document.addEventListener('DOMContentLoaded', (event) => {
        const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
        document.querySelectorAll('.complaint-image').forEach(img => {
            img.addEventListener('click', () => {
                const src = img.getAttribute('src');
                document.getElementById('modalImage').setAttribute('src', src);
                imageModal.show();
            });
        });
    });


    function confirmLogout() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, logout!',
            cancelButtonText: 'No, stay logged in'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logoutForm').submit();
            }
        });
    }
</script>
</body>
</html>
