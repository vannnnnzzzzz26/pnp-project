<?php
  include '../connection/dbconn.php'; 
session_start(); // Start session if not already started



$firstName = $_SESSION['first_name'] ?? '';
$middleName = $_SESSION['middle_name'] ?? '';
$lastName = $_SESSION['last_name'] ?? '';
$extensionName = $_SESSION['extension_name'] ?? '';
$email = $_SESSION['email'] ?? '';
$barangay_name = $_SESSION['barangay_name'] ?? '';
$pic_data = $_SESSION['pic_data'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'add') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $date_posted = date('Y-m-d');

    $image_path = '';
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file is an actual image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = $target_file;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "File is not an image.";
        }
    }

    $sql = "INSERT INTO tbl_announcement (title, content, date_posted, image_path) VALUES (?, ?, ?, ?)";
    $stmt= $pdo->prepare($sql);
    $stmt->execute([$title, $content, $date_posted, $image_path]);

    echo '<script>
        alert("Announcement added successfully!");
        window.location.href = "pnp-announcement.php";
    </script>';
}

// Fetch announcements from the database, excluding deleted ones
$sql = "SELECT announcement_id, title, content, date_posted, image_path FROM tbl_announcement WHERE deleted = 0 ORDER BY date_posted DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$announcements = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PNP Announcements</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../styles/style.css">
</head>
<body>
    <!-- Navbar -->
    <?php 

include '../includes/pnp-nav.php';
include '../includes/pnp-bar.php';
?>

  <div class="content"> 
    <div class="row">
        <div class="col-md-4">
            <h2>Add Announcement</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label for="title">Title</label>
                    <textarea class="form-control" id="title" name="title" rows="1" required></textarea>
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
                </div>
                <div class="form-group">
                    <label for="image">Image (optional)</label>
                    <input type="file" class="form-control-file" id="image" name="image">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        <div class="col-md-8">
            <div class="container mt-4">
                <h2>Announcements</h2>
                <div class="row">
                    <?php if ($announcements): ?>
                        <?php foreach ($announcements as $announcement): ?>
                            <div class="col-12 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($announcement['title']); ?></h5>
                                        <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($announcement['date_posted']); ?></h6>
                                        <p class="card-text"><?php echo nl2br(htmlspecialchars($announcement['content'])); ?></p>
                                        <?php if ($announcement['image_path']): ?>
                                            <div class="image-container mb-2">
                                                <img src="<?php echo htmlspecialchars($announcement['image_path']); ?>" class="img-fluid" alt="Announcement Image">
                                            </div>
                                        <?php endif; ?>
                                        <a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $announcement['announcement_id']; ?>">Edit</a>
                                        <a href="delete_announcement.php?id=<?php echo $announcement['announcement_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this announcement?')">Delete</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal<?php echo $announcement['announcement_id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $announcement['announcement_id']; ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editModalLabel<?php echo $announcement['announcement_id']; ?>">Edit Announcement</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="edit_announcement.php" method="POST" enctype="multipart/form-data">
                                                <input type="hidden" name="announcement_id" value="<?php echo $announcement['announcement_id']; ?>">
                                                <input type="hidden" name="existing_image_path" value="<?php echo htmlspecialchars($announcement['image_path']); ?>">
                                                <div class="form-group">
                                                    <label for="edit_title">Title</label>
                                                    <textarea class="form-control" id="edit_title" name="title" rows="1" required><?php echo htmlspecialchars($announcement['title']); ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="edit_content">Content</label>
                                                    <textarea class="form-control" id="edit_content" name="content" rows="5" required><?php echo htmlspecialchars($announcement['content']); ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="edit_image">Image (optional)</label>
                                                    <input type="file" class="form-control-file" id="edit_image" name="image">
                                                    <?php if ($announcement['image_path']): ?>
                                                        <div class="mt-2">
                                                            <img src="<?php echo htmlspecialchars($announcement['image_path']); ?>" class="img-fluid" alt="Current Image">
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No announcements found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div> 
 

       

    <script src="../scripts/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-eMNCOe7tC1doHpGoWe/6oMVemdAVTMs2xqW4mwXrXsW0L84Iytr2wi5v2QjrP/xp" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>
