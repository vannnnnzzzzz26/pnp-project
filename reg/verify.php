<?php
include '../connection/dbconn.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if token is valid
    $stmt = $pdo->prepare("SELECT * FROM tbl_email_verification WHERE token = ? AND is_used = 0");
    $stmt->execute([$token]);
    $verification = $stmt->fetch();

    if ($verification) {
        // Update the user's verification status
        $stmt = $pdo->prepare("UPDATE tbl_users SET is_verified = 1 WHERE user_id = ?");
        $stmt->execute([$verification['user_id']]);

        // Mark the token as used
        $stmt = $pdo->prepare("UPDATE tbl_email_verification SET is_used = 1 WHERE token = ?");
        $stmt->execute([$token]);

        echo "Your email has been verified!";
    } else {
        echo "Invalid or expired token!";
    }
} else {
    echo "No token provided!";
}
?>
