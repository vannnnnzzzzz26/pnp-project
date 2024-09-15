<?php
header('Content-Type: application/json');
include '../connection/dbconn.php'; 

try {
    // Fetch and prepare data
    $data = fetchDashboardData($pdo, $year, $month ,  $month_from, $month_to);
    $barangayData = fetchComplaintsByBarangay($pdo, $year, $month);
    $genderData = fetchGenderData($pdo, $year, $month,$month_from, $month_to);
    $categoryData = fetchComplaintCategoriesData($pdo, $year, $month,$month_from, $month_to);

    
    // Send JSON response
    echo json_encode([
        'barangayData' => $barangayData,
        'genderData' => $genderData,
        'categoryData' => $categoryData,
        'totalComplaints' => $data['totalComplaints'],
        'filedInCourt' => $data['filedInCourt'],
        'Rejected' => $data['Rejected'],
        'settledInBarangay' => $data['settledInBarangay']
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
