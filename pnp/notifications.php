<?php
include '../connection/dbconn.php'; 

header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare("
        SELECT c.complaints_id, c.complaint_name, c.status, 
               b.barangay_name
        FROM tbl_complaints c
        LEFT JOIN tbl_users_barangay b ON c.barangays_id = b.barangays_id
        WHERE c.status = 'pnp'
        ORDER BY c.date_filed ASC
    ");
    $stmt->execute();

    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'notifications' => $notifications
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Error fetching notifications: ' . $e->getMessage()
    ]);
}
?>
