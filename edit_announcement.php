<?php
require 'dbconn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $announcement_id = $_POST['announcement_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    
    // Retrieve the existing image path from the form
    $image_path = $_POST['existing_image_path'];

    // Check if a new image is uploaded
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file is an actual image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = $target_file; // Update image path only if upload is successful
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "File is not an image.";
        }
    }

    $sql = "UPDATE tbl_announcement SET title = ?, content = ?, image_path = ? WHERE announcement_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$title, $content, $image_path, $announcement_id]);

    echo '<script>
        alert("Announcement updated successfully!");
        window.location.href = "pnp-announcement.php";
    </script>';
}
?>
