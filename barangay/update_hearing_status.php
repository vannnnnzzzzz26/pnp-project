<?php
include '../connection/dbconn.php'; // Adjust the path to your 'dbconn.php' file

// Set content type to JSON
header('Content-Type: application/json');

$response = array('success' => false, 'message' => 'Unknown error');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $complaint_id = $_POST['complaint_id'];
        $hearing_status = $_POST['hearing_status'];

        // Validate input
        if (empty($complaint_id)) {
            throw new Exception('Complaint ID is missing.');
        }

        // If hearing_status is empty, set it to NULL
        $hearing_status = !empty($hearing_status) ? $hearing_status : null;

        // Prepare and execute the SQL statement
        $sql = "UPDATE tbl_complaints SET hearing_status = :hearing_status WHERE complaints_id = :complaints_id";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute(['hearing_status' => $hearing_status, 'complaints_id' => $complaint_id]);

        if ($result) {
            $response['success'] = true;
            $response['message'] = 'Hearing status updated successfully.';
        } else {
            throw new Exception('Failed to update hearing status.');
        }
    } else {
        throw new Exception('Invalid request method.');
    }
} catch (Exception $e) {
    // Capture and log the exception message
    error_log("Error: " . $e->getMessage());
    $response['message'] = $e->getMessage();
}

// Output the JSON response
echo json_encode($response);
?>
