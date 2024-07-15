


<?php
session_start();
require 'dbconn.php'; 
// Replace with your database connection script
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $position = $_POST['position'];


    
    // File upload handling
    $target_dir = "uploads/"; // Directory where uploaded images will be stored
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $_SESSION['error'] = "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (adjust as necessary)
    if ($_FILES["image"]["size"] > 500000) {
        $_SESSION['error'] = "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats (adjust as necessary)
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
       && $imageFileType != "gif" ) {
        $_SESSION['error'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $_SESSION['error'] = "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Image uploaded successfully, now insert data into database
            $image_path = $target_file;

            // Insert into database
            $stmt = $pdo->prepare("INSERT INTO tbl_brg_official (name, position, image) VALUES (?, ?, ?)");
            $stmt->execute([$name, $position, $image_path]);

            $_SESSION['success'] = "Official added successfully.";
        } else {
            $_SESSION['error'] = "Sorry, there was an error uploading your file.";
        }
    }

    // Redirect back to add_official.php after processing
    header("Location: barangay-official.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Barangay Officials</title>
    <!-- Bootstrap CSS link -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom styles for card layout -->
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

        <!-- Add Official Form -->


        
         
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


        <div class="content">
        <div class="row">
            <div class="col-md-4">
            <h2 class="mb-4">Barangay Officials</h2>
                <h4>Add New Official</h4>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="position">Position:</label>
                        <input type="text" id="position" name="position" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="image">Image:</label>
                        <input type="file" id="image" name="image" class="form-control-file" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Official</button>
                </form>
            </div>

            <!-- Display Officials in Cards -->
            <div class="col-md-8">
                <div class="row">
                    <?php 
                       require 'dbconn.php'; // Replace with your database connection script

                       $stmt = $pdo->prepare("SELECT * FROM tbl_brg_official");
                       $stmt->execute();
                       $officials = $stmt->fetchAll(PDO::FETCH_ASSOC);
           
                    
                    
                    if (!empty($officials)): ?>
                        <?php foreach ($officials as $official): ?>
                            <div class="col-md-4 mb-4">
                                <div class="card">
                                    <img src="<?php echo $official['image']; ?>" class="card-img-top" alt="Official Image">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $official['name']; ?></h5>
                                        <p class="card-text"><?php echo $official['position']; ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-md-12">
                            <div class="alert alert-warning">No officials found.</div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
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
                document.getElementById('logoutForm').submit();
            }
        });
    }
    </script>
 <script src="script.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-EaG2D0j3+6vloEIDyLxVXSrV4pge4F1xf0rSPG9nVf+BvxR/6pX0rrOV+2vO1X4K" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8sh+Emn2O+uBSfRvK+4UxFY2n0P0yJWAlpWfgI" crossorigin="anonymous"></script>
</body>
</html>
