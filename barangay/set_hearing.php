<?php
// Start the session
session_start();

include '../connection/dbconn.php';

// Initialize PDO if not already done
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $complaint_id = $_POST['complaint_id'];
    $hearing_date = $_POST['hearing_date'];
    $hearing_time = $_POST['hearing_time']; // 24-hour format input
    $hearing_type = $_POST['hearing_type'];
    $hearing_status = $_POST['hearing_status']; // Ensure this field is included in the form

    // Convert 24-hour time to 12-hour format with AM/PM
    $formatted_hearing_time = date("h:i:s A", strtotime($hearing_time));

    try {
        // Update the complaint with the new hearing details
        $stmt = $pdo->prepare("
            UPDATE tbl_complaints
            SET hearing_date = ?, hearing_time = ?, hearing_type = ?, hearing_status = ?
            WHERE complaints_id = ?
        ");
        $stmt->execute([$hearing_date, $formatted_hearing_time, $hearing_type, $hearing_status, $complaint_id]);

        echo "Hearing updated successfully.";
    } catch (PDOException $e) {
        echo "Error updating hearing: " . $e->getMessage();
    }
}
?>
