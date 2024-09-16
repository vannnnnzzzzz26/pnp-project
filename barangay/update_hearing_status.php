<?php
// update_hearing_status.php

session_start();

include '../connection/dbconn.php';

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $complaint_id = $_POST['complaint_id'];
    $hearing_status = $_POST['hearing_status']; // Status of the hearing

    try {
        $stmt = $pdo->prepare("
            UPDATE tbl_complaints
            SET hearing_status = ?
            WHERE complaints_id = ?
        ");
        $stmt->execute([$hearing_status, $complaint_id]);

        echo "Hearing status updated successfully.";
    } catch (PDOException $e) {
        echo "Error updating hearing status: " . $e->getMessage();
    }
}
?>
