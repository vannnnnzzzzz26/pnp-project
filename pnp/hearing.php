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

    // Debugging output
    if (empty($hearings)) {
        echo json_encode(['error' => 'No hearings found for the given complaint ID']);
    } else {
        echo json_encode($hearings);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
