<?php
// Example of returning filtered data
echo json_encode([
    'barangayNames' => array_column($barangayData, 'barangay_name'),
    'complaintCounts' => array_column($barangayData, 'complaint_count'),
    'percentages' => array_map(function($count) use ($maxComplaints) {
        return number_format(($count / $maxComplaints * 100), 2); // Format as a percentage with 2 decimal places
    }, array_column($barangayData, 'complaint_count')),
    'genderLabels' => array_column($genderData, 'gender'),
    'genderCounts' => array_column($genderData, 'gender_count'),
    'categoryLabels' => array_column($categoryData, 'complaints_category'),
    'categoryCounts' => array_column($categoryData, 'category_count'),
    'maxGenderInfo' => $maxGenderInfo,
    'maxCategoryInfo' => $maxCategoryInfo
]);
?>
