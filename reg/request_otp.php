<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include '../connection/dbconn.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT * FROM tbl_users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generate a random OTP
        $otp = rand(100000, 999999);

        // Store the OTP in the database
        $stmt = $pdo->prepare("UPDATE tbl_users SET otp = ?, otp_expiry = DATE_ADD(NOW(), INTERVAL 10 MINUTE) WHERE email = ?");
        $stmt->execute([$otp, $email]);

        // Send OTP email
        if (sendOtpEmail($email, $otp)) {
            echo json_encode(['status' => 'success', 'message' => 'OTP sent successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Email not found.']);
    }
}

function sendOtpEmail($email, $otp) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'mlgaming143@gmail.com'; // SMTP username
        $mail->Password = 'nvbo dqwb xbrr ppjz'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('no-reply@example.com', 'Your App');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP for Login Verification';
        $mail->Body    = "Your OTP for login verification is: <strong>$otp</strong>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>
