<?php
// Ensure session is started at the very beginning
session_start();

// Include your database connection file
include '../connection/dbconn.php'; 
// Get the complaint ID from the query string
$complaint_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Check if complaint ID is valid
if ($complaint_id <= 0) {
    echo "Invalid complaint ID.";
    exit;
}

try {
    // Prepare SQL statement to fetch complaint details
    $stmt = $pdo->prepare("
        SELECT c.*, b.barangay_name, cc.complaints_category, i.gender, i.place_of_birth, i.age, i.educational_background, i.civil_status, GROUP_CONCAT(e.evidence_path SEPARATOR ', ') AS evidence_paths
        FROM tbl_complaints c
        JOIN tbl_users_barangay b ON c.barangays_id = b.barangays_id
        JOIN tbl_complaintcategories cc ON c.category_id = cc.category_id
        JOIN tbl_info i ON c.info_id = i.info_id
        LEFT JOIN tbl_evidence e ON c.complaints_id = e.complaints_id
        WHERE c.complaints_id = ?
        GROUP BY c.complaints_id
    ");

    // Bind the complaint ID parameter and execute the query
    $stmt->execute([$complaint_id]);

    // Fetch the complaint details
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $complaint_name = htmlspecialchars($row['complaint_name']);
        $complaints = htmlspecialchars($row['complaints']);
        $date_filed = htmlspecialchars($row['date_filed']);
        $category_name = htmlspecialchars($row['complaints_category']);
        $barangay_name = htmlspecialchars($row['barangay_name']);
        $cp_number = htmlspecialchars($row['cp_number']);
        $complaints_person = htmlspecialchars($row['complaints_person']);
        $gender = htmlspecialchars($row['gender']);
        $place_of_birth = htmlspecialchars($row['place_of_birth']);
        $age = htmlspecialchars($row['age']);
        $educational_background = htmlspecialchars($row['educational_background']);
        $civil_status = htmlspecialchars($row['civil_status']);
        $evidence_paths = htmlspecialchars($row['evidence_paths']); // For multiple evidence paths

        // Display the complaint details
        echo "
            <strong>Complaint Name:</strong> $complaint_name<br>
            <strong>Description:</strong> $complaints<br>
            <strong>Date Filed:</strong> $date_filed<br>
            <strong>Category:</strong> $category_name<br>
            <strong>Barangay:</strong> $barangay_name<br>
            <strong>Contact Number:</strong> $cp_number<br>
            <strong>Complaints Person:</strong> $complaints_person<br>
            <strong>Gender:</strong> $gender<br>
            <strong>Place of Birth:</strong> $place_of_birth<br>
            <strong>Age:</strong> $age<br>
            <strong>Educational Background:</strong> $educational_background<br>
            <strong>Civil Status:</strong> $civil_status<br>
            <strong>Evidence:</strong> <a href='$evidence_paths' target='_blank'>View Evidence</a><br>
            <strong>Date Filed:</strong> $date_filed<br>
        ";
    } else {
        echo "No complaint found with the provided ID.";
    }
} catch (PDOException $e) {
    echo "Error fetching complaint details: " . $e->getMessage();
}
?>