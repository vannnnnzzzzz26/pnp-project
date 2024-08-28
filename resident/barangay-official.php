<?php
session_start();
include '../connection/dbconn.php';

// Check if user is logged in and set barangay information in session
$firstName = $_SESSION['first_name'] ?? '';
$middleName = $_SESSION['middle_name'] ?? '';
$lastName = $_SESSION['last_name'] ?? '';
$extensionName = $_SESSION['extension_name'] ?? '';
$email = $_SESSION['email'] ?? '';
$barangay_name = $_SESSION['barangay_name'] ?? '';
$pic_data = $_SESSION['pic_data'] ?? '';

// Ensure barangay_name is set in the session
if (!$barangay_name) {
    // Redirect to login page or handle unauthorized access
    header("Location: login.php");
    exit();
}


$stmt = $pdo->prepare("SELECT barangays_id FROM tbl_users_barangay WHERE barangay_name = ?");
$stmt->execute([$barangay_name]);
$barangay = $stmt->fetch();

if ($barangay) {
    $barangays_id = $barangay['barangays_id'];
} else {
    $_SESSION['error'] = "Barangay not found.";
    header("Location: login.php");
    exit();
}

// Fetch barangays_id based on barangay_name
$stmt = $pdo->prepare("SELECT barangays_id FROM tbl_users_barangay WHERE barangay_name = ?");
$stmt->execute([$barangay_name]);
$barangay = $stmt->fetch();

if ($barangay) {
    $barangays_id = $barangay['barangays_id'];
} else {
    $_SESSION['error'] = "Barangay not found.";
    header("Location: login.php");
    exit();
}

// Fetch officials only from the logged-in barangay (excluding deleted officials)
$stmt = $pdo->prepare("SELECT * FROM tbl_brg_official WHERE barangays_id = ? AND is_deleted = 0");
$stmt->execute([$barangays_id]);

$officials = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Barangay Officials</title>
    <!-- Bootstrap CSS link -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../styles/style.css">
</head>
<body>

<?php 

include '../includes/resident-nav.php';
include '../includes/resident-bar.php';
?>


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

        <!-- Officials Table -->
        <div class="row">
            <div class="col-md-12">
                <h2 class="mb-4">Barangay Officials</h2>
                <label for="barangay">Barangay:</label>
                        <?php 
                            include '../connection/dbconn.php'; 
                            try {
                                $stmt = $pdo->prepare("SELECT barangay_name FROM tbl_users_barangay WHERE barangays_id = ?");
                                $stmt->execute([$barangay]);
                                $barangay = $stmt->fetchColumn();
                                if ($barangay) {
                                    echo "<p>" . htmlspecialchars($barangay) . "</p>";
                                } else {
                                    echo "<p>No barangay found.</p>";
                                }
                            } catch (PDOException $e) {
                                echo "Error fetching barangay name: " . htmlspecialchars($e->getMessage());
                            }
                        ?>
                <!-- Officials List -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Position</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Separate barangay captains and kagawads
                        $captains = array_filter($officials, function($official) {
                            return $official['position'] === 'Barangay Captain';
                        });
                        $kagawads = array_filter($officials, function($official) {
                            return strpos($official['position'], 'Kagawad') === 0;
                        });

                        // Sort kagawads based on their position number
                        usort($kagawads, function($a, $b) {
                            $a_num = (int) str_replace('Kagawad ', '', $a['position']);
                            $b_num = (int) str_replace('Kagawad ', '', $b['position']);
                            return $a_num - $b_num;
                        });

                        // Display barangay captains
                        foreach ($captains as $official): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($official['image'])): ?>
                                        <img src="<?php echo htmlspecialchars($official['image']); ?>" class="img-thumbnail" width="100" alt="Official Image">
                                    <?php else: ?>
                                        <img src="default-image.jpg" class="img-thumbnail" width="100" alt="Default Image">
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($official['name']); ?></td>
                                <td><?php echo htmlspecialchars($official['position']); ?></td>
                            </tr>
                        <?php endforeach; ?>

                        <!-- Display kagawads -->
                        <?php foreach (array_slice($kagawads, 0, 7) as $official): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($official['image'])): ?>
                                        <img src="<?php echo htmlspecialchars($official['image']); ?>" class="img-thumbnail" width="100" alt="Official Image">
                                    <?php else: ?>
                                        <img src="default-image.jpg" class="img-thumbnail" width="100" alt="Default Image">
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($official['name']); ?></td>
                                <td><?php echo htmlspecialchars($official['position']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../barangay/edit-profile.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
<script src="../scripts/script.js"></script>

</body>
</html>
