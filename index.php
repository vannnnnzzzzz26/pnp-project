<?php
session_start(); // Start session at the very top

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    include 'dbconn.php'; // Include your database connection

    try {
        // Fetch form data
        $complaint_name = $_POST['complaint_name'];
        $complaints = $_POST['complaints'];
        $category = $_POST['category'];
        $barangay = $_POST['barangay'];
        $cp_number = $_POST['cp_number'];
        $complaints_person = $_POST['complaints_person'];
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
        $stmt = $pdo->prepare("SELECT barangay_id FROM tbl_baranggay WHERE barangay_name = ?");
        $stmt->execute([$barangay]);
        $barangay_id = $stmt->fetchColumn();

        if (!$barangay_id) {
            $stmt = $pdo->prepare("INSERT INTO tbl_baranggay (barangay_name) VALUES (?)");
            $stmt->execute([$barangay]);
            $barangay_id = $pdo->lastInsertId();
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

        // Insert complaint
        $stmt = $pdo->prepare("INSERT INTO tbl_complaints (complaint_name, complaints, date_filed, category_id, barangay_id, cp_number, complaints_person, image_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$complaint_name, $complaints, $date_filed, $category_id, $barangay_id, $cp_number, $complaints_person, $image_id]);

        // Commit transaction
        $pdo->commit();

        // Set a session variable to indicate successful submission
        $_SESSION['success'] = true;

        // Redirect to avoid resubmission on page refresh
        header("Location: index.php");
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">Excel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="content">
        <h1>Submit a Complaint</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" onsubmit="return onSubmitForm();">
            <div class="form-group row">
                <div class="col-md-6">
                    <label for="complaint_name">Complaint Name:</label>
                    <input type="text" id="complaint_name" name="complaint_name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="cp_number">Contact Number:</label>
                    <input type="text" id="cp_number" name="cp_number" class="form-control" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-6">
                    <label for="barangay">Barangay:</label>
                    <input type="text" id="barangay" name="barangay" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="complaints_person">Complaints Person:</label>
                    <input type="text" id="complaints_person" name="complaints_person" class="form-control" required>
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
                    <label for="image">Upload Image (Optional):</label>
                    <input type="file" id="image" name="image" class="form-control">
                </div>
            </div>

            <div class="form-group row">
                <div class="col-md-12">
                    <button type="submit" name="submit" class="btn btn-primary">Submit Complaint</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="px-3">Menu</h4>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="index.php">Complaints</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="complaints_logs.php">Complaints Logs</a>
            </li>
        </ul>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.all.min.js"></script>
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
    </script>
</body>
</html>
