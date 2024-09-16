<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include '../connection/dbconn.php';
session_start();

// Ensure the user is redirected to this page only if they need to request an OTP
if (!isset($_SESSION['otp_email'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_SESSION['otp_email'];

    // Generate a random OTP
    $otp = rand(100000, 999999);

    // Store the OTP in the database
    $stmt = $pdo->prepare("UPDATE tbl_users SET otp = ? WHERE email = ?");
    $stmt->execute([$otp, $email]);

    // Send OTP email
    sendOtpEmail($email, $otp);

    // Redirect to OTP verification page
    header("Location: otp_verification.php");
    exit();
}

function sendOtpEmail($email, $otp) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'your_email@example.com'; // SMTP username
        $mail->Password = 'your_password'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
  $mail->Username = 'mlgaming143@gmail.com'; // SMTP username
        $mail->Password = 'qzhy sgfu kszi mtul';
        //Recipients
        $mail->setFrom('no-reply@example.com', 'email Verification for new account');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP for Login Verification';
        $mail->Body    = "Your OTP for login verification is: <strong>$otp</strong>";

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request OTP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Request OTP</h1>
        <p>Your email is not verified. Click the button below to request an OTP.</p>
        <form method="post">
            <button type="submit" class="btn btn-primary">Request OTP</button>
        </form>
    </div>
</body>
</html>
