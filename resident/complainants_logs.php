<?php
session_start();
include '../connection/dbconn.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize variables from session data
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$firstName = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : '';
$middleName = isset($_SESSION['middle_name']) ? $_SESSION['middle_name'] : '';
$lastName = isset($_SESSION['last_name']) ? $_SESSION['last_name'] : '';
$extensionName = isset($_SESSION['extension_name']) ? $_SESSION['extension_name'] : '';

// Construct user's full name
$userFullName = $firstName . ' ' . $middleName . ' ' . $lastName;
if (!empty($extensionName)) {
    $userFullName .= ' ' . $extensionName;
}

try {
    // Fetch complaints and evidence for the logged-in user
    $stmt = $pdo->prepare("
        SELECT c.*, cc.complaints_category, b.barangay_name, i.*, e.evidence_path
        FROM tbl_complaints c
        LEFT JOIN tbl_complaintcategories cc ON c.category_id = cc.category_id
        LEFT JOIN tbl_users_barangay b ON c.barangays_id = b.barangays_id
        LEFT JOIN tbl_info i ON c.info_id = i.info_id
        LEFT JOIN tbl_evidence e ON c.complaints_id = e.complaints_id
        WHERE c.complaint_name = ?
    ");
    $stmt->execute([$userFullName]);
    $complaints = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='alert alert-danger' role='alert'>Error: " . $e->getMessage() . "</div>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaints Status</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../styles/style.css">
</head>
<body>

<?php 
include '../includes/resident-nav.php';
include '../includes/resident-bar.php';
?>

<!-- Page Content -->
<div class="content" id="content">
    <div class="container mt-4">
        <h1 class="text-center">Complaints Status</h1>
        <div class="row justify-content-center">
            <div class="col-md-9 mx-auto">
                <?php if (empty($complaints)): ?>
                    <div class="alert alert-info text-center" role="alert">
                        You haven't submitted any complaints yet.
                    </div>
                <?php else: ?>
                    <div class="table"> <!-- Added to make the table responsive -->
                        <table class="table table-striped table-bordered text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">Complaint Name</th>
                                    <th scope="col">Barangay</th>
                                    <th scope="col">View Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($complaints as $complaint): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($complaint['complaint_name']); ?></td>
                                        <td><?php echo htmlspecialchars($complaint['barangay_name']); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#viewComplaintModal" 
                                                    data-complaint='<?php echo json_encode($complaint); ?>'>
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<!-- Complaint Details Modal -->
<!-- Complaint Details Modal -->
<div class="modal fade" id="viewComplaintModal" tabindex="-1" aria-labelledby="viewComplaintModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Make modal larger -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewComplaintModalLabel">Complaint Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Complaint details -->
                <p><strong>Date Filed:</strong> <span id="modalDateFiled"></span></p>
                <p><strong>Complaint Name:</strong> <span id="modalComplaintName"></span></p>
                <p><strong>Complaint Description:</strong> <span id="modalComplaintDescription"></span></p>
                <p><strong>Category:</strong> <span id="modalCategory"></span></p>
                <p><strong>Barangay:</strong> <span id="modalBarangay"></span></p>
                <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                <p><strong>Complaints Person:</strong> <span id="modalComplaintsPerson"></span></p>
                <p><strong>Gender:</strong> <span id="modalGender"></span></p>
                <p><strong>Place Of Birth:</strong> <span id="modalPlaceOfBirth"></span></p>
                <p><strong>Age:</strong> <span id="modalAge"></span></p>
                <p><strong>Educational Background:</strong> <span id="modalEducation"></span></p>
                <p><strong>Civil Status:</strong> <span id="modalCivilStatus"></span></p>

                <!-- Hearing Details -->
                <p><strong>Hearing Type:</strong> <span id="modalHearingType"></span></p>
                <p><strong>Hearing Date:</strong> <span id="modalHearingDate"></span></p>
                <p><strong>Hearing Time:</strong> <span id="modalHearingTime"></span></p>
                <!-- Evidence Section -->
                <div id="modalEvidenceSection" style="display: none;">
                    <p><strong>Evidence:</strong></p>
                    <img id="modalEvidenceImage" src="" alt="Evidence" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../scripts/script.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.all.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var complaintModal = document.getElementById('viewComplaintModal');
    complaintModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var complaint = JSON.parse(button.getAttribute('data-complaint'));

        // Populate modal with complaint details
        document.getElementById('modalDateFiled').textContent = complaint.date_filed;
        document.getElementById('modalComplaintName').textContent = complaint.complaint_name;
        document.getElementById('modalComplaintDescription').textContent = complaint.complaints;
        document.getElementById('modalCategory').textContent = complaint.complaints_category;
        document.getElementById('modalBarangay').textContent = complaint.barangay_name;
        document.getElementById('modalStatus').textContent = complaint.status;
        document.getElementById('modalComplaintsPerson').textContent = complaint.complaints_person;
        document.getElementById('modalGender').textContent = complaint.gender || 'N/A';
        document.getElementById('modalPlaceOfBirth').textContent = complaint.place_of_birth || 'N/A';
        document.getElementById('modalAge').textContent = complaint.age || 'N/A';
        document.getElementById('modalEducation').textContent = complaint.educational_background || 'N/A';
        document.getElementById('modalCivilStatus').textContent = complaint.civil_status || 'N/A';


      
        // Hearing Details
        document.getElementById('modalHearingType').textContent = complaint.hearing_type || 'N/A';
        document.getElementById('modalHearingDate').textContent = complaint.hearing_date ? new Date(complaint.hearing_date).toLocaleDateString() : 'N/A';
              document.getElementById('modalHearingTime').textContent = complaint.  hearing_time || 'N/A'
        // Evidence
        if (complaint.evidence_path) {
            document.getElementById('modalEvidenceSection').style.display = 'block';
            document.getElementById('modalEvidenceImage').src = complaint.evidence_path;
        } else {
            document.getElementById('modalEvidenceSection').style.display = 'none';
        }
    });
});


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
</body>
</html>
