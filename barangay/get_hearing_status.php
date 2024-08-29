<?php
// get_hearing_status.php

session_start();

include '../connection/dbconn.php';

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_GET['id'])) {
    $complaint_id = $_GET['id'];

    try {
        $stmt = $pdo->prepare("SELECT hearing_status FROM tbl_complaints WHERE complaints_id = ?");
        $stmt->execute([$complaint_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($result);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
