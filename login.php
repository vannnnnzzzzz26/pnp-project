<?php
require 'dbconn.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM tbl_users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['middle_name'] = $user['middle_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['pic_data'] = $user['pic_data'];
        $_SESSION['accountType'] = $user['accountType'];
        $_SESSION['barangays_id'] = $user['barangays_id'];

       
       

        // Set success message
        $_SESSION['login_success'] = "Welcome " . $user['first_name'] . "!";

        // Set the redirection URL based on account type
        if ($user['accountType'] == 'Barangay Official') {
            $_SESSION['redirect_url'] = "barangay-responder.php";
        } elseif ($user['accountType'] == 'PNP Officer') {
            $_SESSION['redirect_url'] = "pnp.php";
        } elseif ($user['accountType'] == 'Resident') {
            $_SESSION['redirect_url'] = "index.php";
        } else {
            $_SESSION['login_error'] = "Invalid account type!";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['login_error'] = "Invalid email or password!";
        header("Location: login.php");
        exit();
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.all.min.js"></script>
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
        <h1 class="text-center mb-4">Login</h1>
        <form method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <p class="mt-3 text-center">
            Don't have an account? <a href="register.php">Register here</a><br>
            <a href="forgot-password.php">Forgot Password?</a>
        </p>
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
