<?php
// view_complaint.php

// Include your database connection file
include_once 'dbconn.php';

// Check if complaint ID is provided in the URL
if (isset($_GET['id'])) {
    $complaint_id = $_GET['id'];

    try {
        // Fetch complaint details from database
        $stmt = $pdo->prepare("SELECT * FROM tbl_complaints WHERE complaints_id = ?");
        $stmt->execute([$complaint_id]);
        $complaint = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if complaint exists
        if ($complaint) {
            // Extract fields
            $complaint_name = isset($complaint['complaint_name']) ? htmlspecialchars($complaint['complaint_name']) : '';
            $complaints = isset($complaint['complaints']) ? htmlspecialchars($complaint['complaints']) : '';
            $date_filed = isset($complaint['date_filed']) ? htmlspecialchars($complaint['date_filed']) : '';

            // Fetch category name
            $stmtCat = $pdo->prepare("SELECT complaints_category FROM tbl_complaintcategories WHERE category_id = ?");
            $stmtCat->execute([$complaint['category_id']]);
            $category_name = htmlspecialchars($stmtCat->fetchColumn());

            // Fetch barangay name
            $stmtBar = $pdo->prepare("SELECT barangay_name FROM tbl_users_barangay WHERE barangays_id = ?");
            $stmtBar->execute([$complaint['barangays_id']]);
            $barangay_name = htmlspecialchars($stmtBar->fetchColumn());

            // Fetch contact number and complaints person
            $cp_number = isset($complaint['cp_number']) ? htmlspecialchars($complaint['cp_number']) : '';
            $complaints_person = isset($complaint['complaints_person']) ? htmlspecialchars($complaint['complaints_person']) : '';

            // Fetch status
            $status = isset($complaint['status']) ? htmlspecialchars($complaint['status']) : '';

            // Display complaint details
            echo "<!DOCTYPE html>
                <html lang='en'>
                <head>
                    <meta charset='UTF-8'>
                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                    <title>View Complaint Details</title>
                    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH' crossorigin='anonymous'>
                    <link rel='stylesheet' href='style.css'>
                </head>
                <body>
                
                    <div class='container mt-4'>
                        <h2>Complaint Details</h2>
                        <div class='card'>
                            <div class='card-body'>
                                <h5 class='card-title'>{$complaint_name}</h5>
                                <p class='card-text'><strong>Description:</strong> {$complaints}</p>
                                <p class='card-text'><strong>Category:</strong> {$category_name}</p>
                                <p class='card-text'><strong>Barangay:</strong> {$barangay_name}</p>
                                <p class='card-text'><strong>Contact Number:</strong> {$cp_number}</p>
                                <p class='card-text'><strong>Complaints Person:</strong> {$complaints_person}</p>
                                <p class='card-text'><strong>Date Filed:</strong> {$date_filed}</p>
                                <p class='card-text'><strong>Status:</strong> {$status}</p>
                               
                            </div>
                        </div>
                    </div>
                    <!-- Bootstrap JS and dependencies -->
                    <script src='https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js' integrity='sha384-KyZXEAg3QhqLMpG8r+H9RHlVho9Uv95TE0Yjl0w9utO6oLjGwkskDZ3M2vpXskxq' crossorigin='anonymous'></script>
                    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js' integrity='sha384-7F5kFf1FyZ0QOW+D5FlrbkVCyImqH8R0b79Teja2tvw5StyiJ6Tga4G+M8C5vQgq' crossorigin='anonymous'></script>
                </body>
                </html>";
        } else {
            echo "<p>Complaint not found.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Error fetching complaint details: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>Complaint ID not provided.</p>";
}
?>
