<?php
// Start the session at the beginning
session_start();

// Include your database connection file
include_once 'dbconn.php';

// Fetch barangay name if not already set in session
if (!isset($_SESSION['barangay_name']) && isset($_SESSION['barangays_id'])) {
    $stmt = $pdo->prepare("SELECT barangay_name FROM tbl_users_barangay WHERE barangays_id = ?");
    $stmt->execute([$_SESSION['barangays_id']]);
    $_SESSION['barangay_name'] = $stmt->fetchColumn();
}

$firstName = $_SESSION['first_name'];
$middleName = $_SESSION['middle_name'];
$lastName = $_SESSION['last_name'];
$extensionName = $_SESSION['extension_name'] ?? '';
$email = $_SESSION['email'] ?? '';
$barangay_name = $_SESSION['barangay_name'] ?? '';
$pic_data = $_SESSION['pic_data'] ?? '';

$results_per_page = 10; 

// Determine current page
$page = !isset($_GET['page']) || !is_numeric($_GET['page']) || $_GET['page'] <= 0 ? 1 : $_GET['page'];

// Calculate the SQL LIMIT starting number for the results on the displaying page
$start_from = ($page - 1) * $results_per_page;

function displayComplaints($pdo, $start_from, $results_per_page) {
    try {
        $barangay_name = $_SESSION['barangay_name'] ?? '';

        $stmt = $pdo->prepare("
            SELECT c.*, b.barangay_name, cc.complaints_category, i.gender, i.place_of_birth, i.age, i.educational_background, i.civil_status,
                   e.evidence_path, e.date_uploaded
            FROM tbl_complaints c
            JOIN tbl_users_barangay b ON c.barangays_id = b.barangays_id
            JOIN tbl_complaintcategories cc ON c.category_id = cc.category_id
            JOIN tbl_info i ON c.info_id = i.info_id
            LEFT JOIN tbl_evidence e ON c.complaints_id = e.complaints_id
            WHERE c.status IN ('Approved') AND b.barangay_name = ?
            ORDER BY c.date_filed ASC
            LIMIT ?, ?
        ");
    
        $stmt->bindParam(1, $barangay_name, PDO::PARAM_STR);
        $stmt->bindParam(2, $start_from, PDO::PARAM_INT);
        $stmt->bindParam(3, $results_per_page, PDO::PARAM_INT);
        
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            echo "<tr><td colspan='3'>No complaints found.</td></tr>";
        } else {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $complaint_id = htmlspecialchars($row['complaints_id']);
                $complaint_name = htmlspecialchars($row['complaint_name']);
                $complaint_description = htmlspecialchars($row['complaints']);
                $complaint_category = htmlspecialchars($row['complaints_category']);
                $complaint_barangay = htmlspecialchars($row['barangay_name']);
                $complaint_contact = htmlspecialchars($row['cp_number']);
                $complaint_person = htmlspecialchars($row['complaints_person']);
                $complaint_gender = htmlspecialchars($row['gender']);
                $complaint_birth_place = htmlspecialchars($row['place_of_birth']);
                $complaint_age = htmlspecialchars($row['age']);
                $complaint_education = htmlspecialchars($row['educational_background']);
                $complaint_civil_status = htmlspecialchars($row['civil_status']);
                $complaint_evidence = htmlspecialchars($row['evidence_path']);
                $complaint_date_filed = htmlspecialchars($row['date_filed']);
                $complaint_status = htmlspecialchars($row['status']);

                echo "<tr>";
                echo "<td>{$complaint_id}</td>";
                echo "<td>{$complaint_name}</td>";
                echo "<td><button type='button' class='btn btn-primary view-details-btn' 
                            data-id='{$complaint_id}' data-name='{$complaint_name}' data-description='{$complaint_description}' 
                            data-category='{$complaint_category}' data-barangay='{$complaint_barangay}' 
                            data-contact='{$complaint_contact}' data-person='{$complaint_person}' 
                            data-gender='{$complaint_gender}' data-birth_place='{$complaint_birth_place}' 
                            data-age='{$complaint_age}' data-education='{$complaint_education}' 
                            data-civil_status='{$complaint_civil_status}' data-evidence='{$complaint_evidence}' 
                            data-date_filed='{$complaint_date_filed}' data-status='{$complaint_status}'
                            data-bs-toggle='modal' data-bs-target='#complaintModal'>View Details</button></td>";
                echo "</tr>";
            }
        }
    } catch (PDOException $e) {
        echo "<tr><td colspan='3'>Error fetching complaints: " . $e->getMessage() . "</td></tr>";
    }
}


// Handle status update submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $complaint_id = $_POST['complaint_id'];
    $new_status = $_POST['new_status'];

    try {
        $responds = '';
        if ($new_status === 'settled_in_barangay') {
            $responds = 'barangay';
        } elseif ($new_status === 'pnp') {
            $responds = 'pnp';
        }

        $stmt = $pdo->prepare("UPDATE tbl_complaints SET status = ?, responds = ? WHERE complaints_id = ?");
        $stmt->execute([$new_status, $responds, $complaint_id]);

        header("Location: {$_SERVER['PHP_SELF']}?page={$page}");
        exit();
    } catch (PDOException $e) {
        echo "Error updating status: " . $e->getMessage();
    }
}




