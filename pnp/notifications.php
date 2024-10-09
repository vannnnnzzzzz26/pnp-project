<?php
include '../connection/dbconn.php'; 

header('Content-Type: application/json');

ini_set('display_errors', 0);  
ini_set('log_errors', 1);  
error_reporting(E_ALL);

// Handle updating complaint status and marking notification as read
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'update') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['notificationId'])) {
        $notificationId = $input['notificationId'];
        $userId = $input['userId']; // Assuming you pass the user ID

        try {
            // Update the complaint status
            $stmt = $pdo->prepare("UPDATE tbl_complaints SET status = 'filed in court' WHERE complaints_id = :id");
            $stmt->execute(['id' => $notificationId]);

            // Mark the notification as read in tbl_users
            $stmt = $pdo->prepare("UPDATE tbl_users SET read_status = 'read' WHERE user_id = :userId AND complaints_id = :notificationId");
            $stmt->execute(['userId' => $userId, 'notificationId' => $notificationId]);

            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Error updating status: ' . $e->getMessage()]);
        }
        exit;
    }
}

// Fetch notifications
try {
    $stmt = $pdo->prepare("
        SELECT c.complaints_id, c.complaint_name, c.status, b.barangay_name, u.read_status
        FROM tbl_complaints c
        LEFT JOIN tbl_users_barangay b ON c.barangays_id = b.barangays_id
        LEFT JOIN tbl_users u ON u.user_id = c.user_id
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

exit;
?>
