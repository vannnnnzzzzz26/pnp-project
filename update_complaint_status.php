<?php
session_start();
include_once 'dbconn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['complaint_id']) && isset($_POST['new_status'])) {
    $complaint_id = $_POST['complaint_id'];
    $new_status = $_POST['new_status'];

    try {
        $responds = '';
        if ($new_status === 'settled_in_barangay') {
            $responds = 'barangay';
        } elseif ($new_status === 'pnp') {
            $responds = 'pnp';
        }

        $stmt = $pdo->prepare("UPDATE tbl_complaints SET status = ?, responds = ? WHERE complaints_id = ?");
        $stmt->execute([$new_status, $responds, $complaint_id]);

        echo "Status updated successfully.";
    } catch (PDOException $e) {
        echo "Error updating status: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
