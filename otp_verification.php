<?php
require 'dbconn.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['unverified_user_id'])) {
        $otp = $_POST['otp'];
        $user_id = $_SESSION['unverified_user_id'];

        // Log the OTP and user_id being checked
        error_log("Checking OTP: User ID - " . $user_id . ", OTP - " . $otp);

        $stmt = $pdo->prepare("SELECT * FROM tbl_users WHERE user_id = ? AND otp = ? AND otp_expiry > NOW()");
        $stmt->execute([$user_id, $otp]);
        $user = $stmt->fetch();

        if ($user) {
            $stmt = $pdo->prepare("UPDATE tbl_users SET is_verified = 1, otp = NULL, otp_expiry = NULL WHERE user_id = ?");
            $stmt->execute([$user_id]);

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['middle_name'] = $user['middle_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['pic_data'] = $user['pic_data'];
            $_SESSION['accountType'] = $user['accountType'];
            $_SESSION['barangays_id'] = $user['barangays_id'];

            $_SESSION['login_success'] = "Welcome " . $user['first_name'] . "!";

            if ($user['accountType'] == 'Barangay Official') {
                $_SESSION['redirect_url'] = "barangay-responder.php";
            } elseif ($user['accountType'] == 'PNP Officer') {
                $_SESSION['redirect_url'] = "pnp.php";
            } elseif ($user['accountType'] == 'Resident') {
                $_SESSION['redirect_url'] = "resident.php";
            } else {
                $_SESSION['login_error'] = "Invalid account type!";
                header("Location: login.php");
                exit();
            }

            unset($_SESSION['unverified_user_id']);
            header("Location: " . $_SESSION['redirect_url']);
            exit();
        } else {
            $stmt = $pdo->prepare("SELECT * FROM tbl_users WHERE user_id = ?");
            $stmt->execute([$user_id]);
            $debug_user = $stmt->fetch();

            // Detailed error logging
            error_log("Debug Info: User ID - " . $user_id);
            error_log("Debug Info: OTP - " . $otp);
            error_log("Debug Info: User OTP - " . $debug_user['otp']);
            error_log("Debug Info: User OTP Expiry - " . $debug_user['otp_expiry']);

            $_SESSION['otp_error'] = "Invalid or expired OTP!";
            header("Location: otp_verification.php");
            exit();
        }
    } else {
        $_SESSION['otp_error'] = "Session expired or user ID not found!";
        header("Location: otp_verification.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.all.min.js"></script>
</head>
<body>
    <div class="container">
        <h1>Verify OTP</h1>
        <form method="post">
            <div class="mb-3">
                <label for="otp" class="form-label">Enter OTP</label>
                <input type="text" id="otp" name="otp" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Verify</button>
        </form>
    </div>

    <script>
        <?php if (isset($_SESSION['otp_error'])): ?>
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'OTP Verification Failed',
                text: '<?php echo $_SESSION['otp_error']; ?>',
                showConfirmButton: true,
            });
            <?php unset($_SESSION['otp_error']); ?>
        <?php endif; ?>
    </script>
</body>
</html>
