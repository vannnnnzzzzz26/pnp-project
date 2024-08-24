<?php
  include '../connection/dbconn.php'; 

// Check if complaint ID is provided via GET parameter
if (isset($_GET['id'])) {
    // Sanitize the input to prevent SQL injection
    $complaintId = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    try {
        // Prepare statement to fetch complaint details including additional information
        $stmt = $pdo->prepare("
            SELECT c.complaint_name, c.complaints AS description, c.date_filed, c.status, 
                   c.category_id, c.barangays_id, c.cp_number, c.complaints_person, 
                   b.barangay_name, cat.complaints_category,
                   i.gender, i.place_of_birth, i.age, i.educational_background, i.civil_status
            FROM tbl_complaints c
            LEFT JOIN tbl_users_barangay b ON c.barangays_id = b.barangays_id
            LEFT JOIN tbl_complaintcategories cat ON c.category_id = cat.category_id
        JOIN tbl_info i ON c.info_id = i.info_id
            WHERE c.complaints_id = :complaintId
        ");
        $stmt->bindParam(':complaintId', $complaintId, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch complaint details
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Display complaint details
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

            // Construct HTML to display in modal
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
            echo "<p><strong>Educational Backround:</strong> {$educational_background}</p>";
            echo "<p><strong>Civil Status:</strong> {$civil_status}</p>";
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
