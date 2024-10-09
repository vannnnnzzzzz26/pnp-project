<?php
session_start();
include '../connection/dbconn.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    $firstName = htmlspecialchars($_POST['first_name']);
    $middleName = htmlspecialchars($_POST['middle_name']);
    $lastName = htmlspecialchars($_POST['last_name']);
    $extensionName = htmlspecialchars($_POST['extension_name']);
    $cp_number = htmlspecialchars($_POST['cp_number']);
    $currentPassword = htmlspecialchars($_POST['current_password']);
    $newPassword = htmlspecialchars($_POST['new_password']);
    $redirectTo = isset($_POST['redirect_to']) ? $_POST['redirect_to'] : 'resident'; // Default to 'resident' if no redirect specified

    try {
        // Start the transaction
        $pdo->beginTransaction();

        // Fetch the current password hash from the database
        $stmt = $pdo->prepare("SELECT password FROM tbl_users WHERE user_id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($currentPassword, $user['password'])) {
            throw new Exception("Current password is incorrect.");
        }

        // Update user details
        $stmt = $pdo->prepare("UPDATE tbl_users SET first_name = ?, middle_name = ?, last_name = ?, extension_name = ?, cp_number = ? WHERE user_id = ?");
        $stmt->execute([$firstName, $middleName, $lastName, $extensionName, $cp_number, $userId]);

        // Handle profile picture update
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = mime_content_type($_FILES['profile_pic']['tmp_name']);
            $fileSize = $_FILES['profile_pic']['size'];

            if (in_array($fileType, $allowedTypes) && $fileSize <= 2000000) { // 2MB limit
                $profilePicFilename = basename($_FILES['profile_pic']['name']);
                $profilePicPath = '../uploads/' . $profilePicFilename;

                // Create 'uploads' directory if it doesn't exist
                if (!file_exists('../uploads')) {
                    mkdir('../uploads', 0777, true); // Create directory with full permissions
                }

                // Move uploaded file to 'uploads' directory
                if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profilePicPath)) {
                    $stmt = $pdo->prepare("UPDATE tbl_users SET pic_data = ? WHERE user_id = ?");
                    $stmt->execute([$profilePicPath, $userId]);

                    // Update session variable
                    $_SESSION['pic_data'] = $profilePicPath;
                } else {
                    throw new Exception("Failed to upload profile picture.");
                }
            } else {
                throw new Exception("Invalid file type or size. Please upload a valid image (JPEG, PNG, GIF) no larger than 2MB.");
            }
        }

        // Update password if a new password is provided
        if (!empty($newPassword)) {
            $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE tbl_users SET password = ? WHERE user_id = ?");
            $stmt->execute([$newPasswordHash, $userId]);
        }

        // Commit the transaction
        $pdo->commit();

        // Update session variables
        $_SESSION['first_name'] = $firstName;
        $_SESSION['middle_name'] = $middleName;
        $_SESSION['last_name'] = $lastName;
        $_SESSION['extension_name'] = $extensionName;
        $_SESSION['cp_number'] = $cp_number;

        // Set a success message
        $_SESSION['alert_message'] = "Profile updated successfully!";
        $_SESSION['alert_type'] = "success";

        // Redirect based on the value of redirectTo
        switch ($redirectTo) {
            case 'complainant-logs':
                header("Location: complainant_logs.php");
                break;
            case 'resident':
         
                header("Location: resident.php");
                break;
        }
        exit(); // Always exit after a header redirect to stop further script execution

    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['alert_message'] = "Database error: " . $e->getMessage();
        $_SESSION['alert_type'] = "error";
  
    } catch (Exception $e) {
        $_SESSION['alert_message'] = "Error: " . $e->getMessage();
        $_SESSION['alert_type'] = "error";
    }
}
?>
