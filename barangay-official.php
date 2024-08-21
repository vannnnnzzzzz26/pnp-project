<?php
session_start();
require 'dbconn.php';

// Check if user is logged in and set barangay information in session
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

// Handle form submission for adding new official
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    if ($_POST['action'] == 'add_official') {
        $name = $_POST['name'];
        $position = $_POST['position'];

        // File upload handling
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is an actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            $_SESSION['error'] = "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size (adjust as necessary)
        if ($_FILES["image"]["size"] > 500000) {
            $_SESSION['error'] = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats (adjust as necessary)
        $allowed_formats = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowed_formats)) {
            $_SESSION['error'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $_SESSION['error'] = "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Image uploaded successfully, now insert data into database
                $image_path = $target_file;

                // Insert into database with barangays_id as foreign key
                $stmt = $pdo->prepare("INSERT INTO tbl_brg_official (name, position, image, barangays_id) VALUES (?, ?, ?, ?)");
                if ($stmt->execute([$name, $position, $image_path, $barangays_id])) {
                    $_SESSION['success'] = "Official added successfully.";
                } else {
                    $_SESSION['error'] = "Failed to add official.";
                }
            } else {
                $_SESSION['error'] = "Sorry, there was an error uploading your file.";
            }
        }
    } elseif ($_POST['action'] == 'edit_official') {
        $official_id = $_POST['official_id'];
        $edit_name = $_POST['edit_name'];
        $edit_position = $_POST['edit_position'];
        $image_path = $_POST['existing_image_path'];

        // Check if a new image is uploaded
        if (!empty($_FILES['edit_image']['name'])) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["edit_image"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if file is an actual image
            $check = getimagesize($_FILES["edit_image"]["tmp_name"]);
            if($check !== false) {
                if (move_uploaded_file($_FILES["edit_image"]["tmp_name"], $target_file)) {
                    $image_path = $target_file; // Update image path only if upload is successful
                } else {
                    $_SESSION['error'] = "Sorry, there was an error uploading your file.";
                }
            } else {
                $_SESSION['error'] = "File is not an image.";
            }
        }

        // Update official in database
        $stmt = $pdo->prepare("UPDATE tbl_brg_official SET name = ?, position = ?, image = ? WHERE official_id = ? AND barangays_id = ?");
        if ($stmt->execute([$edit_name, $edit_position, $image_path, $official_id, $barangays_id])) {
            $_SESSION['success'] = "Official updated successfully.";
        } else {
            $_SESSION['error'] = "Failed to update official.";
        }
    }

    // Redirect back to barangay-official.php after processing
    header("Location: barangay-official.php");
    exit();
}

// Soft delete action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['official_id'])) {
    $official_id = $_GET['official_id'];

    // Ensure the official belongs to the logged-in barangay
    $stmt = $pdo->prepare("UPDATE tbl_brg_official SET is_deleted = 1 WHERE official_id = ? AND barangays_id = ?");
    if ($stmt->execute([$official_id, $barangays_id])) {
        $_SESSION['success'] = "Official deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete official.";
    }

    // Redirect back to barangay-official.php after deletion
    header("Location: barangay-official.php");
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
        <div class="row">
            <div class="col-md-4">
                <h2 class="mb-4">Barangay Officials</h2>
                <h4>Add New Official</h4>
                <form action="barangay-official.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="add_official">
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
                    <button type="submit" class="btn btn-primary mt-3">Add Official</button>
                </form>
            </div>

            <!-- Officials List -->
            <div class="col-md-8">
                <h4>Officials List</h4>
                <div class="row">
                    <?php foreach ($officials as $official): ?>
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
                                    <a href="#" class="btn btn-primary btn-sm edit-official-btn" data-bs-toggle="modal" data-bs-target="#editOfficialModal"
                                        data-official-id="<?php echo $official['official_id']; ?>"
                                        data-name="<?php echo htmlspecialchars($official['name']); ?>"
                                        data-position="<?php echo htmlspecialchars($official['position']); ?>"
                                        data-image="<?php echo htmlspecialchars($official['image']); ?>">
                                        Edit
                                    </a>
                                    <a href="barangay-official.php?action=delete&official_id=<?php echo $official['official_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Edit Official Modal -->
        <div class="modal fade" id="editOfficialModal" tabindex="-1" aria-labelledby="editOfficialModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editOfficialModalLabel">Edit Official</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editOfficialForm" action="barangay-official.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="edit_official">
                            <input type="hidden" name="official_id" id="edit_official_id">
                            <input type="hidden" name="existing_image_path" id="existing_image_path">
                            <div class="form-group">
                                <label for="edit_name">Name:</label>
                                <input type="text" id="edit_name" name="edit_name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_position">Position:</label>
                                <input type="text" id="edit_position" name="edit_position" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_image">Image:</label>
                                <input type="file" id="edit_image" name="edit_image" class="form-control-file" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label>Existing Image:</label>
                                <div id="currentImageContainer">
                                    <img id="currentImage" src="" alt="Current Image" class="img-thumbnail" width="100">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Update Official</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


    

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
                document.getElementById('logoutForm').submit();
            }
        });
    }
    </script>
 <script src="script.js"></script>



<!-- Bootstrap JavaScript link -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
<script>
    // JavaScript to handle modal data population for editing
    document.addEventListener('DOMContentLoaded', function() {
        var editButtons = document.querySelectorAll('.edit-official-btn');
        editButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var officialId = this.getAttribute('data-official-id');
                var name = this.getAttribute('data-name');
                var position = this.getAttribute('data-position');
                var image = this.getAttribute('data-image');

                document.getElementById('edit_official_id').value = officialId;
                document.getElementById('edit_name').value = name;
                document.getElementById('edit_position').value = position;
                document.getElementById('existing_image_path').value = image;

                var currentImage = document.getElementById('currentImage');
                currentImage.src = image ? image : 'default-image.jpg'; // Use a default image if none is available
            });
        });
    });
</script>