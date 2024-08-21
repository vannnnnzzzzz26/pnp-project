<?php 

require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT * FROM tbl_users WHERE email = ? AND email_verified = 0");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $verification_token = bin2hex(random_bytes(16));
        $stmt = $pdo->prepare("UPDATE tbl_users SET verification_token = ? WHERE email = ?");
        $stmt->execute([$verification_token, $email]);

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'mlgaming142@gmail.com'; // Your email
            $mail->Password = 'Abc1234@'; // Your email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('mlgaming142@gmail.com', 'Your Name');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Resend Email Verification';
            $mail->Body = 'Click the following link to verify your email: <a href="verify.php?token=' . $verification_token . '">Verify Email</a>';

            $mail->send();
            echo "Verification email sent!";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Invalid email or email already verified.";
    }
}

?>