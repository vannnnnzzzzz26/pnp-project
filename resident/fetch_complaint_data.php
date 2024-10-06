<?php
include '../connection/dbconn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Handle form submission logic (same as you already have)
        $complaint_name = $_POST['complaint_name'];
        $complaints = $_POST['complaints'];
        // Other form processing...

        // Insert into database
        $stmt = $pdo->prepare("INSERT INTO tbl_complaints (complaint_name, complaints) VALUES (?, ?)");
        $stmt->execute([$complaint_name, $complaints]);

        // Return a success message
        echo json_encode(['status' => 'success', 'message' => 'Complaint submitted successfully.']);
    } catch (PDOException $e) {
        // Return an error message
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
    }
}
?>
