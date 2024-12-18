<?php
include '../connection/dbconn.php'; 
include '../resident/notifications.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: ../reg/login.php");
    exit();
}

$firstName = $_SESSION['first_name'];
$middleName = $_SESSION['middle_name'];
$lastName = $_SESSION['last_name'];
$extensionName = isset($_SESSION['extension_name']) ? $_SESSION['extension_name'] : '';
$cp_number = isset($_SESSION['cp_number']) ? $_SESSION['cp_number'] : '';
$barangay = isset($_SESSION['barangay_name']) ? $_SESSION['barangay_name'] : '';
$pic_data = isset($_SESSION['pic_data']) ? $_SESSION['pic_data'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Collect complaint form data
        $complaint_name = "$firstName $middleName $lastName $extensionName";
        $complaints = isset($_POST['complaints']) ? htmlspecialchars($_POST['complaints']) : '';
        $category = isset($_POST['category']) ? htmlspecialchars($_POST['category']) : '';
        $complaints_person = isset($_POST['complaints_person']) ? htmlspecialchars($_POST['complaints_person']) : '';
        $date_filed = date('Y-m-d H:i:s');

        // Collect data for "ano, saan, kailan, paano, bakit"
        $ano = isset($_POST['ano']) ? htmlspecialchars($_POST['ano']) : '';
        $saan = isset($_POST['saan']) ? htmlspecialchars($_POST['saan']) : '';
        
        // Get 'kailan' input and convert it to database-friendly datetime format
        $kailan = isset($_POST['kailan']) ? htmlspecialchars($_POST['kailan']) : '';
        $kailan_db_format = date('Y-m-d H:i:s', strtotime($kailan)); // Store as 'Y-m-d H:i:s'

        $paano = isset($_POST['paano']) ? htmlspecialchars($_POST['paano']) : '';
        $bakit = isset($_POST['bakit']) ? htmlspecialchars($_POST['bakit']) : '';

        $pdo->beginTransaction();

        // Check category and insert new category if necessary
        $stmt = $pdo->prepare("SELECT category_id FROM tbl_complaintcategories WHERE complaints_category = ?");
        $stmt->execute([$category]);
        $category_id = $stmt->fetchColumn();

        if (!$category_id) {
            $stmt = $pdo->prepare("INSERT INTO tbl_complaintcategories (complaints_category) VALUES (?)");
            $stmt->execute([$category]);
            $category_id = $pdo->lastInsertId();
        }

     // Validate barangay
$stmt = $pdo->prepare("SELECT user_id FROM tbl_users WHERE barangay_name = ?");
$stmt->execute([$barangay]);
$user_id = $stmt->fetchColumn();

if (!$user_id) {
    throw new Exception("Invalid Barangay Name: " . $barangay);
}

        // Handle category
        $other_category = isset($_POST['other-category']) ? htmlspecialchars($_POST['other-category']) : '';
        if ($category === 'Other' && !empty($other_category)) {
            $category = $other_category; 
        }

        // Set status and response values based on category
        $status = ($category === 'Other') ? 'pnp' : 'inprogress';
        $responds = ($category === 'Other') ? 'pnp' : '';

        // Insert into tbl_complaints
        $user_id = $_SESSION['user_id']; // Retrieve user_id from session

        try {
            $stmt = $pdo->prepare("INSERT INTO tbl_users_barangay (saan) VALUES (?)");
            $stmt->execute([$saan]);
            $barangays_id = $pdo->lastInsertId();
            echo "Inserted record with ID: " . $barangays_id;
        } catch (PDOException $e) {
            die("Error inserting record: " . $e->getMessage());
        }
      

        $stmt = $pdo->prepare("INSERT INTO tbl_complaints (complaint_name, complaints, date_filed, category_id, barangays_id, complaints_person, status, responds, ano,  kailan, paano, bakit, user_id) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$complaint_name, $complaints, $date_filed, $category_id, $barangays_id, $complaints_person, $status, $responds, $ano, $kailan_db_format, $paano, $bakit, $user_id]);

        $complaint_id = $pdo->lastInsertId();

        // Handle evidence upload
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
        header("Location: complainants_logs.php ");
        exit();


    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "<div class='alert alert-danger' role='alert'>Error: " . $e->getMessage() . "</div>";
    } catch (Exception $e) {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $e->getMessage() . "</div>";
    }
}

