<?php
include '../connection/dbconn.php';
session_start();
// In your auth.php or at the top of each protected page
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // Last request was more than 30 minutes ago
    session_unset();     // Unset session variables
    session_destroy();   // Destroy the session
    header("Location: login.php");
    exit();
}
$_SESSION['LAST_ACTIVITY'] = time(); // Update last activity timestamp


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cp_number = $_POST['cp_number']; // Get cp_number from the POST request
    $password = $_POST['password'];

    // Prepare and execute query to check user
    $stmt = $pdo->prepare("SELECT * FROM tbl_users WHERE cp_number = ?"); // Use cp_number for user lookup
    $stmt->execute([$cp_number]);
    $user = $stmt->fetch();

    if ($user) {
        $currentTime = new DateTime();
        $lockoutTime = new DateTime($user['lockout_time']);
        $lockoutInterval = $lockoutTime->diff($currentTime);

        if ($user['login_attempts'] >= 3 && $lockoutInterval->i < 2) {
            $_SESSION['login_error'] = "Account locked due to too many failed attempts. Please try again after 2 minutes.";
            header("Location: login.php");
            exit();
        }

        // If lockout period has passed, reset login attempts
        if ($lockoutInterval->i >= 2) {
            $stmt = $pdo->prepare("UPDATE tbl_users SET login_attempts = 0, lockout_time = NULL WHERE cp_number = ?"); // Reset login attempts
            $stmt->execute([$cp_number]);
        }

        if (password_verify($password, $user['password'])) {
            // Reset login attempts upon successful login
            $stmt = $pdo->prepare("UPDATE tbl_users SET login_attempts = 0, lockout_time = NULL WHERE cp_number = ?"); // Reset login attempts
            $stmt->execute([$cp_number]);

            // Clear any previous session data
            session_regenerate_id(true); // Regenerate session ID to prevent session fixation attacks
            session_unset(); // Unset all session variables

            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['cp_number'] = $user['cp_number']; // Store cp_number in session
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['middle_name'] = $user['middle_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['pic_data'] = $user['pic_data'];
            $_SESSION['accountType'] = $user['accountType'];
            $_SESSION['barangays_id'] = $user['barangays_id'];
            $_SESSION['birth_date'] = $user['birth_date'];
            $_SESSION['age'] = $user['age'];
            $_SESSION['gender'] = $user['gender'];

            $_SESSION['purok'] = $user['purok'];

            $_SESSION['nationality'] = $user['nationality'];
            $_SESSION['civil_status'] = $user['civil_status'];


            $_SESSION['login_success'] = "Welcome " . $user['first_name'] . "!";

            // Log login time
            $stmt = $pdo->prepare("INSERT INTO tbl_login_logs (user_id, login_time) VALUES (?, NOW())");
            $stmt->execute([$user['user_id']]);

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
            // Increment login attempts
            $stmt = $pdo->prepare("UPDATE tbl_users SET login_attempts = login_attempts + 1 WHERE cp_number = ?"); // Increment login attempts
            $stmt->execute([$cp_number]);

            // Check if the user has reached the limit of 3 failed attempts
            if ($user['login_attempts'] >= 2) {
                // Lockout the user by setting lockout_time to current time
                $stmt = $pdo->prepare("UPDATE tbl_users SET lockout_time = NOW() WHERE cp_number = ?"); // Lockout user
                $stmt->execute([$cp_number]);
                $_SESSION['login_error'] = "Too many failed login attempts. Account locked for 2 minutes.";
            } else {
                $_SESSION['login_error'] = "Invalid contact number or password!";
            }
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['login_error'] = "Invalid contact number or password!";
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
                <input type="cp_number" id="cp_number" name="cp_number" class="form-control" placeholder="CP number" required>
                
            </div>

            <div class="mb-3 input-group">
            <span class="input-group-text"><i class="fas fa-lock"></i></span>
            <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
            <span class="input-group-text">
                <i class="fas fa-eye" id="togglePassword" style="cursor: pointer;"></i>
            </span>
        </div>
            <div class="mb-3 form-check d-flex justify-content-start">
               
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


document.getElementById('cp_number').addEventListener('input', function (e) {
    // Remove non-numeric characters
    this.value = this.value.replace(/\D/g, '');
    // Limit to 11 digits
    if (this.value.length > 11) {
        this.value = this.value.slice(0, 11);
    }
});

      const togglePassword = document.querySelector('#togglePassword');
    const passwordField = document.querySelector('#password');

    togglePassword.addEventListener('click', function () {
        // Toggle the type attribute
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        
        // Toggle the eye / eye-slash icon
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
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
