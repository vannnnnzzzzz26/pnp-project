<?php
include '../connection/dbconn.php'; 

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    try {
        // Prepare SQL statement to fetch complaint details and avoid duplicates
        $stmt = $pdo->prepare("
            SELECT c.complaint_name, c.complaints AS description, c.date_filed, 
                   cc.complaints_category AS category, 
                   b.barangay_name, c.cp_number, c.complaints_person, 
                   i.gender, i.place_of_birth, i.age, 
                   i.educational_background, i.civil_status,
                   GROUP_CONCAT(DISTINCT e.evidence_path ORDER BY e.evidence_path SEPARATOR ',') AS evidence_paths,
                   GROUP_CONCAT(DISTINCT CONCAT(h.hearing_date, '|', h.hearing_time, '|', h.hearing_type, '|', h.hearing_status) ORDER BY h.hearing_date, h.hearing_time SEPARATOR ',') AS hearing_history
            FROM tbl_complaints c
            LEFT JOIN tbl_complaintcategories cc ON c.category_id = cc.category_id
            LEFT JOIN tbl_users_barangay b ON c.barangays_id = b.barangays_id
            LEFT JOIN tbl_info i ON c.info_id = i.info_id
            LEFT JOIN tbl_evidence e ON c.complaints_id = e.complaints_id
            LEFT JOIN tbl_hearing_history h ON c.complaints_id = h.complaints_id
            WHERE c.complaints_id = ?
            GROUP BY c.complaints_id
        ");
        $stmt->execute([$id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $hearing_history = [];
            if ($result['hearing_history']) {
                $hearing_entries = array_unique(explode(',', $result['hearing_history']));
                foreach ($hearing_entries as $entry) {
                    list($hearing_date, $hearing_time, $hearing_type, $hearing_status) = explode('|', $entry);
                    $hearing_history[] = [
                        'hearing_date' => $hearing_date,
                        'hearing_time' => $hearing_time,
                        'hearing_type' => $hearing_type,
                        'hearing_status' => $hearing_status
                    ];
                }
            }

            $evidence = [];
            if ($result['evidence_paths']) {
                $evidence = array_unique(explode(',', $result['evidence_paths']));
            }

            echo json_encode([
                'complaint_name' => $result['complaint_name'],
                'description' => $result['description'],
                'date_filed' => $result['date_filed'],
                'category' => $result['category'],
                'barangay_name' => $result['barangay_name'],
                'cp_number' => $result['cp_number'],
                'complaints_person' => $result['complaints_person'],
                'gender' => $result['gender'],
                'place_of_birth' => $result['place_of_birth'],
                'age' => $result['age'],
                'educational_background' => $result['educational_background'],
                'civil_status' => $result['civil_status'],
                'evidence' => $evidence,
                'hearing_history' => $hearing_history
            ]);
        } else {
            echo json_encode(['error' => 'No data found for the given complaint ID.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid complaint ID.']);
}
?>
