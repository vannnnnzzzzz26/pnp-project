<?php
header('Content-Type: application/json');
include '../connection/dbconn.php'; 

try {
    // Fetch and prepare data
    $data = fetchDashboardData($pdo, $year, $month);
    $barangayData = fetchComplaintsByBarangay($pdo, $year, $month);
    $genderData = fetchGenderData($pdo, $year, $month);
    $categoryData = fetchComplaintCategoriesData($pdo, $year, $month);

    // Debugging output
    error_log(print_r([
        'barangayData' => $barangayData,
        'genderData' => $genderData,
        'categoryData' => $categoryData,
        'totalComplaints' => $data['totalComplaints'],
        'filedInCourt' => $data['filedInCourt'],
        'settledInBarangay' => $data['settledInBarangay']
    ], true));

    // Send JSON response
    echo json_encode([
        'barangayData' => $barangayData,
        'genderData' => $genderData,
        'categoryData' => $categoryData,
        'totalComplaints' => $data['totalComplaints'],
        'filedInCourt' => $data['filedInCourt'],
        'settledInBarangay' => $data['settledInBarangay']
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
