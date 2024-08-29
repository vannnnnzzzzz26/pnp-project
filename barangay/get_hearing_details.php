<?php
// Start the session
session_start();

include '../connection/dbconn.php';

// Initialize PDO if not already done
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $complaint_id = $_GET['complaint_id'];

    try {
        $stmt = $pdo->prepare("SELECT hearing_date, hearing_time, hearing_type, hearing_status FROM tbl_complaints WHERE complaints_id = ?");
        $stmt->execute([$complaint_id]);
        $hearing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($hearing) {
            echo json_encode($hearing);
        } else {
            echo json_encode(["error" => "No hearing details found."]);
        }
    } catch (PDOException $e) {
        echo json_encode(["error" => "Error fetching hearing details: " . $e->getMessage()]);
    }
}
