<?php
// view-officials.php
session_start();

$firstName = $_SESSION['first_name'];
$middleName = $_SESSION['middle_name'];
$lastName = $_SESSION['last_name'];
$extensionName = $_SESSION['extension_name'] ?? '';
$email = $_SESSION['email'] ?? '';
$barangay_name = $_SESSION['barangay_name'] ?? '';
$pic_data = $_SESSION['pic_data'] ?? '';
// Include database connection file
include '../connection/dbconn.php';

// Fetch officials data from the database

// Fetch barangay name if not already set in session






try {
    $stmt = $pdo->prepare("SELECT * FROM tbl_brg_official");
    $stmt->execute();
    $officials = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching officials: " . htmlspecialchars($e->getMessage());
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Officials</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" type="text/css" href="../styles/style.css">
</head>
<body>


<?php 

include '../includes/resident-nav.php';
include '../includes/resident-bar.php';
?>

<div class="content">
    <div class="container mt-5">
        <h4>Officials List</h4>
        <div class="row">
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
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <?php if (!empty($official['image'])): ?>
                            <img src="<?php echo htmlspecialchars($official['image']); ?>" class="card-img-top" alt="Official Image">
                        <?php else: ?>
                            <img src="default-image.jpg" class="card-img-top" alt="Default Image">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($official['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($official['position']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Display kagawads -->
            <?php foreach (array_slice($kagawads, 0, 7) as $official): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <?php if (!empty($official['image'])): ?>
                            <img src="<?php echo htmlspecialchars($official['image']); ?>" class="card-img-top" alt="Official Image">
                        <?php else: ?>
                            <img src="default-image.jpg" class="card-img-top" alt="Default Image">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($official['name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($official['position']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="../scripts/script.js"></script>
  
    <!-- Include jQuery and Bootstrap JavaScript -->


 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


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
