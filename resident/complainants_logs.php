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

<style>
    table, th, td {
    border: none;
}
table {
    border-collapse: collapse;
    width: 100%;
}
</style>

<?php 

include '../includes/navbar.php';
include '../includes/resident-bar.php';
?>

<!-- Page Content -->
<div class="content" id="content">
    <div class="container mt-4">
        <h1 class="text-center">Complaints Status</h1>
        <div class="row">
            <div class="col-md-9">
                <?php if (empty($complaints)): ?>
                    <div class="alert alert-info text-center" role="alert">
                        You haven't submitted any complaints yet.
                    </div>
                <?php else: ?>
                    <div class="table">
                        <table class="table table-striped table-bordered text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">#</th> <!-- New column for numbers -->
                                    <th scope="col">Date Filed</th>
                                    <th scope="col">Complaint Name</th>
                                    <th scope="col">Complaint Description</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Barangay</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Complaints Person</th>
                                    <th scope="col">Gender</th>
                                    <th scope="col">Place Of Birth </th>
                                    <th scope="col">Age</th>
                                    <th scope="col">Education</th>
                                    <th scope="col">Civil Status</th>
                                    <th scope="col">Evidence</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter = 1; ?> <!-- Initialize counter -->
                                <?php foreach ($complaints as $complaint): ?>
                                    <tr>
                                        <td><?php echo $counter++; ?></td> <!-- Display counter and increment -->
                                        <td><?php echo htmlspecialchars($complaint['date_filed']); ?></td>
                                        <td><?php echo htmlspecialchars($complaint['complaint_name']); ?></td>
                                        <td><?php echo htmlspecialchars($complaint['complaints']); ?></td>
                                        <td><?php echo htmlspecialchars($complaint['complaints_category']); ?></td>
                                        <td><?php echo htmlspecialchars($complaint['barangay_name']); ?></td>
                                        <td><?php echo htmlspecialchars($complaint['status']); ?></td>
                                        <td><?php echo htmlspecialchars($complaint['complaints_person']); ?></td>
                                        <td><?php echo htmlspecialchars($complaint['gender'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($complaint['place_of_birth'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($complaint['age'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($complaint['educational_background'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($complaint['civil_status'] ?? 'N/A'); ?></td>
                                        <td>
                                            <?php if (!empty($complaint['evidence_path'])): ?>
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewEvidenceModal" data-evidence-path="<?php echo htmlspecialchars($complaint['evidence_path']); ?>">
                                                    View Evidence
                                                </button>
                                            <?php else: ?>
                                                N/A
                                            <?php endif; ?>
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

<!-- Evidence Modal -->
<div class="modal fade" id="viewEvidenceModal" tabindex="-1" aria-labelledby="viewEvidenceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewEvidenceModalLabel">Evidence</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="evidenceImage" src="" alt="Evidence" class="img-fluid">
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
    var evidenceModal = document.getElementById('viewEvidenceModal');
    evidenceModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var evidencePath = button.getAttribute('data-evidence-path');
        var evidenceImage = evidenceModal.querySelector('#evidenceImage');
        evidenceImage.src = evidencePath;
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
            window.location.href = "login.php?logout=<?php echo $_SESSION['user_id']; ?>";
        }
    });
}
</script>
</body>
</html>
