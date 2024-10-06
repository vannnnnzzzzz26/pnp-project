<?php
session_start();
include '../connection/dbconn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the logged-in user's full name
    $userFullName = $_SESSION['first_name'] . ' ' . $_SESSION['middle_name'] . ' ' . $_SESSION['last_name'];

    // Fetch unread notifications for the logged-in user
    try {
        $stmt = $pdo->prepare("
            SELECT c.complaints_id, c.status, h.hearing_type, h.hearing_date, h.hearing_time, h.hearing_status
            FROM tbl_complaints c
            LEFT JOIN tbl_hearing_history h ON c.complaints_id = h.complaints_id
            WHERE c.complaint_name = ?
            AND (
                c.status IN ('Pending', 'Approved', 'Rejected', 'settled_in_barangay', 'pnp', 'barangay', 'Filed in Court') 
                OR h.hearing_type IS NOT NULL 
                OR h.hearing_date IS NOT NULL 
                OR h.hearing_time IS NOT NULL 
                OR h.hearing_status IS NOT NULL
            )
            AND c.status != 'Read'
        ");
        $stmt->execute([$userFullName]);
        $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Replace null values with 'Not Set'
        foreach ($notifications as &$notification) {
            $notification['hearing_type'] = $notification['hearing_type'] ?? 'Not Set';
            $notification['hearing_date'] = $notification['hearing_date'] ?? 'Not Set';
            $notification['hearing_time'] = $notification['hearing_time'] ?? 'Not Set';
            $notification['hearing_status'] = $notification['hearing_status'] ?? 'Not Set';
        }

        // If there are notifications, send them in the response
        if (!empty($notifications)) {
            echo json_encode(['success' => true, 'notifications' => $notifications]);
        } else {
            // No notifications found, do not send any response
            http_response_code(204); // 204 No Content
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => "Error: " . $e->getMessage()]);
    }
}

?>
