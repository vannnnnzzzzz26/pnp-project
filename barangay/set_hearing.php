<?php
// Start the session
session_start();

include '../connection/dbconn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $complaint_id = $_POST['complaint_id'];
    $hearing_date = $_POST['hearing_date'];
    $hearing_time = $_POST['hearing_time'];
    $hearing_type = $_POST['hearing_type']; // 'First Hearing', 'Second Hearing', 'Third Hearing'

    try {
        // Update the complaint with the new hearing details
        $stmt = $pdo->prepare("
            UPDATE tbl_complaints
            SET hearing_date = ?, hearing_time = ?, hearing_type = ?
            WHERE complaints_id = ?
        ");
        $stmt->execute([$hearing_date, $hearing_time, $hearing_type, $complaint_id]);

        echo "Hearing updated successfully.";
    } catch (PDOException $e) {
        echo "Error updating hearing: " . $e->getMessage();
    }
}
?>
