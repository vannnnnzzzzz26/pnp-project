<?php
// Start session and include database connection
session_start();
include '../connection/dbconn.php'; 

// Redirect to login if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['barangay_name']) && isset($_SESSION['barangays_id'])) {
    $stmt = $pdo->prepare("SELECT barangay_name FROM tbl_users_barangay WHERE barangays_id = ?");
    $stmt->execute([$_SESSION['barangays_id']]);
    $_SESSION['barangay_name'] = $stmt->fetchColumn();
}

$firstName = $_SESSION['first_name'];
$middleName = $_SESSION['middle_name'];
$lastName = $_SESSION['last_name'];
$extensionName = isset($_SESSION['extension_name']) ? $_SESSION['extension_name'] : '';
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$barangay_name = isset($_SESSION['barangay_name']) ? $_SESSION['barangay_name'] : '';
$pic_data = isset($_SESSION['pic_data']) ? $_SESSION['pic_data'] : '';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['complaint_id']) && isset($_POST['action'])) {
    try {
        $complaint_id = $_POST['complaint_id'];
        $action = $_POST['action'];
        $status = ($action == 'approve') ? 'Approved' : 'Rejected';

        // Update complaint status
        $stmt = $pdo->prepare("UPDATE tbl_complaints SET status = ? WHERE complaints_id = ?");
        $stmt->execute([$status, $complaint_id]);

        // Set success message using session
        $_SESSION['success'] = "Complaint status updated successfully.";

        // Redirect to manage complaints page to prevent form resubmission
        header("Location: manage-complaints.php");
        exit();
    } catch (PDOException $e) {
        // Set error message using session
        $_SESSION['error'] = "Error updating complaint status: " . $e->getMessage();

        // Redirect to manage complaints page to prevent form resubmission
        header("Location: manage-complaints.php");
        exit();
    }
}

// Fetch complaints with status 'Unresolved' from the user's barangay
try {
    $stmt = $pdo->prepare("
    SELECT c.*, u.barangay_name, i.image_path, info.gender, info.place_of_birth, info.age, info.educational_background, info.civil_status,
           e.evidence_id, e.evidence_path, cc.complaints_category
    FROM tbl_complaints c
    LEFT JOIN tbl_users_barangay u ON c.barangays_id = u.barangays_id
    LEFT JOIN tbl_image i ON c.image_id = i.image_id
    LEFT JOIN tbl_info info ON c.info_id = info.info_id
    LEFT JOIN tbl_evidence e ON c.complaints_id = e.complaints_id
    LEFT JOIN tbl_complaintcategories cc ON c.category_id = cc.category_id
    WHERE c.status = 'Inprogress' AND u.barangay_name = ? AND c.status != 'Rejected'
");
    $stmt->execute([$barangay_name]);
    $complaints = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error'] = "Error fetching complaints: " . $e->getMessage();
    $complaints = []; // Initialize complaints array if fetch fails
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Complaints</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../styles/style.css">
    <!-- SweetAlert CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php 
include '../includes/navbar.php';
include '../includes/sidebar.php';
?>
<div class="content">
<div class="container mt-5">
    <h1>Manage Complaints</h1>

    <!-- Display success or error messages using SweetAlert -->
    <script>
        <?php if (isset($_SESSION['success'])): ?>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '<?php echo $_SESSION['success']; ?>',
            showConfirmButton: false,
            timer: 1500
        });
        <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '<?php echo $_SESSION['error']; ?>',
            showConfirmButton: false,
            timer: 1500
        });
        <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </script>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Complaint Name</th>
                <th>Barangay</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($complaints as $complaint): ?>
                <tr>
                    <td><?php echo htmlspecialchars($complaint['complaints_id']); ?></td>
                    <td><?php echo htmlspecialchars($complaint['complaint_name']); ?></td>
                    <td><?php echo htmlspecialchars($complaint['barangay_name']); ?></td>
                    <td>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewComplaintModal" data-complaint='<?php echo htmlspecialchars(json_encode($complaint), ENT_QUOTES, 'UTF-8'); ?>'>View</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</div>

