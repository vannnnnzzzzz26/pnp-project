<?php
// Start the session
session_start();

// Include the database connection file
include '../connection/dbconn.php';

// Initialize PDO if not already done
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_POST['complaint_id'])) {
    $complaint_id = $_POST['complaint_id'];

    try {
        // Fetch hearing history
        $stmt = $pdo->prepare("
            SELECT hearing_type, hearing_date, hearing_time, hearing_status
            FROM tbl_hearing_history
            WHERE complaints_id = ?
            ORDER BY hearing_date ASC, hearing_time ASC
        ");
        $stmt->execute([$complaint_id]);
        $hearingHistory = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($hearingHistory) {
            echo json_encode($hearingHistory);
        } else {
            echo json_encode([]);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