// Pagination
$stmt = $pdo->prepare("SELECT COUNT(*) AS total FROM tbl_complaints c JOIN tbl_users_barangay b ON c.barangays_id = b.barangays_id WHERE (c.status = 'Approved' OR c.status = 'unresolved') AND b.barangay_name = ?");
$stmt->execute([$barangay_name]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$total_pages = ceil($row['total'] / $results_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uploaded Complaints</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">Excel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Add Navbar items if needed -->
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="content">
        <div class="container">
            <h2 class="mt-3 mb-4">Uploaded Complaints</h2>
          
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php displayComplaints($pdo, $start_from, $results_per_page); ?>
                </tbody>
            </table>

    </div>

<!-- Complaint Modal -->
<div class="modal fade" id="viewComplaintModal" tabindex="-1" aria-labelledby="viewComplaintModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewComplaintModalLabel">Complaint Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Existing fields -->
                <p><strong>Name:</strong> <span id="modal-name"></span></p>
                <p><strong>Date Filed:</strong> <span id="modal-date_filed"></span></p>
                <p><strong>Status:</strong> <span id="modal-status"></span></p>

                <!-- Hearing Section -->
                <div id="hearingSection" style="display: none;">
                    <h5 class="mt-4">Set Hearing Date and Time</h5>
                    <form id="hearingForm">
                        <div class="mb-3">
                            <label for="hearing-date" class="form-label">Hearing Date</label>
                            <input type="date" class="form-control" id="hearing-date" name="hearing_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="hearing-time" class="form-label">Hearing Time</label>
                            <input type="time" class="form-control" id="hearing-time" name="hearing_time" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Set Hearing</button>
                    </form>
                </div>

                <!-- Additional fields as needed -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



            <nav>
                <ul class="pagination justify-content-center">
                    <?php
                    $pagination_range = 2; // Range of pages to display before and after the current page
                    for ($i = max(1, $page - $pagination_range); $i <= min($page + $pagination_range, $total_pages); $i++) {
                        $active = $i == $page ? 'active' : '';
                        echo "<li class='page-item {$active}'><a class='page-link' href='barangay-responder.php?page={$i}'>{$i}</a></li>";
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </div>

    <!-- Complaint Modal -->
  <!-- Complaint Modal -->
  <div class="modal fade" id="complaintModal" tabindex="-1" aria-labelledby="complaintModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="complaintModalLabel">Complaint Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Name:</strong> <span id="modal-name"></span></p>
                <p><strong>Description:</strong> <span id="modal-description"></span></p>
                <p><strong>Category:</strong> <span id="modal-category"></span></p>
                <p><strong>Barangay:</strong> <span id="modal-barangay"></span></p>
                <p><strong>Contact:</strong> <span id="modal-contact"></span></p>
                <p><strong>Person:</strong> <span id="modal-person"></span></p>
                <p><strong>Gender:</strong> <span id="modal-gender"></span></p>
                <p><strong>Birth Place:</strong> <span id="modal-birth_place"></span></p>
                <p><strong>Age:</strong> <span id="modal-age"></span></p>
                <p><strong>Education:</strong> <span id="modal-education"></span></p>
                <p><strong>Civil Status:</strong> <span id="modal-civil_status"></span></p>
                <p><strong>Evidence:</strong> 
                    <span id="modal-evidence">
                        <img id="modal-evidence-image" src="" alt="Evidence Image" style="display: none; max-width: 100%;">
                        <video id="modal-evidence-video" controls style="display: none; max-width: 100%;">
                            <source id="modal-evidence-video-source" src="" type="video/mp4">
                        </video>
                    </span>
                </p>
                <p><strong>Date Filed:</strong> <span id="modal-date_filed"></span></p>
                <p><strong>Status:</strong> <span id="modal-status"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="moveToPnpBtn">Move to PNP</button>
                <button type="button" class="btn btn-secondary" id="settleInBarangayBtn">Settle in Barangay</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>

                
      
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#viewComplaintModal" id="setHearingBtn">
                Set Hearing
            </button>
        
            </div>
        </div>
    </div>
</div>


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
        <h5 class="white-text"><?php echo "$firstName $middleName $lastName $extensionName"; ?></h5>
        <p class="user-email white-text"><?php echo "$email"; ?></p>
    </div>
    
    <!-- Sidebar Links -->
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link" href="manage-complaints.php">
                <i class="bi bi-file-earmark-text large-icon"></i><span class="nav-text">Complaints</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="barangay-responder.php">
                <i class="bi bi-file-earmark-text large-icon"></i><span class="nav-text">Complaints Logs</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="barangaylogs.php">
            <i class="bi bi-check-square-fill large-icon"></i><span class="nav-text">Complaints Responder</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="barangay-official.php">
                <i class="bi bi-person large-icon"></i><span class="nav-text">Barangay Official</span>
            </a>
        </li>
     
    </ul>
    
    <!-- Logout -->
               <!-- Logout Form -->
        <form action="logout.php" method="post" id="logoutForm">
            <div class="logout-btn">
                <button type="button" class="btn btn-danger btn-sm" onclick="confirmLogout()">
                    <i class="bi bi-box-arrow-left"></i><span class="nav-text">Logout</span>
                </button>
            </div>
        </form>
</div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>

    <script src="script.js"></script>



<!-- Bootstrap JavaScript link -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>

    <script>
        // JavaScript for modal functionality
        document.addEventListener('DOMContentLoaded', function() {
    const viewButtons = document.querySelectorAll('.view-details-btn');
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Populate modal with data
            document.getElementById('modal-name').textContent = this.dataset.complaint_name;
            document.getElementById('modal-description').textContent = this.dataset.description;
            document.getElementById('modal-category').textContent = this.dataset.category;
            document.getElementById('modal-barangay').textContent = this.dataset.barangay;
            document.getElementById('modal-contact').textContent = this.dataset.contact;
            document.getElementById('modal-person').textContent = this.dataset.person;
            document.getElementById('modal-gender').textContent = this.dataset.gender;
            document.getElementById('modal-birth_place').textContent = this.dataset.birth_place;
            document.getElementById('modal-age').textContent = this.dataset.age;
            document.getElementById('modal-education').textContent = this.dataset.education;
            document.getElementById('modal-civil_status').textContent = this.dataset.civil_status;
            document.getElementById('modal-date_filed').textContent = this.dataset.date_filed;
            document.getElementById('modal-status').textContent = this.dataset.status;

            // Handle evidence display
            const evidencePath = this.dataset.evidence;
            const evidenceImage = document.getElementById('modal-evidence-image');
            const evidenceVideo = document.getElementById('modal-evidence-video');
            const evidenceVideoSource = document.getElementById('modal-evidence-video-source');

            evidenceImage.style.display = 'none';
            evidenceVideo.style.display = 'none';

            if (evidencePath) {
                const fileExtension = evidencePath.split('.').pop().toLowerCase();
                if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                    evidenceImage.src = evidencePath;
                    evidenceImage.style.display = 'block';
                } else if (['mp4', 'webm', 'ogg'].includes(fileExtension)) {
                    evidenceVideoSource.src = evidencePath;
                    evidenceVideo.load();
                    evidenceVideo.style.display = 'block';
                }
            }

            // Store the complaint ID in the modal for use later
            document.getElementById('complaintModal').setAttribute('data-complaint-id', this.dataset.id);
        });
    });

    // Handle Move to PNP button click
    document.getElementById('moveToPnpBtn').addEventListener('click', function() {
        updateComplaintStatus('pnp');
    });

    // Handle Settle in Barangay button click
    document.getElementById('settleInBarangayBtn').addEventListener('click', function() {
        updateComplaintStatus('settled_in_barangay');
    });

    function updateComplaintStatus(newStatus) {
        const complaintId = document.getElementById('complaintModal').getAttribute('data-complaint-id');
        
        fetch('update_complaint_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `complaint_id=${complaintId}&new_status=${newStatus}`
        })
        .then(response => response.text())
        .then(result => {
            alert(result); // Display success or error message
            window.location.reload(); // Reload the page to reflect changes
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
});



