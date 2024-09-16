<?php
session_start();
include '../connection/dbconn.php';

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $complaint_id = isset($_GET['complaint_id']) ? $_GET['complaint_id'] : '';

    try {
        $stmt = $pdo->prepare("SELECT hearing_date, hearing_time, hearing_type, hearing_status FROM tbl_hearing_complaints WHERE complaints_id = ?");
        $stmt->execute([$complaint_id]);
        $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($history);
    } catch (PDOException $e) {
        echo json_encode(["error" => "Error fetching hearing history: " . $e->getMessage()]);
    }
}
?>
