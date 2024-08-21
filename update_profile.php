<?php
require 'dbconn.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    $firstName = htmlspecialchars($_POST['first_name']);
    $middleName = htmlspecialchars($_POST['middle_name']);
    $lastName = htmlspecialchars($_POST['last_name']);
    $extensionName = htmlspecialchars($_POST['extension_name']);
    $email = htmlspecialchars($_POST['email']);

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("UPDATE tbl_users SET first_name = ?, middle_name = ?, last_name = ?, extension_name = ?, email = ? WHERE user_id = ?");
        $stmt->execute([$firstName, $middleName, $lastName, $extensionName, $email, $userId]);

        // Handle profile picture update
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == UPLOAD_ERR_OK) {
            $profilePicFilename = basename($_FILES['profile_pic']['name']);
            $profilePicPath = 'uploads/' . $profilePicFilename;

            // Create 'uploads' directory if it doesn't exist
            if (!file_exists('uploads')) {
                mkdir('uploads', 0777, true); // Create directory with full permissions
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
        }

        $pdo->commit();

        // Update session variables
        $_SESSION['first_name'] = $firstName;
        $_SESSION['middle_name'] = $middleName;
        $_SESSION['last_name'] = $lastName;
        $_SESSION['extension_name'] = $extensionName;
        $_SESSION['email'] = $email;

        // Redirect back to profile page or wherever appropriate
        header("Location: resident.php");
        exit();

    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
