<?php
require_once '../connection/dbconn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $complaint_id = $_POST['complaint_id'];
    $hearing_status = trim($_POST['hearing_status']); // Trim whitespace

    // Debug: Print values to ensure they are correct
    echo "Complaint ID: '$complaint_id', Hearing Status: '$hearing_status'";

    // Prepare the SQL statement
    $stmt = $conn->prepare("UPDATE tbl_complaints SET hearing_status = ? WHERE complaints_id = ?");

    // Bind parameters
    if (empty($hearing_status)) {
        // If hearing_status is empty or null, bind null
        $hearing_status = null;
        $stmt->bind_param("si", $hearing_status, $complaint_id);
    } else {
        // Otherwise, bind the actual value
        $stmt->bind_param("si", $hearing_status, $complaint_id);
    }

    // Execute the query
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error: ' . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
