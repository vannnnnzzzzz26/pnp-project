<?php
include '../connection/dbconn.php';
session_start();

// Ensure the user is redirected to this page only if they need to verify OTP
if (!isset($_SESSION['otp_email'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_SESSION['otp_email'];
    $otp = $_POST['otp'];

    $stmt = $pdo->prepare("SELECT otp FROM tbl_users WHERE email = ?");
    $stmt->execute([$email]);
    $storedOtp = $stmt->fetchColumn();

    if ($otp == $storedOtp) {
        // Mark the email as verified
        $stmt = $pdo->prepare("UPDATE tbl_users SET is_verified = 1, otp = NULL WHERE email = ?");
        $stmt->execute([$email]);

        // Retrieve user details
        $stmt = $pdo->prepare("SELECT * FROM tbl_users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Clear OTP email session
        unset($_SESSION['otp_email']);

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

        $_SESSION['login_success'] = "Your email has been verified. Welcome back!";

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
        $_SESSION['otp_error'] = "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
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
        <h1>Verify OTP</h1>
        <form method="post">
            <div class="mb-3">
                <label for="otp" class="form-label">Enter OTP</label>
                <input type="text" id="otp" name="otp" class="form-control" required>
            </div>
            <?php if (isset($_SESSION['otp_error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $_SESSION['otp_error']; ?>
                </div>
                <?php unset($_SESSION['otp_error']); ?>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary">Verify OTP</button>
        </form>
    </div>
</body>
</html>
