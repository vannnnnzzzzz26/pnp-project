<?php
header('Content-Type: application/json');
include '../connection/dbconn.php'; ;

$complaint_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($complaint_id <= 0) {
    echo json_encode(['error' => 'Invalid complaint ID']);
    exit();
}

try {
    $stmt = $pdo->prepare("
        SELECT c.*, b.barangay_name, cc.complaints_category, i.gender, i.place_of_birth, i.age, i.educational_background, i.civil_status,
               GROUP_CONCAT(DISTINCT e.evidence_path) AS evidence, GROUP_CONCAT(DISTINCT d.document_path) AS documents
        FROM tbl_complaints c
        JOIN tbl_users_barangay b ON c.barangays_id = b.barangays_id
        JOIN tbl_complaintcategories cc ON c.category_id = cc.category_id
        JOIN tbl_info i ON c.info_id = i.info_id
        LEFT JOIN tbl_evidence e ON c.complaints_id = e.complaints_id
        LEFT JOIN tbl_documents d ON c.complaints_id = d.complaints_id
        WHERE c.complaints_id = ?
        GROUP BY c.complaints_id
    ");
    $stmt->execute([$complaint_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // Add any necessary processing for evidence and documents arrays
        $result['evidence'] = explode(',', $result['evidence']);
        $result['documents'] = explode(',', $result['documents']);
        echo json_encode($result);
    } else {
        echo json_encode(['error' => 'No complaint found']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
