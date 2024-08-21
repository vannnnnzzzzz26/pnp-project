<?php
// Start the session
session_start();

// Include your database connection file
include_once 'dbconn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $complaint_id = $_POST['complaint_id'];
    $hearing_date = $_POST['hearing_date'];
    $hearing_time = $_POST['hearing_time'];

    try {
        // Prepare and execute the update statement
        $stmt = $pdo->prepare("
            UPDATE tbl_complaints
            SET hearing_date = ?, hearing_time = ?
            WHERE complaints_id = ? AND status = 'Approved'
        ");
        $stmt->execute([$hearing_date, $hearing_time, $complaint_id]);

        echo "Hearing date and time set successfully.";
    } catch (PDOException $e) {
        echo "Error setting hearing date: " . $e->getMessage();
    }
}
?>
