<?php
require 'dbconn.php'; // Ensure this file contains valid PDO initialization

// Error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Initialize variables
$email = $new_password = $confirm_password = "";
$email_err = $new_password_err = $confirm_password_err = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate email
    if (empty(trim($_POST['email']))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST['email']);
    }

    // Validate new password
    if (empty(trim($_POST['new_password']))) {
        $new_password_err = "Please enter a new password.";
    } elseif (strlen(trim($_POST['new_password'])) < 8) {
        $new_password_err = "Password must have at least 8 characters.";
    } else {
        $new_password = trim($_POST['new_password']);
    }

    // Validate confirm password
    if (empty(trim($_POST['confirm_password']))) {
        $confirm_password_err = "Please confirm the password.";
    } else {
        $confirm_password = trim($_POST['confirm_password']);
        if ($new_password != $confirm_password) {
            $confirm_password_err = "Passwords do not match.";
        }
    }

    // Process reset if no errors
    if (empty($email_err) && empty($new_password_err) && empty($confirm_password_err)) {
        try {
            // Check if the email exists in the database
            $stmt_check_email = $pdo->prepare("SELECT user_id FROM tbl_users WHERE email = ?");
            $stmt_check_email->execute([$email]);
            $user_id = $stmt_check_email->fetchColumn();

            if ($user_id) {
                // Email exists, proceed with password update
                $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt_update_password = $pdo->prepare("UPDATE tbl_users SET password = ? WHERE user_id = ?");
                if ($stmt_update_password->execute([$hashedPassword, $user_id])) {
                    echo "<div class='alert alert-success'>Password reset successful!</div>";
                } else {
                    echo "<div class='alert alert-danger'>Password reset failed!</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Email not found!</div>";
            }
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger'>Database Error: " . $e->getMessage() . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Reset Password</h1>
        <form method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" required>
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>
            <div class="mb-3">
                <label for="new_password" class="form-label">New Password:</label>
                <input type="password" id="new_password" name="new_password" class="form-control <?php echo (!empty($new_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_password; ?>" required>
                <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>" required>
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <button type="submit" class="btn btn-primary w-100">Reset Password</button>
        </form>
        <p class="mt-3 text-center"><a href="login.php">Back to Login</a></p>
    </div>
</body>
</html>
