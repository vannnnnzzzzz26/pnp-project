<?php
session_start();
require 'dbconn.php';

// Ensure user is logged in and get barangay information from session
if (!isset($_SESSION['barangay_name']) && isset($_SESSION['barangays_id'])) {
    $stmt = $pdo->prepare("SELECT barangay_name FROM tbl_users_barangay WHERE barangays_id = ?");
    $stmt->execute([$_SESSION['barangays_id']]);
    $_SESSION['barangay_name'] = $stmt->fetchColumn();
}

// Ensure necessary session variables are set
$firstName = $_SESSION['first_name'] ?? '';
$middleName = $_SESSION['middle_name'] ?? '';
$lastName = $_SESSION['last_name'] ?? '';
$extensionName = $_SESSION['extension_name'] ?? '';
$email = $_SESSION['email'] ?? '';
$barangay_name = $_SESSION['barangay_name'] ?? '';
$pic_data = $_SESSION['pic_data'] ?? '';

// Ensure barangays_id is set in the session
$barangays_id = $_SESSION['barangays_id'] ?? '';

// Check if barangays_id is not set or user is not logged in
if (!$barangays_id) {
    // Redirect to login page or handle unauthorized access
    header("Location: login.php");
    exit();
}

// Check if official_id is provided via GET
if (!isset($_GET['official_id'])) {
    $_SESSION['error'] = "Official ID not provided.";
    header("Location: barangay-official.php");
    exit();
}

$official_id = $_GET['official_id'];

// Fetch the official's details
$stmt = $pdo->prepare("SELECT * FROM tbl_brg_official WHERE official_id = ?");
$stmt->execute([$official_id]);
$official = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission for updating official
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $position = $_POST['position'];

    // File upload handling (if you want to allow updating image)
    // This part can be similar to your add official form handling

    // Update official details in database
    $stmt = $pdo->prepare("UPDATE tbl_brg_official SET name = ?, position = ? WHERE official_id = ? AND barangays_id = ?");
    if ($stmt->execute([$name, $position, $official_id, $barangays_id])) {
        $_SESSION['success'] = "Official updated successfully.";
        header("Location: barangay-official.php");
        exit();
    } else {
        $_SESSION['error'] = "Failed to update official.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Barangay Official</title>
    <!-- Bootstrap CSS link -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">Excel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Add Navbar items if needed -->
            </div>
        </div>
    </nav>
    <div class="content">
        <div class="container mt-4">
            <!-- Display session messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php echo $_SESSION['success']; ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php echo $_SESSION['error']; ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <!-- Edit Official Form -->
            <div class="row">
                <div class="col-md-6">
                    <h2>Edit Barangay Official</h2>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($official['name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="position">Position:</label>
                            <input type="text" id="position" name="position" class="form-control" value="<?php echo htmlspecialchars($official['position']); ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Official</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div style="margin-top: 3rem;" class="sidebar bg-dark" id="sidebar">
        <!-- Sidebar content -->
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-eMNCOe7tC1doHpGoWe/6oMVemdAVTMs2xqW4mwXrXsW0L84Iytr2wi5v2QjrP/xp" crossorigin="anonymous"></script>
</body>
</html>
