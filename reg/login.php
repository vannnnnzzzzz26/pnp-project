<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include '../connection/dbconn.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute query to check user
    $stmt = $pdo->prepare("SELECT * FROM tbl_users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Check if email is verified
        if ($user['is_verified'] == 0) {
            // Generate a random OTP
            $otp = rand(100000, 999999);

            // Store the OTP in the database
            $stmt = $pdo->prepare("UPDATE tbl_users SET otp = ? WHERE email = ?");
            $stmt->execute([$otp, $email]);

            // Send OTP email
            sendOtpEmail($email, $otp);

            // Save email to session for OTP verification
            $_SESSION['otp_email'] = $email;

            // Redirect to OTP verification page
            header("Location: otp_request.php");
            exit();
        }

        // Clear any previous session data
        session_regenerate_id(true); // Regenerate session ID to prevent session fixation attacks
        session_unset(); // Unset all session variables

        // Set session variables
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['middle_name'] = $user['middle_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['pic_data'] = $user['pic_data'];
        $_SESSION['accountType'] = $user['accountType'];
        $_SESSION['barangays_id'] = $user['barangays_id'];

        $_SESSION['login_success'] = "Welcome " . $user['first_name'] . "!";

        // Redirect based on account type
        if ($user['accountType'] == 'Barangay Official') {
            $redirectUrl = "../barangay/barangay-responder.php";
        } elseif ($user['accountType'] == 'PNP Officer') {
            $redirectUrl = "../pnp/pnp.php";
        } elseif ($user['accountType'] == 'Resident') {
            $redirectUrl = "../resident/resident.php";
        } else {
            $_SESSION['login_error'] = "Invalid account type!";
            header("Location: login.php");
            exit();
        }

        // Redirect to the appropriate dashboard
        header("Location: $redirectUrl");
        exit();
    } else {
        $_SESSION['login_error'] = "Invalid email or password!";
        header("Location: login.php");
        exit();
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
        $mail->Password = 'qzhy sgfu kszi mtul
'; // SMTP password
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
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.all.min.js"></script>
    <style>
        html, body {
            overflow: hidden;
            height: 100%;
        }

        body {
            background-image: url('../reg/poles.jpg');
            background-size: cover;
            background-position: center top;
            background-repeat: no-repeat;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: whitesmoke;
        }

        .container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .container h1 {
            margin-bottom: 1rem;
            font-size: 1.5rem;
            color: #333;
            text-align: center;
        }

        .input-group-text {
            background-color: transparent;
            border: none;
        }

        .input-group-text i {
            color: #5bc0de; 
        }

        .form-control {
            border-radius: 50px;
            border: 1px solid #ddd;
            padding-left: 2.5rem;
            background-color: #f5f5f5;
        }

        .form-check-label {
            margin-left: 0.3rem;
        }

        .form-check {
            margin: 0.5rem 0;
        }

        .btn-primary {
            border-radius: 50px;
            background-color: #5bc0de;
            border: none;
            padding: 0.75rem;
            font-size: 1rem;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #31b0d5;
        }

        .link {
            margin-top: 1rem;
            text-align: center;
        }

        .link a {
            color: #5bc0de;
            text-decoration: none;
        }

        .link a:hover {
            text-decoration: underline;
        }

        .forgot-password,
        .register {
            display: block;
            margin: 0.5rem 0;
            color: #5bc0de;
            text-align: center;
        }

        .forgot-password:hover,
        .register:hover {
            text-decoration: underline;
        }

        .forgot-password {
            text-decoration: none;
            color: #007bff; 
            font-size: 0.9rem; 
        }

        .forgot-password:hover {
             text-decoration: underline; 
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="../reg/complaint.jpg" alt="Illustration" class="img-fluid mb-4"> 
        <h1>Log in</h1>
        <form method="post">

            <div class="mb-3 input-group">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
            </div>

            <div class="mb-3 input-group">
                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <div class="mb-3 form-check d-flex justify-content-start">
                <input type="checkbox" class="form-check-input" id="rememberMe" name="rememberMe">
                <label class="form-check-label" for="rememberMe">Remember Me</label>
                <a href="../forgot-password.php" class="forgot-password">Forgot Password?</a>
            </div>

            <button type="submit" class="btn btn-primary">Log in</button>
            <div class="link">
                <span>Don't Have an Account?</span>
                <a href="register.php" class="register">Create a new account</a>
            </div>
        </form>
    </div>

    <script>
        <?php if (isset($_SESSION['login_success'])): ?>
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: '<?php echo $_SESSION['login_success']; ?>',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.href = '<?php echo $_SESSION['redirect_url']; ?>';
            });
            <?php unset($_SESSION['login_success']); unset($_SESSION['redirect_url']); ?>
        <?php elseif (isset($_SESSION['login_error'])): ?>
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Login Failed',
                text: '<?php echo $_SESSION['login_error']; ?>',
                showConfirmButton: true,
            });
            <?php unset($_SESSION['login_error']); ?>
        <?php endif; ?>
    </script>
</body>
</html>