document.addEventListener('DOMContentLoaded', function() {
    const viewButtons = document.querySelectorAll('.view-details-btn');
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Populate modal with data
            document.getElementById('modal-name').textContent = this.dataset.name;
            // Populate other fields...

            // Show hearing section if the complaint status is 'Approved'
            const status = this.dataset.status;
            if (status === 'Approved') {
                document.getElementById('hearingSection').style.display = 'block';
            } else {
                document.getElementById('hearingSection').style.display = 'none';
            }

            // Store the complaint ID in the modal for use later
            document.getElementById('viewComplaintModal').setAttribute('data-complaint-id', this.dataset.id);
        });
    });

    // Handle Hearing Form Submission
    document.getElementById('hearingForm').addEventListener('submit', function(event) {
        event.preventDefault();
        setHearingDate();
    });

    function setHearingDate() {
        const complaintId = document.getElementById('viewComplaintModal').getAttribute('data-complaint-id');
        const hearingDate = document.getElementById('hearing-date').value;
        const hearingTime = document.getElementById('hearing-time').value;

        fetch('set_hearing_date.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `complaint_id=${complaintId}&hearing_date=${hearingDate}&hearing_time=${hearingTime}`
        })
        .then(response => response.text())
        .then(result => {
            alert(result); // Display success or error message
            window.location.reload(); // Reload the page to reflect changes
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
});



    </script>
</body>
</html>