<!-- Complaint Details Modal -->
<div class="modal fade" id="viewComplaintModal" tabindex="-1" aria-labelledby="viewComplaintModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewComplaintModalLabel">Complaint Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Complaint details will be populated here using JavaScript -->
                <p><strong>Complaint Name:</strong> <span id="complaintName"></span></p>
                <p><strong>Complaint:</strong> <span id="Complaints"></span></p>
                <p><strong>Date Filed:</strong> <span id="dateFiled"></span></p>
                <p><strong>Category:</strong> <span id="category"></span></p>
                <p><strong>Barangay:</strong> <span id="barangay"></span></p>
                <p><strong>Contact Number:</strong> <span id="contactNumber"></span></p>
                <p><strong>Complaints Person:</strong> <span id="complaintsPerson"></span></p>
                <p><strong>Gender:</strong> <span id="gender"></span></p>
                <p><strong>Place of Birth:</strong> <span id="placeOfBirth"></span></p>
                <p><strong>Age:</strong> <span id="age"></span></p>
                <p><strong>Educational Background:</strong> <span id="educationalBackground"></span></p>
                <p><strong>Civil Status:</strong> <span id="civilStatus"></span></p>
                <p><strong>Verification ID:</strong> 
                    <img id="image" src="" alt="Complaint Image" style="max-width: 100px; cursor: pointer;">
                </p>
                <p><strong>Documents:</strong> <span id="documents"></span></p>
                <p><strong>Evidence:</strong> <span id="evidence"></span></p> <!-- Evidence field -->
            </div>
            <div class="modal-footer">
                <form action="manage-complaints.php" method="post" class="d-inline-block">
                    <input type="hidden" name="complaint_id" id="complaintIdForForm">
                    <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                    <button type="submit" name="action" value="reject" class="btn btn-warning">Reject</button>
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Image View</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="modalImage" src="" alt="Complaint Image" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<!-- Video Modal -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="videoModalLabel">Video View</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <video id="modalVideo" controls class="w-100">
                    <source id="videoSource" src="" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
    </div>
</div>

<!-- Include JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../scripts/script.js"></script>

<script>
// Initialize Bootstrap modals
const viewComplaintModal = new bootstrap.Modal(document.getElementById('viewComplaintModal'), { keyboard: false });
const imageModal = new bootstrap.Modal(document.getElementById('imageModal'), { keyboard: false });
const videoModal = new bootstrap.Modal(document.getElementById('videoModal'), { keyboard: false });

// Fetch modal elements and buttons
document.addEventListener('DOMContentLoaded', function () {
    const modalButtons = document.querySelectorAll('[data-bs-target="#viewComplaintModal"]');
    modalButtons.forEach(button => {
        button.addEventListener('click', function () {
            const complaint = JSON.parse(this.getAttribute('data-complaint'));

            // Populate modal fields with complaint details
            document.getElementById('complaintName').textContent = complaint.complaint_name;
            document.getElementById('Complaints').textContent = complaint.complaints;
            document.getElementById('dateFiled').textContent = complaint.date_filed;
            document.getElementById('category').textContent = complaint.complaints_category;
            document.getElementById('barangay').textContent = complaint.barangay_name;
            document.getElementById('contactNumber').textContent = complaint.cp_number;
            document.getElementById('complaintsPerson').textContent = complaint.complaints_person;
            document.getElementById('gender').textContent = complaint.gender;
            document.getElementById('placeOfBirth').textContent = complaint.place_of_birth;
            document.getElementById('age').textContent = complaint.age;
            document.getElementById('educationalBackground').textContent = complaint.educational_background;
            document.getElementById('civilStatus').textContent = complaint.civil_status;
            document.getElementById('image').setAttribute('src', complaint.image_path || '');
            document.getElementById('complaintIdForForm').value = complaint.complaints_id;

            // Handle Evidence Display
            let evidenceHtml = '';
            if (complaint.evidence_path) {
                const evidenceArray = complaint.evidence_path.split(',');
                evidenceArray.forEach(evidencePath => {
                    const fileExtension = evidencePath.split('.').pop().toLowerCase();
                    if (['mp4', 'mov', 'avi', 'wmv'].includes(fileExtension)) {
                        evidenceHtml += `<a href="#" data-video="${evidencePath}" class="view-media" data-type="video">View Video</a><br>`;
                    } else {
                        evidenceHtml += `<a href="#" data-image="${evidencePath}" class="view-media" data-type="image">View Image</a><br>`;
                    }
                });
            } else {
                evidenceHtml = 'No Evidence Available';
            }
            document.getElementById('evidence').innerHTML = evidenceHtml;

            // Add event listeners for viewing media in modals
            document.querySelectorAll('.view-media').forEach(item => {
                item.addEventListener('click', function (event) {
                    event.preventDefault();
                    const type = this.getAttribute('data-type');
                    if (type === 'image') {
                        const src = this.getAttribute('data-image');
                        document.getElementById('modalImage').setAttribute('src', src);
                        imageModal.show();
                    } else if (type === 'video') {
                        const src = this.getAttribute('data-video');
                        document.getElementById('videoSource').setAttribute('src', src);
                        document.getElementById('modalVideo').load(); // Reload the video element
                        videoModal.show();
                    }
                });
            });
        });
    });
});
</script>


</body>
</html>