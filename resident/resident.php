<?php
include '../connection/dbconn.php'; 
include '../resident/notifications.php';


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
            $image_path = '../uploads/' . $image_filename;
            $date_uploaded = date('Y-m-d H:i:s');

            if (!file_exists('uploads')) {
                mkdir('uploads', 0777, true); 
            }

            if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
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

        // Determine the responds value
        $responds = ($category === 'Other') ? 'pnp' : '';

        // Insert into tbl_complaints with status
        $stmt = $pdo->prepare("INSERT INTO tbl_complaints (complaint_name, complaints, date_filed, category_id, barangays_id, cp_number, complaints_person, info_id, image_id, status, responds) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$complaint_name, $complaints, $date_filed, $category_id, $barangays_id, $cp_number, $complaints_person, $info_id, $image_id, 'inprogress', $responds]);
        $complaint_id = $pdo->lastInsertId(); // Get the inserted complaint ID

        // Handle evidence upload if provided
        if (isset($_FILES['evidence']) && $_FILES['evidence']['error'][0] == UPLOAD_ERR_OK) {
            foreach ($_FILES['evidence']['tmp_name'] as $key => $tmp_name) {
                $evidence_filename = basename($_FILES['evidence']['name'][$key]);
                $evidence_path = '../uploads/' . $evidence_filename;
                $date_uploaded = date('Y-m-d H:i:s');

                if (move_uploaded_file($tmp_name, $evidence_path)) {
                    $stmt = $pdo->prepare("INSERT INTO tbl_evidence (complaints_id, evidence_path, date_uploaded) VALUES (?, ?, ?)");
                    $stmt->execute([$complaint_id, $evidence_path, $date_uploaded]);
                } else {
                    throw new Exception("Failed to upload evidence.");
                }
            }
        }

        $pdo->commit();

        $_SESSION['success'] = true;

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();

    } catch (PDOException $e) {
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

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../styles/style.css">

</head>
<body>

<style>
.popover-content {
    background-color: #343a40; /* Dark background to contrast with white */
    color: #ffffff; /* White text color */
    padding: 10px; /* Add some padding */
    border: 1px solid #495057; /* Optional: border for better visibility */
    border-radius: 5px; /* Optional: rounded corners */
    max-height: 300px; /* Ensure it doesn't grow too large */
    overflow-y: auto; /* Add vertical scroll if needed */
}

/* Adjust the arrow for the popover to ensure it points correctly */
.popover .popover-arrow {
    border-top-color: #343a40; /* Match the background color */
}




    </style>

<?php 

include '../includes/resident-nav.php';
include '../includes/resident-bar.php';
include '../includes/edit-profile.php';
?>

    <!-- Page Content -->
 

   
   <div class="content">
  <div class="card">
    <div class="card-body">
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" onsubmit="return onSubmitForm();">
        <div class="form-box">
          <!-- User Information -->
          <div class="row">
            <div class="col-lg-6 col-md-12 form-group">
              <label for="complaint_name">Complaint Name:</label>
              <p><?php echo htmlspecialchars("$firstName $middleName $lastName $extensionName"); ?></p>
            </div>
            <div class="col-lg-6 col-md-12 form-group">
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

          <!-- Complaint Information -->
          <div class="row">
            <div class="col-lg-6 col-md-12 form-group">
              <label for="complaints">Complaint:</label>
              <textarea id="complaints" name="complaints" class="form-control" required></textarea>
            </div>
            <div class="col-lg-6 col-md-12 form-group">
              <label for="category">Category:</label>
              <select id="category" name="category" class="form-control" required>
                <option value="">select</option>
                <option value="Unlawful Use of Means of Publication and Unlawful Utterances (Art. 154)">Unlawful Use of Means of Publication and Unlawful Utterances (Art. 154)</option>
    <option value="Alarms and Scandals (Art. 155)">Alarms and Scandals (Art. 155)</option>
    <option value="Using False Certificates (Art. 175)">Using False Certificates (Art. 175)</option>
    <option value="Using Fictitious Names and Concealing True Names (Art. 178)">Using Fictitious Names and Concealing True Names (Art. 178)</option>
    <option value="Illegal Use of Uniforms and Insignias (Art. 179)">Illegal Use of Uniforms and Insignias (Art. 179)</option>
    <option value="Physical Injuries Inflicted in a Tumultuous Affray (Art. 252)">Physical Injuries Inflicted in a Tumultuous Affray (Art. 252)</option>
    <option value="Giving Assistance to Consummated Suicide (Art. 253)">Giving Assistance to Consummated Suicide (Art. 253)</option>
    <option value="Responsibility of Participants in a Duel if only Physical Injuries are Inflicted or No Physical Injuries have been Inflicted (Art. 260)">Responsibility of Participants in a Duel if only Physical Injuries are Inflicted or No Physical Injuries have been Inflicted (Art. 260)</option>
    <option value="Less serious physical injuries (Art. 265)">Less serious physical injuries (Art. 265)</option>
    <option value="Slight physical injuries and maltreatment (Art. 266)">Slight physical injuries and maltreatment (Art. 266)</option>
    <option value="Unlawful arrest (Art. 269)">Unlawful arrest (Art. 269)</option>
    <option value="Inducing a minor to abandon his/her home (Art. 271)">Inducing a minor to abandon his/her home (Art. 271)</option>
    <option value="Abandonment of a person in danger and abandonment of one’s own victim (Art. 275)">Abandonment of a person in danger and abandonment of one’s own victim (Art. 275)</option>
    <option value="Abandoning a minor (a child under seven (7) years old) (Art. 276)">Abandoning a minor (a child under seven (7) years old) (Art. 276)</option>
    <option value="Abandonment of a minor by persons entrusted with his/her custody; indifference of parents (Art. 277)">Abandonment of a minor by persons entrusted with his/her custody; indifference of parents (Art. 277)</option>
    <option value="Qualified trespass to dwelling (without the use of violence and intimidation) (Art. 280)">Qualified trespass to dwelling (without the use of violence and intimidation) (Art. 280)</option>
    <option value="Other forms of trespass (Art. 281)">Other forms of trespass (Art. 281)</option>
    <option value="Light threats (Art. 283)">Light threats (Art. 283)</option>
    <option value="Other light threats (Art. 285)">Other light threats (Art. 285)</option>
    <option value="Grave coercion (Art. 286)">Grave coercion (Art. 286)</option>
    <option value="Light coercion (Art. 287)">Light coercion (Art. 287)</option>
    <option value="Other similar coercions (compulsory purchase of merchandise and payment of wages by means of tokens) (Art. 288)">Other similar coercions (compulsory purchase of merchandise and payment of wages by means of tokens) (Art. 288)</option>
    <option value="Formation, maintenance and prohibition of combination of capital or labor through violence or threats (Art. 289)">Formation, maintenance and prohibition of combination of capital or labor through violence or threats (Art. 289)</option>
    <option value="Discovering secrets through seizure and correspondence (Art. 290)">Discovering secrets through seizure and correspondence (Art. 290)</option>
    <option value="Revealing secrets with abuse of authority (Art. 291)">Revealing secrets with abuse of authority (Art. 291)</option>
    <option value="Theft (if the value of the property stolen does not exceed Php50.00) (Art. 309)">Theft (if the value of the property stolen does not exceed Php50.00) (Art. 309)</option>
    <option value="Qualified theft (if the amount does not exceed Php500) (Art. 310)">Qualified theft (if the amount does not exceed Php500) (Art. 310)</option>
    <option value="Occupation of real property or usurpation of real rights in property (Art. 312)">Occupation of real property or usurpation of real rights in property (Art. 312)</option>
    <option value="Altering boundaries or landmarks (Art. 313)">Altering boundaries or landmarks (Art. 313)</option>
    <option value="Swindling or estafa (if the amount does not exceed Php200.00) (Art. 315)">Swindling or estafa (if the amount does not exceed Php200.00) (Art. 315)</option>
    <option value="Other forms of swindling (Art. 316)">Other forms of swindling (Art. 316)</option>
    <option value="Swindling a minor (Art. 317)">Swindling a minor (Art. 317)</option>
    <option value="Other deceits (Art. 318)">Other deceits (Art. 318)</option>
    <option value="Removal, sale or pledge of mortgaged property (Art. 319)">Removal, sale or pledge of mortgaged property (Art. 319)</option>
    <option value="Special cases of malicious mischief (if the value of the damaged property does not exceed Php1,000.00 Art. 328)">Special cases of malicious mischief (if the value of the damaged property does not exceed Php1,000.00 Art. 328)</option>
    <option value="Other mischiefs (if the value of the damaged property does not exceed Php1,000.00) (Art. 329)">Other mischiefs (if the value of the damaged property does not exceed Php1,000.00) (Art. 329)</option>
    <option value="Simple seduction (Art. 338)">Simple seduction (Art. 338)</option>
    <option value="Acts of lasciviousness with the consent of the offended party (Art. 339)">Acts of lasciviousness with the consent of the offended party (Art. 339)</option>
    <option value="Threatening to publish and offer to prevent such publication for compensation (Art. 356)">Threatening to publish and offer to prevent such publication for compensation (Art. 356)</option>
    <option value="Prohibiting publication of acts referred to in the course of official proceedings (Art. 357)">Prohibiting publication of acts referred to in the course of official proceedings (Art. 357)</option>
    <option value="Incriminating innocent persons (Art. 363)">Incriminating innocent persons (Art. 363)</option>
    <option value="Intriguing against honor (Art. 364)">Intriguing against honor (Art. 364)</option>
    <option value="Issuing checks without sufficient funds (B.P. 22)">Issuing checks without sufficient funds (B.P. 22)</option>
    <option value="Fencing of stolen properties if the property involved is not more than Php50.00 (P.D. 1612)">Fencing of stolen properties if the property involved is not more than Php50.00 (P.D. 1612)</option>


                <option value="Other">Other</option>
                                         
    


              </select>
              <div id="other-category-group" style="display: none;">
                <label for="other-category">Please specify:</label>
                <input type="text" id="other-category" name="other-category" class="form-control" placeholder="Specify your complaint" />
              </div>
            </div>
          </div>

          <!-- Script for Other Category Toggle -->
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

          <!-- Evidence and Complained Person -->
          <div class="row">
            <div class="col-lg-6 col-md-12 form-group">
              <label for="evidence">Upload Evidence:</label>
              <input type="file" id="evidence" name="evidence[]" class="form-control" multiple required>
            </div>
            <div class="col-lg-6 col-md-12 form-group">
              <label for="complaints_person">Person Complained Against:</label>
              <input type="text" id="complaints_person" name="complaints_person" class="form-control" required>
            </div>
          </div>

          <!-- Contact and Birth Information -->
          <div class="row">
            <div class="col-lg-6 col-md-12 form-group">
              <label for="cp_number">CP Number:</label>
              <input type="text" id="cp_number" name="cp_number" class="form-control" required>
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
                <option value="Divorced">Divorced</option>
                <option value="Widowed">Widowed</option>
              </select>
            </div>
          </div>

          <!-- Educational Background and ID -->
          <div class="row">
            <div class="col-lg-6 col-md-12 form-group">
              <label for="educational_background">Educational Background:</label>
              <select id="educational_background" name="educational_background" class="form-control" required>
                <option value="Primary">Primary</option>
                <option value="Secondary">Secondary</option>
                <option value="Tertiary">Tertiary</option>
              </select>
            </div>
            <div class="col-lg-6 col-md-12 form-group">
              <label for="image">ID:</label>
              <input type="file" id="image" name="image" class="form-control">
            </div>
          </div>

          <!-- Submit Button -->
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="../scripts/script.js"></script>
  
    <!-- Include jQuery and Bootstrap JavaScript -->


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
                window.location.href = " ../reg/login.php?logout=<?php echo $_SESSION['user_id']; ?>";
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




    document.addEventListener("DOMContentLoaded", function () {
    const notificationButton = document.getElementById('notificationButton');
    const modalBody = document.getElementById('notificationModalBody');

    // Function to fetch notifications
    function fetchNotifications() {
        return fetch('notifications.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const notificationCount = data.notifications.length;
                const notificationCountBadge = document.getElementById("notificationCount");

                // Update notification count
                if (notificationCount > 0) {
                    notificationCountBadge.textContent = notificationCount;
                    notificationCountBadge.classList.remove("d-none");
                } else {
                    notificationCountBadge.textContent = "0";
                    notificationCountBadge.classList.add("d-none");
                }

                // Populate notifications
                let notificationListHtml = '';
                if (notificationCount > 0) {
                    data.notifications.forEach(notification => {
                        notificationListHtml += `
                                  <div class="dropdown-item" 
     data-id="${notification.id}" 
     data-status="${notification.status}" 
     data-hearing-type="${notification.hearing_type}" 
     data-hearing-date="${notification.hearing_date}" 
     data-hearing-time="${notification.hearing_time}" 
     data-hearing-status="${notification.hearing_status}">
    Status: ${notification.status}<br>
    Hearing Type: ${notification.hearing_type}<br>
    Date: ${notification.hearing_date}<br>
    Time: ${notification.hearing_time}<br>
    Hearing Status: ${notification.hearing_status}
</div>

                        `;
                    });
                } else {
                    // If no new notifications
                    notificationListHtml = '<div class="dropdown-item text-center">No new notifications</div>';
                }

                // Update the popover content
                const popoverInstance = bootstrap.Popover.getInstance(notificationButton);
                if (popoverInstance) {
                    popoverInstance.setContent({
                        '.popover-body': notificationListHtml
                    });
                } else {
                    // Initialize the popover
                    new bootstrap.Popover(notificationButton, {
                        html: true,
                        content: function () {
                            return `<div class="popover-content">${notificationListHtml}</div>`;
                        },
                        container: 'body'
                    });
                }

                // Attach click event listeners to notifications
                document.querySelectorAll('.popover-content .dropdown-item').forEach(item => {
                    item.addEventListener('click', function () {
                        openNotificationDetail(this);
                    });
                });
            } else {
                console.error("Failed to fetch notifications");
            }
        })
        .catch(error => {
            console.error("Error fetching notifications:", error);
        });
    }

    // Function to open notification detail in a modal
    

    // Initialize or refresh the popover when needed
    fetchNotifications();

    // Mark notifications as read when the popover is shown
    notificationButton.addEventListener('shown.bs.popover', function () {
        markNotificationsAsRead();
    });

    function markNotificationsAsRead() {
        // Make an AJAX request to the server to mark notifications as read
        fetch('notifications.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ markAsRead: true })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Handle the response, e.g., update the UI to reflect read notifications
                const badge = document.querySelector(".badge.bg-danger");
                if (badge) {
                    badge.classList.add("d-none");
                }
            } else {
                console.error("Failed to mark notifications as read");
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    }
});


            
        </script>
    
</body>
</html>
