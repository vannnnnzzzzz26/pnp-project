<?php
// Include database connection file (adjust path as needed)
require 'dbconn.php';

// Error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to safely retrieve POST data
function getPostData($key) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : null;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $first_name = getPostData('first_name');
    $middle_name = getPostData('middle_name');
    $last_name = getPostData('last_name');
    $extension_name = getPostData('extension_name');  // Optional field
    $email = getPostData('email');
    $password = getPostData('password');
    $confirm_password = getPostData('confirm_password');
    $accountType = getPostData('accountType');
    $barangay_name = getPostData('barangay');

    // Validate form data
    if ($first_name && $middle_name && $last_name && $email && $password && $confirm_password && $accountType && $barangay_name) {
        // Check if passwords match
        if ($password !== $confirm_password) {
            echo 'error_password';
            exit; // Ensure script stops executing after sending response
        }

        // Check if the email already exists in the database
        $stmt_check_email = $pdo->prepare("SELECT COUNT(*) FROM tbl_users WHERE email = ?");
        $stmt_check_email->execute([$email]);
        $count = $stmt_check_email->fetchColumn();

        if ($count > 0) {
            echo 'error_email_exists';
            exit;
        }

        // Handle file upload
        $pic_data = null; // Initialize pic_data

        if ($_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $temp_name = $_FILES['profile_picture']['tmp_name'];
            $file_name = $_FILES['profile_picture']['name'];
            $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
            $upload_directory = 'uploads/'; // Directory where uploads will be stored
            $new_file_name = uniqid('profile_') . '.' . $file_extension; // Generate a unique filename
            $destination = $upload_directory . $new_file_name;

            if (move_uploaded_file($temp_name, $destination)) {
                // File uploaded successfully, store the file path in $pic_data
                $pic_data = $destination;
            } else {
                echo 'error_file_upload';
                exit;
            }
        }

        // Insert into tbl_users_barangay
        $stmt_barangay = $pdo->prepare("INSERT INTO tbl_users_barangay (barangay_name) VALUES (?)");
        $stmt_barangay->execute([$barangay_name]);
        $barangays_id = $pdo->lastInsertId(); // Retrieve the last inserted ID

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Now insert into tbl_users
        $stmt_users = $pdo->prepare("INSERT INTO tbl_users (first_name, middle_name, last_name, extension_name, email, password, accountType, barangays_id, pic_data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt_users->execute([$first_name, $middle_name, $last_name, $extension_name, $email, $hashedPassword, $accountType, $barangays_id, $pic_data]);

        if ($stmt_users->rowCount() > 0) {
            echo 'success';
            exit;
        } else {
            echo 'error_database';
            exit;
        }
    } else {
        echo 'error_required_fields';
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.4/dist/sweetalert2.min.css" rel="stylesheet">
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
        <h1 class="text-center mb-4">Register</h1>
        <form id="registerForm" method="post">
            <div class="row">
                <!-- First Name and Middle Name -->
                <div class="col-md-6 mb-3">
                    <label for="first_name" class="form-label">First Name:</label>
                    <input type="text" id="first_name" name="first_name" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="middle_name" class="form-label">Middle Name:</label>
                    <input type="text" id="middle_name" name="middle_name" class="form-control" required>
                </div>

                <!-- Last Name and Extension Name -->
                <div class="col-md-6 mb-3">
                    <label for="last_name" class="form-label">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="extension_name" class="form-label">Extension Name:</label>
                    <input type="text" id="extension_name" name="extension_name" class="form-control">
                </div>

                <!-- Email -->
                <div class="col-12 mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <!-- Password and Confirm Password -->
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="confirm_password" class="form-label">Re-enter Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                </div>

                <!-- Account Type -->
                <div class="col-md-6 mb-3">
                    <label for="accountType" class="form-label">Account Type:</label>
                    <select id="accountType" name="accountType" class="form-select" required>
                        <option value="Barangay Official">Barangay Official</option>
                        <option value="PNP Officer">PNP Officer</option>
                        <option value="Resident">Resident</option>
                    </select>
                </div>

                <!-- Barangay Select -->
                <div class="col-md-6 mb-3">
                    <label for="barangay" class="form-label">Barangay:</label>
                    <select id="barangay" name="barangay" class="form-select" required>
                        <?php
                        // Array of barangays of echague
                        $barangays = [
                            "Angoluan", "Annafunan", "Arabiat", "Aromin", "Babaran", "Bacradal", "Benguet", "Buneg", "Busilelao", "Cabugao (Poblacion)",
                            "Caniguing", "Carulay", "Castillo", "Dammang East", "Dammang West", "Diasan", "Dicaraoyan", "Dugayong", "Fugu", "Garit Norte",
                            "Garit Sur", "Gucab", "Gumbauan", "Ipil", "Libertad", "Mabbayad", "Mabuhay", "Madadamian", "Magleticia", "Malibago", "Maligaya",
                            "Malitao", "Narra", "Nilumisu", "Pag-asa", "Pangal Norte", "Pangal Sur", "Rumang-ay", "Salay", "Salvacion", "San Antonio Ugad",
                            "San Antonio Minit", "San Carlos", "San Fabian", "San Felipe", "San Juan", "San Manuel (formerly Atelan)", "San Miguel", "San Salvador",
                            "Santa Ana", "Santa Cruz", "Santa Maria", "Santa Monica", "Santo Domingo", "Silauan Sur (Poblacion)", "Silauan Norte (Poblacion)",
                            "Sinabbaran", "Soyung (Poblacion)", "Taggappan (Poblacion)", "Villa Agullana", "Villa Concepcion", "Villa Cruz", "Villa Fabia",
                            "Villa Gomez", "Villa Nuesa", "Villa Padian", "Villa Pereda", "Villa Quirino", "Villa Remedios", "Villa Serafica", "Villa Tanza",
                            "Villa Verde", "Villa Vicenta", "Villa Ysmael (formerly T. Belen)"
                        ];

                        // Display barangays as options
                        foreach ($barangays as $barangay) {
                            echo "<option value=\"$barangay\">$barangay</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Profile Picture Upload -->
<div class="col-md-6 mb-3">
    <label for="profile_picture" class="form-label">Profile Picture:</label>
    <input type="file" id="profile_picture" name="profile_picture" class="form-control">
</div>

            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Register</button>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <!-- Include SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.4/dist/sweetalert2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registerForm');

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(form);

                fetch('register.php', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(result => {
                    if (result === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Registration successful!'
                        }).then(() => {
                            window.location.href = 'login.php'; // Redirect to login page
                        });
                    } else if (result === 'error_password') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Passwords do not match!'
                        });
                    } else if (result === 'error_email_exists') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Email already exists!'
                        });
                    } else if (result === 'error_required_fields') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'All fields are required!'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Registration failed!'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Registration failed!'
                    });
                });
            });
        });
    </script>
</body>
</html>
