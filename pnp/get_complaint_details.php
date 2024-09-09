<?php
include '../connection/dbconn.php'; 

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    try {
        $stmt = $pdo->prepare("
            SELECT c.complaint_name, c.complaints AS description, c.date_filed, 
                   cc.complaints_category AS category, 
                   b.barangay_name, c.cp_number, c.complaints_person, 
                   i.gender, i.place_of_birth, i.age, 
                   i.educational_background, i.civil_status,
                   GROUP_CONCAT(e.evidence_path) AS evidence_paths
            FROM tbl_complaints c
            LEFT JOIN tbl_complaintcategories cc ON c.category_id = cc.category_id
            LEFT JOIN tbl_users_barangay b ON c.barangays_id = b.barangays_id
            LEFT JOIN tbl_info i ON c.info_id = i.info_id
            
            LEFT JOIN tbl_evidence e ON c.complaints_id = e.complaints_id
            WHERE c.complaints_id = ?
            GROUP BY c.complaints_id
        ");
        $stmt->execute([$id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
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
                'evidence' => explode(',', $result['evidence_paths'])
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
