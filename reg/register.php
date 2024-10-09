<?php
include '../connection/dbconn.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Function to safely retrieve POST data
function getPostData($key) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : null;
}

// Function to validate strong password
function isStrongPassword($password) {
    $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
    return preg_match($pattern, $password);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $first_name = getPostData('first_name');
    $middle_name = getPostData('middle_name');
    $last_name = getPostData('last_name');
    $extension_name = getPostData('extension_name'); // Optional field
    $cp_number = getPostData('cp_number'); // CP Number instead of email
    $password = getPostData('password');
    $confirm_password = getPostData('confirm_password');
    $accountType = getPostData('accountType');
    $barangay_name = getPostData('barangay');
    $security_question = getPostData('security_question');
    $security_answer = getPostData('security_answer');

    // New fields
    $civil_status = getPostData('civil_status');
    $nationality = getPostData('nationality');
    $age = getPostData('age');
    $birth_date = getPostData('birth_date');
    $gender = getPostData('gender');
    $place_of_birth = getPostData('place_of_birth'); // Added field for place of birth
    $purok = getPostData('purok'); // Added field for purok
    $educational_background = getPostData('educational_background'); // Added field for educational background
    $selfie_path = getPostData('selfie_path'); // Added field for educational background

    // Validate form data
    if ($first_name && $middle_name && $last_name && $cp_number && $password && $confirm_password && $accountType && $barangay_name && $security_question && $security_answer && $civil_status && $nationality && $age && $birth_date && $gender && $place_of_birth && $purok && $educational_background) {
        // Check if passwords match
        if ($password !== $confirm_password) {
            echo 'error_password';
            exit;
        }

        // Check if password is strong
        if (!isStrongPassword($password)) {
            echo 'error_weak_password';
            exit;
        }

        // Check if the CP Number already exists in the database
        $stmt_check_cp_number = $pdo->prepare("SELECT COUNT(*) FROM tbl_users WHERE cp_number = ?");
        $stmt_check_cp_number->execute([$cp_number]);
        $count = $stmt_check_cp_number->fetchColumn();

        if ($count > 0) {
            echo 'error_cp_number_exists';
            exit;
        }

        // Handle file upload for profile picture
        $pic_data = null;
        if ($_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $temp_name = $_FILES['profile_picture']['tmp_name'];
            $file_name = $_FILES['profile_picture']['name'];
            $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            $upload_directory = '../uploads/';
            $new_file_name = uniqid('profile_') . '.' . $file_extension;
            $destination = $upload_directory . $new_file_name;

            // Validate file type and move file
            if (in_array(strtolower($file_extension), $allowed_extensions)) {
                if (move_uploaded_file($temp_name, $destination)) {
                    $pic_data = $destination;
                } else {
                    echo 'error_file_upload';
                    exit;
                }
            } else {
                echo 'error_invalid_file_type';
                exit;
            }
        }

        // Handle Selfie upload
        $selfie_path = null;
        if (isset($_FILES['selfie']) && $_FILES['selfie']['error'] == UPLOAD_ERR_OK) {
            $selfie_filename = basename($_FILES['selfie']['name']);
            $selfie_path = '../uploads/' . uniqid('selfie_') . '_' . $selfie_filename;

            if (!file_exists('../uploads')) {
                mkdir('../uploads', 0777, true); 
            }

            if (move_uploaded_file($_FILES['selfie']['tmp_name'], $selfie_path)) {
                // Selfie uploaded successfully
            } else {
                echo 'error_selfie_upload';
                exit;
            }
        }

        // Insert into tbl_users_barangay
        $stmt_barangay = $pdo->prepare("INSERT INTO tbl_users_barangay (barangay_name) VALUES (?)");
        $stmt_barangay->execute([$barangay_name]);
        $barangays_id = $pdo->lastInsertId(); // Retrieve the last inserted ID

        // Hash the password and security answer
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $hashed_answer = password_hash($security_answer, PASSWORD_DEFAULT);

        // Insert into tbl_users (Fixed fields count and removed invalid variables)
        $stmt_users = $pdo->prepare("
            INSERT INTO tbl_users 
            (first_name, middle_name, last_name, extension_name, cp_number, password, accountType, barangays_id, pic_data, selfie_path, security_question, security_answer, civil_status, nationality, age, birth_date, gender, place_of_birth, purok, educational_background) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt_users->execute([
            $first_name, $middle_name, $last_name, $extension_name, $cp_number, $hashedPassword, $accountType, 
            $barangays_id, $pic_data, $selfie_path, $security_question, $hashed_answer, 
            $civil_status, $nationality, $age, $birth_date, $gender, 
            $place_of_birth, $purok, $educational_background
        ]);

        // Check if the user was successfully inserted
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
            background-image: url('../reg/poles.jpg');
            background-size: cover;
            background-position: center top;
            background-position: center;
            background-repeat: no-repeat;
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
        .form-control, .form-select {
            border-radius: 25px; /* Make inputs and selects round */
        }
        .btn {
            border-radius: 25px; /* Make button round */
        }
        .btn-primary {
            border-radius: 50px;
            background-color: #5bc0de;
            border: none;
            padding: 0.75rem;
            font-size: 1rem;
            width: 100%;
        }


        .progress {
    background-color: #e9ecef;
}

.progress-bar {
    transition: width 0.4s;
}

.weak {
    background-color: red;
}

.medium {
    background-color: orange;
}

.strong {
    background-color: green;
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
                    <input type="text" id="first_name" name="first_name" class="form-control" placeholder="Enter your first name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="middle_name" class="form-label">Middle Name:</label>
                    <input type="text" id="middle_name" name="middle_name" class="form-control" placeholder="Enter your middle name" required>
                </div>

                <!-- Last Name and Extension Name -->
                <div class="col-md-6 mb-3">
                    <label for="last_name" class="form-label">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Enter your last name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="extension_name" class="form-label">Extension Name:</label>
                    <input type="text" id="extension_name" name="extension_name" class="form-control" placeholder="Enter your extension name">
                </div>

                <!-- Email -->
                <div class="col-12 mb-3">
                    <label for="cp_number" class="form-label">CP Number:</label>
                    <input type="cp_number" id="cp_number" name="cp_number" class="form-control" placeholder="Enter your number" required>
                </div>

                <!-- Password and Confirm Password -->
                <div class="col-md-6 mb-3">
    <label for="password" class="form-label">Password:</label>
    <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
    <div id="password-strength" class="progress mt-2" style="height: 10px;">
        <div id="strength-bar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <small id="strength-text" class="form-text"></small>
</div>
<div class="col-md-6 mb-3">
    <label for="confirm_password" class="form-label">Re-enter Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Re-enter your password" required>


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


                <label for="purok">Purok:</label>
<select name="purok" required>
    <option value="">Select Purok</option>
    <option value="Purok 1">Purok 1</option>
    <option value="Purok 2">Purok 2</option>
    <option value="Purok 3">Purok 3</option>
    <option value="Purok 4">Purok 4</option>
    <option value="Purok 5">Purok 5</option>
    <option value="Purok 6">Purok 6</option>
    <option value="Purok 7">Purok 7</option>
</select>


                <div class="form-group">
    <label for="nationality">Nationality/Citizenship:</label>
    <input type="text" id="nationality" name="nationality" class="form-control" required>
</div>




<div class="col-lg-6 col-md-12 form-group">
              <label for="birth_date">Birth Date:</label>
              <input type="date" id="birth_date" name="birth_date" class="form-control" required>
            </div>
          </div>

          <!-- Gender and Age Information -->
          <div class="row">
            <div class="col-lg-6 col-md-12 form-group">
              <label for="gender">Gender:</label>
              <select id="gender" name="gender" class="form-control" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
              </select>
            </div>
            <div class="col-lg-6 col-md-12 form-group">
              <label for="age">Age:</label>
              <input type="number" id="age" name="age" class="form-control" readonly>
            </div>
          </div>

          <!-- Place of Birth and Civil Status -->
          <div class="row">
            <div class="col-lg-6 col-md-12 form-group">
              <label for="place_of_birth">Place of Birth:</label>
              <input type="text" id="place_of_birth" name="place_of_birth" class="form-control" required>
            </div>
            <div class="col-lg-6 col-md-12 form-group">
              <label for="civil_status">Civil Status:</label>
              <select id="civil_status" name="civil_status" class="form-control" required>
                <option value="Single">Single</option>
                <option value="Married">Married</option>
                <option value="Live-in">Live-in</option>
                <option value="Divorced">Divorced</option>
                <option value="Widowed">Widowed</option>
                <option value="Separated">Separated</option>
              </select>
            </div>


            <div class="row">
            <div class="col-lg-6 col-md-12 form-group">
              <label for="educational_background">Educational Attainment:</label>
              <select id="educational_background" name="educational_background" class="form-control" required>
                <option value="No Formal Education">No Formal Education</option>
                <option value="Elementary">Elementary</option>
                <option value="Highschool">Highschool</option>
                <option value="College">College</option>
                <option value="Post Graduate<">Post Graduate</option>
              </select>
            </div>


                <!-- Profile Picture Upload -->
                <div class="col-md-6 mb-3">
                    <label for="profile_picture" class="form-label">Profile Picture:</label>
                    <input type="file" id="profile_picture" name="profile_picture" class="form-control">
                </div>
            </div>

            
          </div>
          <div class="form-group">
        <label for="selfie">Upload Selfie:</label>
        <input type="file" name="selfie" accept="image/*" class="form-control" required>
    </div>
            <div class="col-12 mb-3">
                <label for="security_question" class="form-label">Security Question 1:</label>
                <select id="security_question" name="security_question" class="form-select" required>
                    <option value="">Select a question...</option>
                    <option value="What was your childhood nickname?">What was your childhood nickname?</option>
                    <option value="What is the name of your first pet?">What is the name of your first pet?</option>
                    <option value="What was the make and model of your first car?">What was the make and model of your first car?</option>
                </select>
                <input type="text" id="security_answer" name="security_answer" class="form-control mt-2" placeholder="Your answer" required>
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



document.getElementById('cp_number').addEventListener('input', function (e) {
    // Remove non-numeric characters
    this.value = this.value.replace(/\D/g, '');
    // Limit to 11 digits
    if (this.value.length > 11) {
        this.value = this.value.slice(0, 11);
    }
});

            document.getElementById('birth_date').addEventListener('change', function() {
    var birthDate = new Date(this.value);
    var today = new Date();
    var age = today.getFullYear() - birthDate.getFullYear();
    var monthDifference = today.getMonth() - birthDate.getMonth();
    
    // Adjust the age if the birthday hasn't occurred yet this year
    if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }

    if (age < 18) {
        alert("Age must be 18 or above.");
        this.value = ''; // Clear the birth date field
        document.getElementById('age').value = ''; // Clear the age field
    } else {
        document.getElementById('age').value = age; // Set the calculated age
    }
});




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
                    } else if (result === 'error_weak_password') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Password is too weak!'
                        });
                    } else if (result === 'error_file_upload') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'File upload error!'
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





        function assessPasswordStrength(password) {
        let strength = 0;

        // Check for various password strength criteria
        if (password.length >= 8) strength++; // Length
        if (/[A-Z]/.test(password)) strength++; // Uppercase letters
        if (/[a-z]/.test(password)) strength++; // Lowercase letters
        if (/\d/.test(password)) strength++; // Numbers
        
        // Check for special characters
        if (/[@$!%*?&]/.test(password)) {
            strength = 4; // Directly assign maximum strength if a special character is found
        }

        return strength;
    }

    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const strengthBar = document.getElementById('strength-bar');
        const strengthText = document.getElementById('strength-text');
        const strength = assessPasswordStrength(password);
        
        // Determine strength level and update the progress bar
        switch (strength) {
            case 0:
            case 1:
                strengthBar.style.width = '20%';
                strengthBar.className = 'progress-bar weak';
                strengthText.innerText = 'Weak';
                break;
            case 2:
                strengthBar.style.width = '50%';
                strengthBar.className = 'progress-bar medium';
                strengthText.innerText = 'Medium';
                break;
            case 3:
            case 4:
                strengthBar.style.width = '100%';
                strengthBar.className = 'progress-bar strong';
                strengthText.innerText = 'Strong';
                break;
            default:
                strengthBar.style.width = '0%';
                strengthText.innerText = '';
        }
    });
    </script>
</body>
</html>