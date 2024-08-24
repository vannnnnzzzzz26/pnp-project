


<?php
include '../connection/dbconn.php'; 


session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


$firstName = $_SESSION['first_name'];
$middleName = $_SESSION['middle_name'];
$lastName = $_SESSION['last_name'];
$extensionName = isset($_SESSION['extension_name']) ? $_SESSION['extension_name'] : '';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$barangay = isset($_SESSION['barangays_id']) ? $_SESSION['barangays_id'] : '';
$pic_data = isset($_SESSION['pic_data']) ? $_SESSION['pic_data'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
       
        $complaint_name = "$firstName $middleName $lastName $extensionName";
        $complaints = isset($_POST['complaints']) ? htmlspecialchars($_POST['complaints']) : '';
        $category = isset($_POST['category']) ? htmlspecialchars($_POST['category']) : '';
        $cp_number = isset($_POST['cp_number']) ? htmlspecialchars($_POST['cp_number']) : '';
        $complaints_person = isset($_POST['complaints_person']) ? htmlspecialchars($_POST['complaints_person']) : '';
        $age = isset($_POST['age']) ? htmlspecialchars($_POST['age']) : '';
        $gender = isset($_POST['gender']) ? htmlspecialchars($_POST['gender']) : '';
        $birth_date = isset($_POST['birth_date']) ? htmlspecialchars($_POST['birth_date']) : '';
        $place_of_birth = isset($_POST['place_of_birth']) ? htmlspecialchars($_POST['place_of_birth']) : '';
        $civil_status = isset($_POST['civil_status']) ? htmlspecialchars($_POST['civil_status']) : '';
        $educational_background = isset($_POST['educational_background']) ? htmlspecialchars($_POST['educational_background']) : '';
        $date_filed = date('Y-m-d H:i:s');

     
        $other_category = isset($_POST['other-category']) ? htmlspecialchars($_POST['other-category']) : '';
        if ($category === 'Other' && !empty($other_category)) {
            $category = $other_category;
        }

   
        $pdo->beginTransaction();


        $stmt = $pdo->prepare("SELECT category_id FROM tbl_complaintcategories WHERE complaints_category = ?");
        $stmt->execute([$category]);
        $category_id = $stmt->fetchColumn();

        if (!$category_id) {
            $stmt = $pdo->prepare("INSERT INTO tbl_complaintcategories (complaints_category) VALUES (?)");
            $stmt->execute([$category]);
            $category_id = $pdo->lastInsertId();
        }

      
        $stmt = $pdo->prepare("SELECT barangays_id FROM tbl_users_barangay WHERE barangays_id = ?");
        $stmt->execute([$barangay]);
        $barangays_id = $stmt->fetchColumn();

        if (!$barangays_id) {
            throw new Exception("Invalid Barangay ID."); 
        }

      
        $image_id = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $image_type = 'ID'; // Assuming you have a fixed image type for now
            $image_filename = basename($_FILES['image']['name']);
            $image_path = 'uploads/' . $image_filename;
            $date_uploaded = date('Y-m-d H:i:s');

        
            if (!file_exists('uploads')) {
                mkdir('uploads', 0777, true); 
            }

            if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                // Insert image into tbl_image
                $stmt = $pdo->prepare("INSERT INTO tbl_image (image_type, image_path, date_uploaded) VALUES (?, ?, ?)");
                $stmt->execute([$image_type, $image_path, $date_uploaded]);
                $image_id = $pdo->lastInsertId();
            } else {
                throw new Exception("Failed to upload image.");
            }
        }

    
        $stmt = $pdo->prepare("INSERT INTO tbl_info (age, gender, birth_date, place_of_birth, civil_status, educational_background) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$age, $gender, $birth_date, $place_of_birth, $civil_status, $educational_background]);
        $info_id = $pdo->lastInsertId();

        // Insert into tbl_complaints with status
        $stmt = $pdo->prepare("INSERT INTO tbl_complaints (complaint_name, complaints, date_filed, category_id, barangays_id, cp_number, complaints_person, info_id, image_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$complaint_name, $complaints, $date_filed, $category_id, $barangays_id, $cp_number, $complaints_person, $info_id, $image_id, 'Unresolved']);
        $complaint_id = $pdo->lastInsertId(); // Get the inserted complaint ID

        // Handle evidence upload if provided
        if (isset($_FILES['evidence']) && $_FILES['evidence']['error'][0] == UPLOAD_ERR_OK) {
            foreach ($_FILES['evidence']['tmp_name'] as $key => $tmp_name) {
                $evidence_filename = basename($_FILES['evidence']['name'][$key]);
                $evidence_path = 'uploads/' . $evidence_filename;
                $date_uploaded = date('Y-m-d H:i:s');

                // Move uploaded file to 'uploads' directory
                if (move_uploaded_file($tmp_name, $evidence_path)) {
                    // Insert evidence into tbl_evidence
                    $stmt = $pdo->prepare("INSERT INTO tbl_evidence (complaints_id, evidence_path, date_uploaded) VALUES (?, ?, ?)");
                    $stmt->execute([$complaint_id, $evidence_path, $date_uploaded]);
                } else {
                    throw new Exception("Failed to upload evidence.");
                }
            }
        }

        // Commit transaction
        $pdo->commit();

        // Set a session variable to indicate successful submission
        $_SESSION['success'] = true;

        // Redirect to avoid resubmission on page refresh
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();

    } catch (PDOException $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        echo "<div class='alert alert-danger' role='alert'>Error: " . $e->getMessage() . "</div>";
    } catch (Exception $e) {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $e->getMessage() . "</div>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Complaint</title>
    <!-- Bootstrap CSS -->
     <!-- Bootstrap Icons CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../styles/style.css">

</head>
<body>



<?php 

include '../includes/navbar.php';
include '../includes/resident-bar.php';
?>

    <!-- Page Content -->
 

   
 <div class="content">
   
    
    <div class="card">
    <div class="card-header text-center">
        <h3>Complaint Form</h3>
    </div>
    <div class="card-body">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" onsubmit="return onSubmitForm();">
            <div class="form-box">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                        <label for="complaint_name">Complaint Name:</label>
                        <p><?php echo htmlspecialchars("$firstName $middleName $lastName $extensionName"); ?></p>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                        <label for="barangay">Barangay:</label>
                        <?php 
                      include '../connection/dbconn.php'; 
                        try {
                            $stmt = $pdo->prepare("SELECT barangay_name FROM tbl_users_barangay WHERE barangays_id = ?");
                            $stmt->execute([$barangay]);
                            $barangay = $stmt->fetchColumn();
                            if ($barangay) {
                                echo "<p>" . htmlspecialchars($barangay) . "</p>";
                            } else {
                                echo "<p>No barangay found.</p>";
                            }
                        } catch (PDOException $e) {
                            echo "Error fetching barangay name: " . htmlspecialchars($e->getMessage());
                        }
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-sm-12 mb-3">
                        <label for="complaints">Complaint:</label>
                        <textarea id="complaints" name="complaints" class="form-control" required></textarea>
                    </div>
                    <div class="col-md-6 col-sm-12 mb-3">
                        <label for="category">Category:</label>
                        <select id="category" name="category" class="form-control" required>
                            <!-- Category options -->
                            <option value="Rape">Rape</option>
                            <!-- Other options here -->
                            <option value="Other">Other</option>
                        </select>
                        <div id="other-category-group" style="display: none;">
                            <label for="other-category">Please specify:</label>
                            <input type="text" id="other-category" name="other-category" class="form-control" placeholder="Specify your complaint" />
                        </div>
                    </div>
                </div>

                <script>
                    document.getElementById('category').addEventListener('change', function() {
                        var otherCategoryGroup = document.getElementById('other-category-group');
                        if (this.value === 'Other') {
                            otherCategoryGroup.style.display = 'block';
                        } else {
                            otherCategoryGroup.style.display = 'none';
                            document.getElementById('other-category').value = ''; // Clear the input field
                        }
                    });
                </script>

                <div class="row">
                    <div class="col-md-6 col-sm-12 mb-3">
                        <label for="evidence">Upload Evidence:</label>
                        <input type="file" id="evidence" name="evidence[]" class="form-control" multiple required>
                    </div>
                    <div class="col-md-6 col-sm-12 mb-3">
                        <label for="complaints_person">Person Complained Against:</label>
                        <input type="text" id="complaints_person" name="complaints_person" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-sm-12 mb-3">
                        <label for="cp_number">CP Number:</label>
                        <input type="text" id="cp_number" name="cp_number" class="form-control" required>
                    </div>
                    <div class="col-md-6 col-sm-12 mb-3">
                        <label for="age">Age:</label>
                        <input type="number" id="age" name="age" class="form-control" readonly>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-sm-12 mb-3">
                        <label for="gender">Gender:</label>
                        <select id="gender" name="gender" class="form-control" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="col-md-6 col-sm-12 mb-3">
                        <label for="birth_date">Birth Date:</label>
                        <input type="date" id="birth_date" name="birth_date" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                        <label for="place_of_birth">Place of Birth:</label>
                        <input type="text" id="place_of_birth" name="place_of_birth" class="form-control" required>
                    </div>
                    <div class="col-md-6 col-sm-12 mb-3">
                        <label for="civil_status">Civil Status:</label>
                        <select id="civil_status" name="civil_status" class="form-control" required>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Divorced">Divorced</option>
                            <option value="Widowed">Widowed</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                        <label for="educational_background">Educational Background:</label>
                        <select id="educational_background" name="educational_background" class="form-control" required>
                            <option value="Primary">Primary</option>
                            <option value="Secondary">Secondary</option>
                            <option value="Tertiary">Tertiary</option>
                        </select>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                        <label for="image">Upload Image (if any):</label>
                        <input type="file" id="image" name="image" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-center">
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>



<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProfileForm" action="../update_profile.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="editFirstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="editFirstName" name="first_name" value="<?php echo htmlspecialchars($firstName); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="editMiddleName" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="editMiddleName" name="middle_name" value="<?php echo htmlspecialchars($middleName); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="editLastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="editLastName" name="last_name" value="<?php echo htmlspecialchars($lastName); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="editExtensionName" class="form-label">Extension Name</label>
                        <input type="text" class="form-control" id="editExtensionName" name="extension_name" value="<?php echo htmlspecialchars($extensionName); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="editProfilePic" class="form-label">Profile Picture</label>
                        <input type="file" class="form-control" id="editProfilePic" name="profile_pic">
                    </div>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script src="../scripts/script.js"></script>
  
    <!-- Include jQuery and Bootstrap JavaScript -->


 
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   
    <script>
        // Check if the session variable is set and show SweetAlert
        <?php if (isset($_SESSION['success'])): ?>
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Your complaint has been submitted',
                showConfirmButton: false,
                timer: 1500
            });
            // Unset the session variable
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        function onSubmitForm() {
            // Check if image field is empty
            var imageField = document.getElementById('image');
            if (imageField.value.trim() === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please upload an image!',
                });
                return false; // Prevent form submission
            }
            return true; // Allow form submission
        }


        function confirmLogout() {
        Swal.fire({
            title: "Are you sure?",
            text: "You will be logged out.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#212529",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, logout"
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to logout URL
                window.location.href = " ../login.php?logout=<?php echo $_SESSION['user_id']; ?>";
            }
        });
    }
  

    </script>



<script>
            document.getElementById('category').addEventListener('change', function() {
                var otherCategoryGroup = document.getElementById('other-category-group');
                if (this.value === 'Other') {
                    otherCategoryGroup.style.display = 'block';
                } else {
                    otherCategoryGroup.style.display = 'none';
                    document.getElementById('other-category').value = ''; // Clear the input field
                }
            });

            // Age calculation based on birth date
            document.getElementById('birth_date').addEventListener('change', function() {
                var birthDate = new Date(this.value);
                var today = new Date();
                var age = today.getFullYear() - birthDate.getFullYear();
                var monthDifference = today.getMonth() - birthDate.getMonth();
                
                // Adjust the age if the birthday hasn't occurred yet this year
                if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }

                // Set the calculated age
                document.getElementById('age').value = age;
            });






            document.addEventListener('DOMContentLoaded', function () {
        var profilePic = document.querySelector('.profile');
        var editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));

        profilePic.addEventListener('click', function () {
            editProfileModal.show();
        });
    });


            
        </script>
    
</body>
</html>
