<?php
include 'connection/dbconn.php';

// Start the session
session_start();

// Initialize variables
$email = $new_password = $confirm_password = $security_answer_1 = $security_answer_2 = $security_answer_3 = "";
$email_err = $new_password_err = $confirm_password_err = $security_answer_err = "";
$security_question_1 = $security_question_2 = $security_question_3 = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Step 1: Handle email input and fetch security questions
    if (isset($_POST['submit_email'])) {
        if (empty(trim($_POST['email']))) {
            $email_err = "Please enter your email.";
        } else {
            $email = trim($_POST['email']);
            
            // Fetch security questions based on email
            $stmt = $pdo->prepare("SELECT security_question_1, security_question_2, security_question_3 FROM tbl_users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                // Set security questions from the fetched data
                $security_question_1 = $user['security_question_1'];
                $security_question_2 = $user['security_question_2'];
                $security_question_3 = $user['security_question_3'];

                // Store email in session for use in step 2
                $_SESSION['email'] = $email;
            } else {
                $email_err = "Email not found!";
            }
        }
    }

    // Step 2: Handle security question answers and password reset
    if (isset($_POST['submit_answers'])) {
        // Retrieve email from session
        $email = $_SESSION['email'] ?? "";

        $selected_question_1 = trim($_POST['security_question_1']);
        $security_answer_1 = trim($_POST['security_answer_1']);
        $selected_question_2 = trim($_POST['security_question_2']);
        $security_answer_2 = trim($_POST['security_answer_2']);
        $selected_question_3 = trim($_POST['security_question_3']);
        $security_answer_3 = trim($_POST['security_answer_3']);
        $new_password = trim($_POST['new_password']);
        $confirm_password = trim($_POST['confirm_password']);

        // Validate answers and password input
        if (empty($security_answer_1) || empty($security_answer_2) || empty($security_answer_3)) {
            $security_answer_err = "Please answer all security questions.";
        }
        if (empty($new_password)) {
            $new_password_err = "Please enter a new password.";
        } elseif (strlen($new_password) < 8) {
            $new_password_err = "Password must have at least 8 characters.";
        }
        if ($new_password != $confirm_password) {
            $confirm_password_err = "Passwords do not match.";
        }

        if (empty($email_err) && empty($new_password_err) && empty($confirm_password_err) && empty($security_answer_err)) {
            try {
                // Fetch the user again to verify security answers
                $stmt_check_email = $pdo->prepare("SELECT user_id, security_question_1, security_question_2, security_question_3, security_answer_1, security_answer_2, security_answer_3 FROM tbl_users WHERE email = ?");
                $stmt_check_email->execute([$email]);
                $user = $stmt_check_email->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    // Verify security answers
                    if ($selected_question_1 === $user['security_question_1'] && 
                        password_verify($security_answer_1, $user['security_answer_1']) &&
                        $selected_question_2 === $user['security_question_2'] && 
                        password_verify($security_answer_2, $user['security_answer_2']) &&
                        $selected_question_3 === $user['security_question_3'] && 
                        password_verify($security_answer_3, $user['security_answer_3'])) {
                        
                        // Security answers are correct, proceed with password update
                        $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
                        $stmt_update_password = $pdo->prepare("UPDATE tbl_users SET password = ? WHERE user_id = ?");
                        if ($stmt_update_password->execute([$hashedPassword, $user['user_id']])) {
                            echo "<div class='alert alert-success'>Password reset successful!</div>";
                            // Clear session email after successful reset
                            unset($_SESSION['email']);
                        } else {
                            echo "<div class='alert alert-danger'>Password reset failed!</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger'>Incorrect security answers!</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Email not found!</div>";
                }
            } catch (PDOException $e) {
                echo "<div class='alert alert-danger'>Database Error: " . $e->getMessage() . "</div>";
            }
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
            max-width: 600px;
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
            <?php if (empty($security_question_1) && empty($security_question_2) && empty($security_question_3)) : ?>
                <!-- Step 1: Email input -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" required>
                    <span class="invalid-feedback"><?php echo $email_err; ?></span>
                </div>
                <button type="submit" name="submit_email" class="btn btn-primary w-100">Next</button>
            <?php else : ?>
                <!-- Step 2: Security questions and new password input -->
                <div class="row mb-3">
                    <div class="col-12 mb-3">
                        <label for="security_question_1" class="form-label">Security Question 1:</label>
                        <select id="security_question_1" name="security_question_1" class="form-select" required>
                            <option value="">Select a question...</option>
                            <option value="What was your childhood nickname?" <?php echo ($security_question_1 == 'What was your childhood nickname?') ? 'selected' : ''; ?>>What was your childhood nickname?</option>
                            <option value="What is the name of your first pet?" <?php echo ($security_question_1 == 'What is the name of your first pet?') ? 'selected' : ''; ?>>What is the name of your first pet?</option>
                            <option value="What was the make and model of your first car?" <?php echo ($security_question_1 == 'What was the make and model of your first car?') ? 'selected' : ''; ?>>What was the make and model of your first car?</option>
                        </select>
                        <input type="text" id="security_answer_1" name="security_answer_1" class="form-control mt-2" placeholder="Your answer" value="<?php echo $security_answer_1; ?>" required>
                    </div>
                    
                    <div class="col-12 mb-3">
                        <label for="security_question_2" class="form-label">Security Question 2:</label>
                        <select id="security_question_2" name="security_question_2" class="form-select" required>
                            <option value="">Select a question...</option>
                            <option value="In what city were you born?" <?php echo ($security_question_2 == 'In what city were you born?') ? 'selected' : ''; ?>>In what city were you born?</option>
                            <option value="What is your favorite book?" <?php echo ($security_question_2 == 'What is your favorite book?') ? 'selected' : ''; ?>>What is your favorite book?</option>
                            <option value="What was the name of your elementary school?" <?php echo ($security_question_2 == 'What was the name of your elementary school?') ? 'selected' : ''; ?>>What was the name of your elementary school?</option>
                        </select>
                        <input type="text" id="security_answer_2" name="security_answer_2" class="form-control mt-2" placeholder="Your answer" value="<?php echo $security_answer_2; ?>" required>
                    </div>
                    
                    <div class="col-12 mb-3">
                        <label for="security_question_3" class="form-label">Security Question 3:</label>
                        <select id="security_question_3" name="security_question_3" class="form-select" required>
                            <option value="">Select a question...</option>
                            <option value="What is your mother’s maiden name?" <?php echo ($security_question_3 == 'What is your mother’s maiden name?') ? 'selected' : ''; ?>>What is your mother’s maiden name?</option>
                            <option value="What was your high school mascot?" <?php echo ($security_question_3 == 'What was your high school mascot?') ? 'selected' : ''; ?>>What was your high school mascot?</option>
                            <option value="What street did you grow up on?" <?php echo ($security_question_3 == 'What street did you grow up on?') ? 'selected' : ''; ?>>What street did you grow up on?</option>
                        </select>
                        <input type="text" id="security_answer_3" name="security_answer_3" class="form-control mt-2" placeholder="Your answer" value="<?php echo $security_answer_3; ?>" required>
                    </div>
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
                <button type="submit" name="submit_answers" class="btn btn-primary w-100">Reset Password</button>
            <?php endif; ?>
        </form>
        <p class="mt-3 text-center"><a href="./reg/login.php">Back to Login</a></p>
    </div>
</body>
</html>
