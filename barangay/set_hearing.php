<?php
// Start the session
session_start();

// Include the database connection file
include '../connection/dbconn.php';

// Initialize PDO if not already done
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle POST requests to update or insert hearing details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $complaint_id = $_POST['complaint_id'] ?? null;
    $hearing_date = $_POST['hearing_date'] ?? null;
    $hearing_time = $_POST['hearing_time'] ?? null; // 24-hour format input
    $hearing_type = $_POST['hearing_type'] ?? null;
    $hearing_status = $_POST['hearing_status'] ?? null;

    if (!$complaint_id || !$hearing_type) {
        echo "Complaint ID and Hearing Type are required.";
        exit;
    }

    $formatted_hearing_time = $hearing_time ? date("h:i:s A", strtotime($hearing_time)) : null;

    try {
        // Check if the hearing record already exists
        $stmt = $pdo->prepare("
            SELECT id FROM tbl_hearing_history
            WHERE complaints_id = ? AND hearing_type = ?
        ");
        $stmt->execute([$complaint_id, $hearing_type]);
        $existingHearing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingHearing) {
            // Update existing hearing record
            $stmt = $pdo->prepare("
                UPDATE tbl_hearing_history
                SET hearing_date = ?, hearing_time = ?, hearing_status = ?
                WHERE id = ?
            ");
            $stmt->execute([$hearing_date, $formatted_hearing_time, $hearing_status, $existingHearing['id']]);
            echo "Hearing details updated successfully.";
        } else {
            // Insert new hearing record
            $stmt = $pdo->prepare("
                INSERT INTO tbl_hearing_history (complaints_id, hearing_date, hearing_time, hearing_type, hearing_status)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$complaint_id, $hearing_date, $formatted_hearing_time, $hearing_type, $hearing_status]);
            echo "Hearing details recorded successfully.";
        }
    } catch (PDOException $e) {
        echo "Error recording hearing details: " . $e->getMessage();
    }
}

// Handle GET requests to fetch hearing history
if (isset($_GET['complaint_id'])) {
    $complaint_id = $_GET['complaint_id'];

    try {
        $stmt = $pdo->prepare("
            SELECT hearing_date, hearing_time, hearing_type, hearing_status
            FROM tbl_hearing_history
            WHERE complaints_id = ?
            ORDER BY hearing_date DESC, hearing_time DESC
        ");
        $stmt->execute([$complaint_id]);
        $hearings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($hearings);
    } catch (PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
}
?>
