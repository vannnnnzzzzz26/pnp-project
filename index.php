<?php
require 'dbconn.php';
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user information from session
$firstName = $_SESSION['first_name'];
$middleName = $_SESSION['middle_name'];
$lastName = $_SESSION['last_name'];
$extensionName = isset($_SESSION['extension_name']) ? $_SESSION['extension_name'] : '';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$barangay = isset($_SESSION['barangays_id']) ? $_SESSION['barangays_id'] : '';
$pic_data = isset($_SESSION['pic_data']) ? $_SESSION['pic_data'] : '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    try {
        // Fetch form data and sanitize inputs
        $complaint_name = "$firstName $middleName $lastName $extensionName";
        $complaints = htmlspecialchars($_POST['complaints']);
        $category = htmlspecialchars($_POST['category']);
        $cp_number = htmlspecialchars($_POST['cp_number']);
        $complaints_person = htmlspecialchars($_POST['complaints_person']);
        $age = htmlspecialchars($_POST['age']); // Assuming these are sanitized
        $gender = htmlspecialchars($_POST['gender']); // Assuming these are sanitized
        $date_filed = date('Y-m-d H:i:s');

        // Begin transaction
        $pdo->beginTransaction();

        // Insert category if it doesn't exist and get the category_id
        $stmt = $pdo->prepare("SELECT category_id FROM tbl_complaintcategories WHERE complaints_category = ?");
        $stmt->execute([$category]);
        $category_id = $stmt->fetchColumn();

        if (!$category_id) {
            $stmt = $pdo->prepare("INSERT INTO tbl_complaintcategories (complaints_category) VALUES (?)");
            $stmt->execute([$category]);
            $category_id = $pdo->lastInsertId();
        }

        // Insert barangay if it doesn't exist and get the barangay_id
        $stmt = $pdo->prepare("SELECT barangays_id FROM tbl_users_barangay WHERE barangays_id = ?");
        $stmt->execute([$barangay]);
        $barangays_id = $stmt->fetchColumn();

        if (!$barangays_id) {
            throw new Exception("Invalid Barangay ID."); // Adjust error handling as per your logic
        }

        // Handle image upload if provided
        $image_id = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $image_type = 'ID'; // Assuming you have a fixed image type for now
            $image_filename = basename($_FILES['image']['name']);
            $image_path = 'uploads/' . $image_filename;
            $date_uploaded = date('Y-m-d H:i:s');

            // Create 'uploads' directory if it doesn't exist
            if (!file_exists('uploads')) {
                mkdir('uploads', 0777, true); // Create directory with full permissions
            }

            // Move uploaded file to 'uploads' directory
            if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
                // Insert image into tbl_image
                $stmt = $pdo->prepare("INSERT INTO tbl_image (image_type, image_path, date_uploaded) VALUES (?, ?, ?)");
                $stmt->execute([$image_type, $image_path, $date_uploaded]);
                $image_id = $pdo->lastInsertId();
            } else {
                throw new Exception("Failed to upload image.");
            }
        }

        // Insert into tbl_info for age and gender
        $stmt = $pdo->prepare("INSERT INTO tbl_info (age, gender) VALUES (?, ?)");
        $stmt->execute([$age, $gender]);
        $info_id = $pdo->lastInsertId();

        // Insert into tbl_complaints with status
        $stmt = $pdo->prepare("INSERT INTO tbl_complaints (complaint_name, complaints, date_filed, category_id, barangays_id, cp_number, complaints_person, info_id, image_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$complaint_name, $complaints, $date_filed, $category_id, $barangays_id, $cp_number, $complaints_person, $info_id, $image_id, 'Unresolved']);

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
    <link rel="stylesheet" href="style.css">
</head>
<body>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">Excel</a>
            <!-- Button to toggle sidebar visibility -->
            <button class="navbar-toggler" type="button" onclick="toggleSidebar()">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>



    <!-- Page Content -->
 

    <div  style="margin-top: 3rem;" class="sidebar bg-dark" id="sidebar">
        <!-- Toggle button inside sidebar -->
        <button class="sidebar-toggler" type="button" onclick="toggleSidebar()">
        <i class="bi bi-grid-fill large-icon"></i><span class="nav-text menu-icon-text">Menu</span>
        </button>

        <!-- User Information -->
        <div class="user-info px-3 py-2 text-center">
            <!-- Your PHP session-based content -->
            <?php
            if (isset($_SESSION['pic_data'])) {
                $pic_data = $_SESSION['pic_data'];
                echo "<img class='profile' src='$pic_data' alt='Profile Picture'>";
            }
            ?>
            <p class='white-text'> <?php echo $_SESSION['accountType']; ?></p>
            <h5 class='white-text'>User Information</h5>
            <p class='white-text'><?php echo $email; ?></p>
            <p class='white-text'><?php echo "$firstName $middleName $lastName $extensionName"; ?></p>
        </div>

        <!-- Menu items -->
        <div class="menu-header">
            <h4 class="white-text">Menu</h4>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item menu-item">
                <a class="nav-link active" href="index.php"><i class="bi bi-house-door-fill"></i><span class="nav-text">Complaints</span></a>
            </li>
            <li class="nav-item menu-item">
                <a class="nav-link" href="complainants_logs.php"><i class="bi bi-journal-text"></i><span class="nav-text">Complaints Logs</span></a>
            </li>
            <li class="nav-item menu-item">
                <a class="nav-link" href=""><i class="bi bi-person-check-fill"></i><span class="nav-text">Complaints Responder</span></a>
            </li>
        </ul>

        <!-- Logout Form -->
        <form action="logout.php" method="post" id="logoutForm">
            <div class="logout-btn">
                <button type="button" class="btn btn-danger btn-sm" onclick="confirmLogout()">
                    <i class="bi bi-box-arrow-left"></i><span class="nav-text">Logout</span>
                </button>
            </div>
        </form>

        
    </div>
   <div class="content">
        <h1>Submit a Complaint</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" onsubmit="return onSubmitForm();">
        
        <div class="form-group row">
                <div class="col-md-6">
                    <label for="complaint_name">Complaint Name:</label>
                    <p> <?php echo "$firstName $middleName $lastName $extensionName"; ?></p>
                </div>
                <div class="col-md-6">
                    <label for="cp_number">Contact Number:</label>
                    <input type="text" id="cp_number" name="cp_number" class="form-control" required>
                </div>
            </div>

            <div class="form-group row">
    <div class="col-md-6">
    <label for="complaint_name">Barangay </label>
    <?php 
    include 'dbconn.php';
// Debugging output to check $barangayyy value


try {
    // Prepare statement to fetch barangay_name based on barangays_id
    $stmt = $pdo->prepare("SELECT barangay_name FROM tbl_users_barangay WHERE barangays_id = ?");
    $stmt->execute([$barangay]); // Use parameter binding to safely insert variable
    $barangay = $stmt->fetchColumn();

    // Check if a result was fetched
    if ($barangay) {
        echo "<p>" . htmlspecialchars($barangay) . "</p>";
    } else {
        echo "<p>No barangay found.</p>";
    }
} catch (PDOException $e) {
    echo "Error fetching barangay name: " . $e->getMessage();
}
?>


    
    </div>
    <div class="col-md-6">
        <label for="complaints_person">Complaints Person:</label>
        <input type="text" id="complaints_person" name="complaints_person" class="form-control" required>
    </div>
</div>


            <div class="form-group row">
                <div class="col-md-6">
                    <label for="age">Age:</label>
                    <input type="number" id="age" name="age" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender" class="form-control" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-12">
                    <label for="complaints">Complaint Description:</label>
                    <textarea id="complaints" name="complaints" class="form-control" required></textarea>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-12">
                    <label for="category">Category:</label>
                    <select id="category" name="category" class="form-control" required>
                        <option value="Noise Complaints">Noise Complaints</option>
                        <option value="Sanitation and Cleanliness">Sanitation and Cleanliness</option>
                        <option value="Disputes between Neighbors">Disputes between Neighbors</option>
                        <option value="Traffic and Parking">Traffic and Parking</option>
                        <option value="Public Safety">Public Safety</option>
                        <option value="Zoning Violations">Zoning Violations</option>
                        <option value="Public Utilities">Public Utilities</option>
                        <option value="Public Health">Public Health</option>
                        <option value="Environmental Issues">Environmental Issues</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-12">
                    <label for="image">Upload Image:</label>
                    <input type="file" id="image" name="image" class="form-control">
                </div>
            </div>

            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    
    <script src="script.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
 
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
            window.location.href = "login.php?logout=<?php echo $_SESSION['user_id']; ?>";
        }
    });
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
            window.location.href = "login.php?logout=<?php echo $_SESSION['user_id']; ?>";
        }
    });
}

    </script>
    
</body>
</html>
