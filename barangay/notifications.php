<?php
session_start();
include '../connection/dbconn.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   

    // Get the logged-in user's barangay name
    $barangay_name = $_SESSION['barangay_name'];

    try {
        // Fetch unread notifications
        $stmt = $pdo->prepare("
            SELECT c.complaints_id, c.complaint_name, c.status, u.barangay_name
            FROM tbl_complaints c
            LEFT JOIN tbl_users_barangay u ON c.barangays_id = u.barangays_id
            WHERE u.barangay_name = ? AND c.status IN ('Inprogress')
        ");
        $stmt->execute([$barangay_name]);
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($notifications)) {
            echo json_encode(['success' => true, 'notifications' => $notifications]);
        } else {
            http_response_code(204); // 204 No Content
            echo json_encode(['success' => true, 'notifications' => []]);
        }
    } catch (PDOException $e) {
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => "Error: " . $e->getMessage()]);
    }
}
?>
