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
    // Prepare SQL statement to fetch complaint details and hearing history
    $stmt = $pdo->prepare("
        SELECT c.*, b.barangay_name, cc.complaints_category,   b.barangay_name, 
               cc.complaints_category,
               u.cp_number,          
               u.gender,            
               u.place_of_birth,    
               u.age,               
                u.nationality,
                u.educational_background,
               u.civil_status,
               GROUP_CONCAT(DISTINCT e.evidence_path SEPARATOR ', ') AS evidence_paths,
               GROUP_CONCAT(DISTINCT CONCAT(h.hearing_date, '|', h.hearing_time, '|', h.hearing_type, '|', h.hearing_status) SEPARATOR ',') AS hearing_history
        FROM tbl_complaints c
        JOIN tbl_users_barangay b ON c.barangays_id = b.barangays_id
        JOIN tbl_complaintcategories cc ON c.category_id = cc.category_id
          JOIN tbl_users u ON c.user_id = u.user_id  
        LEFT JOIN tbl_evidence e ON c.complaints_id = e.complaints_id
        LEFT JOIN tbl_hearing_history h ON c.complaints_id = h.complaints_id
        WHERE c.complaints_id = ?
        GROUP BY c.complaints_id
    ");

    // Bind the complaint ID parameter and execute the query
    $stmt->execute([$complaint_id]);

    // Fetch the complaint details
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $complaint_name = htmlspecialchars($row['complaint_name']);
        $ano = htmlspecialchars($row['ano']);
        $saan = htmlspecialchars($row['saan']);
        $kailan = htmlspecialchars($row['kailan']);
        $paano = htmlspecialchars($row['paano']);
        $bakit = htmlspecialchars($row['bakit']);

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
        $nationality = htmlspecialchars($row['nationality']);
        $evidence_paths = htmlspecialchars($row['evidence_paths']); 
        $status = htmlspecialchars($row['status']);
        $hearing_history = htmlspecialchars($row['hearing_history']); // New



 // Display the complaint details
echo "
<div class='row'>
    <div class='col-md-6'>
        <label for='complaintName'>Complaint Name:</label>
        <input type='text' id='complaintName' class='form-control' value='$complaint_name' readonly>

        <label for='ano'>Ano (What):</label>
        <input type='text' id='ano' class='form-control' value='$ano' readonly>

        <label for='saan'>Saan (Where):</label>
        <input type='text' id='saan' class='form-control' value='$saan' readonly>

        <label for='kailan'>Kailan (When):</label>
        <input type='text' id='kailan' class='form-control' value='$kailan' readonly>

        <label for='paano'>Paano (How):</label>
        <input type='text' id='paano' class='form-control' value='$paano' readonly>

        <label for='bakit'>Bakit (Why):</label>
        <input type='text' id='bakit' class='form-control' value='$bakit' readonly>

        <label for='description'>Description:</label>
        <textarea id='description' class='form-control' rows='4' readonly>$complaints</textarea>

        <label for='dateFiled'>Date Filed:</label>
        <input type='text' id='dateFiled' class='form-control' value='$date_filed' readonly>

        <label for='category'>Category:</label>
        <input type='text' id='category' class='form-control' value='$category_name' readonly>
    </div>
    <div class='col-md-6'>
        <label for='barangay'>Barangay:</label>
        <input type='text' id='barangay' class='form-control' value='$barangay_name' readonly>

        <label for='contactNumber'>Contact Number:</label>
        <input type='text' id='contactNumber' class='form-control' value='$cp_number' readonly>

        <label for='complaintsPerson'>Complaints Person:</label>
        <input type='text' id='complaintsPerson' class='form-control' value='$complaints_person' readonly>

        <label for='gender'>Gender:</label>
        <input type='text' id='gender' class='form-control' value='$gender' readonly>

        <label for='placeOfBirth'>Place of Birth:</label>
        <input type='text' id='placeOfBirth' class='form-control' value='$place_of_birth' readonly>

        <label for='age'>Age:</label>
        <input type='text' id='age' class='form-control' value='$age' readonly>

        <label for='educationalBackground'>Educational Background:</label>
        <input type='text' id='educationalBackground' class='form-control' value='$educational_background' readonly>

        <label for='civilStatus'>Civil Status:</label>
        <input type='text' id='civilStatus' class='form-control' value='$civil_status' readonly>

        <label for='status'>Status:</label>
        <input type='text' id='status' class='form-control' value='$status' readonly>

        <label for='nationality'>Nationality:</label>
        <input type='text' id='nationality' class='form-control' value='$nationality' readonly>

        <label for='hearingHistory'>Hearing History:</label>
        <ul id='hearingHistory'>
";

// Display hearing history
if ($hearing_history) {
$hearings = explode(',', $hearing_history);
foreach ($hearings as $hearing) {
    list($date, $time, $type, $status) = explode('|', $hearing);
    echo "<li>Date: $date, Time: $time, Type: $type, Status: $status</li>";
}
} else {
echo "<li>No hearing history available.</li>";
}

echo "
        </ul>
        </div>
    </div>
";

        // Display evidence
        if ($evidence_paths) {
            $evidence_paths_array = explode(', ', $evidence_paths);
            foreach ($evidence_paths_array as $path) {
                echo "<a href='../uploads/$path' target='_blank'>View Evidence</a><br>";
            }
        } else {
            echo "No evidence available.";
        }

        echo "
        ";
    } else {
        echo "No complaint found with the provided ID.";
    }
} catch (PDOException $e) {
    echo "Error fetching complaint details: " . $e->getMessage();
}
?>