// Example retrieval of the 'kailan' field for displaying with AM/PM format
if (isset($complaint_id)) {
    $stmt = $pdo->prepare("SELECT kailan FROM tbl_complaints WHERE complaints_id = ?");
    $stmt->execute([$complaint_id]);
    $kailan_from_db = $stmt->fetchColumn();
    
    // Convert stored 'kailan' to AM/PM format for display

    $kailan = isset($_POST['kailan']) ? htmlspecialchars($_POST['kailan']) : '';

    // Convert the datetime-local format to MySQL datetime format
    $kailan_db_format = date('Y-m-d H:i:s', strtotime($kailan));

    // If you want to display AM/PM later, you can format it
    $kailan_am_pm = date('F j, Y, g:i A', strtotime($kailan));

    // Validate the conversion
    if (!$kailan_db_format) {
        throw new Exception("Invalid date format for 'kailan'.");
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
    <link rel="stylesheet" href="../styles/style.css">

</head>
<body >

<style>
.popover-content {
    background-color: whitesmoke; 
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

.sidebar-toggler {
    display: flex;
    align-items: center;
    padding: 10px;
    background-color: transparent; /* Changed from #082759 to transparent */
    border: none;
    cursor: pointer;
    color: white;
    text-align: left;
    width: auto; /* Adjust width automatically */
}

.navbar{
  background-color: #082759;

}

.navbar-brand{
color: whitesmoke;
}

.sidebar.collapsed {
    width: 80px; /* Width when collapsed */
}
.content {
    margin-left: 250px; /* Same as initial width of the sidebar */
    transition: margin-left 0.3s ease;
    padding: 20px; /* Adjust padding as needed */
    width: 80%; /* Calculate remaining width */
}
.content.expanded {
    margin-left: 75px; /* Adjust content margin when sidebar expands */
}



label {
    font-weight: bold;
    margin-bottom: 5px;
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
              <label for="complaint_name">Complainant:</label>
              <p><?php echo htmlspecialchars("$firstName $middleName $lastName $extensionName"); ?></p>
            </div>
            <div class="col-lg-6 col-md-12 form-group">
              <label for="barangay">Barangay:</label>
              <?php 
include '../connection/dbconn.php'; 

try {
    // Example: Make sure $userId or similar variable is initialized correctly
    $userId = $_SESSION['user_id'] ?? null; // Replace with your actual logic

    if ($userId) {
        $stmt = $pdo->prepare("SELECT barangay_name FROM tbl_users WHERE user_id = ?");
        $stmt->execute([$userId]);
        $barangayName = $stmt->fetchColumn();

        if ($barangayName) {
            echo "<p>" . htmlspecialchars(trim($barangayName)) . "</p>";
        } else {
            echo "<p>No barangay found.</p>";
        }
    } else {
        echo "<p>User ID is not set.</p>";
    }
} catch (PDOException $e) {
    error_log("Error fetching barangay name: " . $e->getMessage()); // Log the error for debugging
    echo "<p>An error occurred. Please try again later.</p>";
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
<?php include 'category.php';
?>
           <button id="openModalButton" class="btn btn-primary">Viewn Category</button>
<br>
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



<div class="row">
    <div class="col-lg-6 col-md-12 form-group">
        <label for="ano">Ano (What):</label>
        <input type="text" name="ano" id="ano" class="form-control" required>
    </div>

    <div class="col-lg-6 col-md-12 form-group">
        <label for="saan">Saan (Where):</label>
    

        <select id="saan" name="saan" class="form-select" required>
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

    <div class="col-lg-6 col-md-12 form-group">
        <label for="kailan">Kailan (When):</label>
        <input type="datetime-local" name="kailan" id="kailan" class="form-control" required>
    </div>

    <div class="col-lg-6 col-md-12 form-group">
        <label for="paano">Paano (How):</label>
        <textarea name="paano" id="paano" class="form-control" required></textarea>
    </div>

    <div class="col-lg-6 col-md-12 form-group">
        <label for="bakit">Bakit (Why):</label>
        <textarea name="bakit" id="bakit" class="form-control" required></textarea>
    </div>
</div>



          <!-- Evidence and Complained Person -->
          <div class="row">
            <div class="col-lg-6 col-md-12 form-group">
              <label for="evidence">Upload Evidence:</label>
              <input type="file" id="evidence" name="evidence[]" class="form-control" multiple required>
            </div>
            <div class="col-lg-6 col-md-12 form-group">
              <label for="complaints_person">Person Involved :</label>
              <input type="text" id="complaints_person" name="complaints_person" class="form-control" required>
            </div>
          </div>

          <!-- Contact and Birth Information -->
 

          </div>

          



         

          <!-- Educational Background and ID -->
     
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

$(document).ready(function() {
  // Open the modal when a button is clicked
  $('#openModalButton').click(function() {
    $('#categoryModal').modal('show');
  });

  // Close the modal  
  $('#categoryModal').on('hidden.bs.modal', function () {
    // You can reset any content here if necessary
    console.log('Modal closed');
  });

  // Optional: Any additional logic when the modal is shown
  $('#categoryModal').on('shown.bs.modal', function () { 
    console.log('Modal is open');
  });
});





        // Check if the session variable is set and show SweetAlert
        <?php 
        
        if (isset($_SESSION['success'])): ?>
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Your complaint has been submitted',
                showConfirmButton: false,
                timer: 1500
            });
          
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        function onSubmitForm() {
    var imageField = document.getElementById('image');
    if (!imageField || imageField.value.trim() === '') {
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



            document.getElementById('category').addEventListener('change', function() {
                var otherCategoryGroup = document.getElementById('other-category-group');
                if (this.value === 'Other') {
                    otherCategoryGroup.style.display = 'block';
                } else {
                    otherCategoryGroup.style.display = 'none';
                    document.getElementById('other-category').value = ''; // Clear the input field
                }
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
        .then(response => {
            if (!response.ok) {
                // If no content (204) or other error, handle it
                return {};
            }
            return response.json();
        })
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
                                data-id="${notification.complaints_id}" 
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
                                <hr>
                            </div>`;
                    });
                } else {
                    notificationListHtml = '<div class="dropdown-item text-center">No new notifications</div>';
                }

                // Update the popover content
                const popoverInstance = bootstrap.Popover.getInstance(notificationButton);
                if (popoverInstance) {
                    popoverInstance.setContent({
                        '.popover-body': notificationListHtml
                    });
                } else {
                    new bootstrap.Popover(notificationButton, {
                        html: true,
                        content: function () {
                            return `<div class="popover-content">${notificationListHtml}</div>`;
                        },
                        container: 'body'
                    });
                }
            } else {
                console.error("Failed to fetch notifications");
            }
        })
        .catch(error => {
            console.error("Error fetching notifications:", error);
        });
    }

    // Initialize or refresh the popover when needed
    fetchNotifications();

    // Mark notifications as read when the popover is shown
    notificationButton.addEventListener('shown.bs.popover', function () {
        markNotificationsAsRead();
    });

    function markNotificationsAsRead() {
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





<?php if (isset($_SESSION['alert_message'])): ?>
        Swal.fire({
            icon: '<?= $_SESSION['alert_type'] ?>',
            title: '<?= $_SESSION['alert_message'] ?>'
        });
        <?php unset($_SESSION['alert_message'], $_SESSION['alert_type']); endif; ?>

            
      

        </script>
    
</body>
</html>