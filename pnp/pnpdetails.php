<?php
include '../connection/dbconn.php'; 

// Check if complaint ID is provided via GET parameter
if (isset($_GET['id'])) {
    // Sanitize the input to prevent SQL injection
    $complaintId = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    try {
        // Prepare statement to fetch complaint details including additional information, evidence, and hearing history
        $stmt = $pdo->prepare("
            SELECT DISTINCT c.complaint_name, c.complaints AS description, c.date_filed, c.status, 
                   c.category_id, c.barangays_id,  c.complaints_person, 
                   b.barangay_name, cat.complaints_category,
                   u.gender, u.place_of_birth, u.age, u.educational_background, u.civil_status,u.nationality, u.cp_number,
                   e.evidence_path,
                   h.hearing_date, h.hearing_time, h.hearing_type, h.hearing_status
            FROM tbl_complaints c
            LEFT JOIN tbl_users_barangay b ON c.barangays_id = b.barangays_id
            LEFT JOIN tbl_complaintcategories cat ON c.category_id = cat.category_id
            LEFT JOIN tbl_users u ON c.user_id = u.user_id
            LEFT JOIN tbl_evidence e ON c.complaints_id = e.complaints_id
            LEFT JOIN tbl_hearing_history h ON c.complaints_id = h.complaints_id
            WHERE c.complaints_id = :complaintId
        ");
        $stmt->bindParam(':complaintId', $complaintId, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch complaint details
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($rows) {
            // Initialize arrays for evidence paths and hearing history
            $evidencePaths = [];
            $hearings = [];

            // Process each row to avoid duplication
            foreach ($rows as $row) {
                // Extract evidence paths if available
                if (!empty($row['evidence_path'])) {
                    $evidencePaths[] = htmlspecialchars($row['evidence_path']);
                }

                // Extract hearing history if available
                if (!empty($row['hearing_date'])) {
                    $hearings[] = [
                        'date' => htmlspecialchars($row['hearing_date']),
                        'time' => htmlspecialchars($row['hearing_time']),
                        'type' => htmlspecialchars($row['hearing_type']),
                        'status' => htmlspecialchars($row['hearing_status'])
                    ];
                }

                // Display complaint details (only once)
                $complaint_name = htmlspecialchars($row['complaint_name']);
                $description = htmlspecialchars($row['description']);
                $date_filed = htmlspecialchars($row['date_filed']);
                $status = htmlspecialchars($row['status']);
                $category_name = htmlspecialchars($row['complaints_category']);
                $barangay_name = htmlspecialchars($row['barangay_name']);
                $cp_number = !empty($row['cp_number']) ? htmlspecialchars($row['cp_number']) : '-';
                $complaints_person = !empty($row['complaints_person']) ? htmlspecialchars($row['complaints_person']) : '-';
                $gender = htmlspecialchars($row['gender']);
                $place_of_birth = htmlspecialchars($row['place_of_birth']);
                $age = htmlspecialchars($row['age']);
                $educational_background = htmlspecialchars($row['educational_background']);
                $civil_status = htmlspecialchars($row['civil_status']);
                $nationality = htmlspecialchars($row['nationality']);

                // Construct HTML to display in modal (only once)
                echo "<p><strong>Name:</strong> {$complaint_name}</p>";
                echo "<p><strong>Description:</strong> {$description}</p>";
                echo "<p><strong>Date Filed:</strong> {$date_filed}</p>";
                echo "<p><strong>Status:</strong> {$status}</p>";
                echo "<p><strong>Category:</strong> {$category_name}</p>";
                echo "<p><strong>Barangay:</strong> {$barangay_name}</p>";
                echo "<p><strong>Contact Number:</strong> {$cp_number}</p>";
                echo "<p><strong>Complaints Person:</strong> {$complaints_person}</p>";
                echo "<p><strong>Gender:</strong> {$gender}</p>";
                echo "<p><strong>Place of Birth:</strong> {$place_of_birth}</p>";
                echo "<p><strong>Age:</strong> {$age}</p>";
                echo "<p><strong>Educational Background:</strong> {$educational_background}</p>";
                echo "<p><strong>Civil Status:</strong> {$civil_status}</p>";
                echo "<p><strong>Nationality:</strong> {$nationality}</p>";

                // Break after displaying the details once
                break;
            }

            // Display evidence if available (unique paths)
            if (!empty($evidencePaths)) {
                $evidencePaths = array_unique($evidencePaths);
                echo "<h5>Evidence:</h5><ul>";
                foreach ($evidencePaths as $path) {
                    echo "<li><a href='../uploads/{$path}' target='_blank'>View Evidence</a></li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No evidence available.</p>";
            }

            // Display hearing history if available
            if (!empty($hearings)) {
                echo "<h5>Hearing History:</h5><ul>";
                foreach ($hearings as $hearing) {
                    echo "<li>Date: {$hearing['date']}, Time: {$hearing['time']}, Type: {$hearing['type']}, Status: {$hearing['status']}</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No hearing history available.</p>";
            }
        } else {
            echo "<p>No details found for Complaint ID: {$complaintId}</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Error fetching complaint details: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>Complaint ID not provided.</p>";
}
?>