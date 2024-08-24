<?php
  include '../connection/dbconn.php'; 

if (isset($_GET['id'])) {
    $announcement_id = $_GET['id'];
    $sql = "UPDATE tbl_announcement SET deleted = 1 WHERE announcement_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$announcement_id]);

    echo '<script>
        alert("Announcement deleted successfully!");
        window.location.href = "pnp-announcement.php";
    </script>';
}
?>