<?php 
require 'dbconn.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $announcement_id = $data['announcement_id'];

    // Update share count in the database
    $sql = "UPDATE tbl_announcement SET share_count = share_count + 1 WHERE announcement_id = :announcement_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':announcement_id', $announcement_id, PDO::PARAM_INT);
    $stmt->execute();

    // Get the updated share count
    $sql = "SELECT share_count FROM tbl_announcement WHERE announcement_id = :announcement_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':announcement_id', $announcement_id, PDO::PARAM_INT);
    $stmt->execute();
    $share_count = $stmt->fetchColumn();

    echo json_encode(['success' => true, 'share_count' => $share_count]);
} else {
    echo json_encode(['success' => false]);
}


?>