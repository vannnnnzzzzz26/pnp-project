<?php
include '../connection/dbconn.php'; 

header('Content-Type: application/json');

if (isset($_POST['complaints_id'])) {
    $complaints_id = $_POST['complaints_id'];

    try {
        $stmt = $pdo->prepare("
            UPDATE tbl_complaints 
            SET read_status = 1
            WHERE complaints_id = :complaints_id
        ");
        $stmt->bindParam(':complaints_id', $complaints_id, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode([
            'success' => true,
            'message' => 'Notification marked as read.'
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'error' => 'Error updating notification: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'error' => 'No complaint ID provided.'
    ]);
}
?>
