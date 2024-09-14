<?php
header('Content-Type: application/json');
include '../connection/dbconn.php'; 

try {
    // Get year and month from request
    $year = isset($_GET['year']) ? intval($_GET['year']) : null;
    $month = isset($_GET['month']) ? intval($_GET['month']) : null;

    // Fetch data
    $data = fetchDashboardData($pdo, 'YourBarangayName', $year, $month);
    $genderData = fetchGenderData($pdo, 'YourBarangayName', $year, $month);
    $categoryData = fetchComplaintCategoriesData($pdo, 'YourBarangayName', $year, $month);

    // Check and access data
    $settledInBarangay = isset($data['settledInBarangay']) ? $data['settledInBarangay'] : 0;
    $rejectedInBarangay = isset($data['rejectedInBarangay']) ? $data['rejectedInBarangay'] : 0;

    // Send JSON response
    echo json_encode([
        'genderData' => $genderData,
        'categoryData' => $categoryData,
        'settledInBarangay' => $settledInBarangay,
        'rejectedInBarangay' => $rejectedInBarangay
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
