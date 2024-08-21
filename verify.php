<?php
require 'dbconn.php';
session_start();

if (isset($_GET['code'])) {
    $verificationCode = $_GET['code'];

    // Verify the code
    $stmt = $pdo->prepare("SELECT * FROM tbl_users WHERE verification_code = ?");
    $stmt->execute([$verificationCode]);
    $user = $stmt->fetch();

    if ($user) {
        // Update the user to set as verified
        $stmt = $pdo->prepare("UPDATE tbl_users SET is_verified = 1 WHERE verification_code = ?");
        $stmt->execute([$verificationCode]);

        $_SESSION['verification_success'] = "Your email has been verified. You can now log in.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['verification_error'] = "Invalid verification code.";
        header("Location: login.php");
        exit();
    }
} else {
    $_SESSION['verification_error'] = "No verification code provided.";
    header("Location: login.php");
    exit();
}
?>
