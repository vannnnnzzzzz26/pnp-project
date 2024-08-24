<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your database connection file
include '../connection/dbconn.php'; 
// Get the JSON data from the POST request
$data = json_decode(file_get_contents('php://input'), true);

$response = [];

if (isset($data['id'])) {
    $complaint_id = $data['id'];

    // SQL query to update the status of the complaint to 'Settled'
    $sql = "UPDATE tbl_complaints SET status = 'Settled in PNP' WHERE complaints_id = ?";
    
    // Prepare and execute the statement
    if ($stmt = $pdo->prepare($sql)) {
        $stmt->bindParam(1, $complaint_id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            // If the update was successful
            $response['success'] = true;
        } else {
            // If the update failed
            $response['success'] = false;
            $response['error'] = 'Failed to update complaint status.';
        }

        $stmt->closeCursor();
    } else {
        // If the SQL preparation failed
        $response['success'] = false;
        $response['error'] = 'Failed to prepare the SQL statement.';
    }
} else {
    // If the complaint ID is not provided
    $response['success'] = false;
    $response['error'] = 'Complaint ID not provided.';
}

// Send the JSON response back to the JavaScript
header('Content-Type: application/json');
echo json_encode($response);
?>
