<?php
header('Content-Type: application/json');
include '../connection/dbconn.php'; 

try {
    // Get year and month from request
    $year = isset($_GET['year']) ? intval($_GET['year']) : null;
    $month = isset($_GET['month']) ? intval($_GET['month']) : null;
    $month_from = isset($_GET['month_from']) ? intval($_GET['month_from']) : '';
    $month_to = isset($_GET['month_to']) ? intval($_GET['month_to']) : '';
    // Fetch data
    $data = fetchDashboardData($pdo, '', $year, $month,  $month_from, $month_to);
    $purokData = fetchPurokData($pdo, '', $year, $month,  $month_from, $month_to);
    $categoryData = fetchComplaintCategoriesData($pdo, '', $year, $month,  $month_from, $month_to);

    // Check and access data
    $settledInBarangay = isset($data['settledInBarangay']) ? $data['settledInBarangay'] : 0;
    $rejectedInBarangay = isset($data['rejectedInBarangay']) ? $data['rejectedInBarangay'] : 0;
    $approved = isset($data['approved']) ? $data['approved'] : 0;
    // Send JSON response
    echo json_encode([
        'purokData' => $purokData,
        'categoryData' => $categoryData,
        'settledInBarangay' => $settledInBarangay,
        'rejectedInBarangay' => $rejectedInBarangay,


        'approved' => $approved

    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
