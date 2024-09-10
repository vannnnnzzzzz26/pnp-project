<?php
// fetch_hearing_history.php
require '../onnection/dbconn.php'; // Include your database connection script

if (isset($_GET['complaint_id'])) {
    $complaint_id = intval($_GET['complaint_id']);

    $sql = "SELECT hearing_date, hearing_time, hearing_type, hearing_status 
            FROM tbl_hearing_history 
            WHERE complaints_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $complaint_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $hearing_history = [];
    while ($row = $result->fetch_assoc()) {
        $hearing_history[] = $row;
    }

    echo json_encode($hearing_history);
} else {
    echo json_encode(['error' => 'No complaint ID provided']);
}

$conn->close();
?>
