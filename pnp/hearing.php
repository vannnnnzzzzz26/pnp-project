<?php

include '../connection/dbconn.php';

header('Content-Type: application/json');

if (!isset($_GET['complaint_id'])) {
    echo json_encode(['error' => 'Complaint ID not provided']);
    exit();
}

$complaintId = intval($_GET['complaint_id']);

try {
    // Fetch hearing history
    $stmt = $pdo->prepare("
    SELECT hearing_date, hearing_time, hearing_type, hearing_status
    FROM tbl_hearing_history
    WHERE complaints_id = ?
    ");
    $stmt->execute([$complaintId]);
    $hearings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($hearings);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

?>
