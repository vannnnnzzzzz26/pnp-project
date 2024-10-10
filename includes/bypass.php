<?php

include '../connection/dbconn.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../reg/login.php");
    exit();
}

// Additional security check: Verify the user exists in the database
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM tbl_users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    // If the user does not exist, destroy the session and redirect
    session_destroy();
    header("Location: ../reg/login.php");
    exit();
}

?>