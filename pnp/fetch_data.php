<?php
// Example of how to structure the PHP response
header('Content-Type: application/json');

// Simulating data fetching from a database based on the month
$month = $_GET['month'];
$barangayData = [
    ['barangay_name' => 'Barangay 1', 'complaint_count' => 10],
    ['barangay_name' => 'Barangay 2', 'complaint_count' => 15],
    // Add more data as required
];

$genderData = [
    ['gender' => 'Male', 'gender_count' => 20],
    ['gender' => 'Female', 'gender_count' => 25],
    // Add more data as required
];

$categoryData = [
    ['complaints_category' => 'Category 1', 'category_count' => 30],
    ['complaints_category' => 'Category 2', 'category_count' => 40],
    // Add more data as required
];

echo json_encode([
    'barangayData' => $barangayData,
    'genderData' => $genderData,
    'categoryData' => $categoryData
]);
?>
