<?php
// Start the session
session_start();

include '../connection/dbconn.php'; ;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $complaint_id = $_POST['complaint_id'];
    $hearing_date = $_POST['hearing_date'];
    $hearing_time = $_POST['hearing_time'];
    $hearing_type = $_POST['hearing_type']; // 'First Hearing', 'Second Hearing', 'Third Hearing'
    $attend = isset($_POST['attend']) ? 1 : 0; // 1 if checked, 0 otherwise

    try {
        if ($attend) {
            // Mark the hearing as attended
            $stmt = $pdo->prepare("
                UPDATE tbl_complaints
                SET hearing_missed = FALSE
                WHERE complaints_id = ?
            ");
            $stmt->execute([$complaint_id]);

            // Determine the next hearing type
            $stmt = $pdo->prepare("SELECT hearing_type FROM tbl_complaints WHERE complaints_id = ?");
            $stmt->execute([$complaint_id]);
            $current_hearing_type = $stmt->fetchColumn();

            $next_hearing_type = '';
            if ($current_hearing_type === 'First Hearing') {
                $next_hearing_type = 'Second Hearing';
            } elseif ($current_hearing_type === 'Second Hearing') {
                $next_hearing_type = 'Third Hearing';
            } elseif ($current_hearing_type === 'Third Hearing') {
                // No further hearings needed
                $next_hearing_type = 'None'; // Or you can leave it as an empty string or some other status
            }

            if ($next_hearing_type !== 'None') {
                $stmt = $pdo->prepare("
                    UPDATE tbl_complaints
                    SET hearing_type = ?
                    WHERE complaints_id = ?
                ");
                $stmt->execute([$next_hearing_type, $complaint_id]);
            }

            echo "Hearing attended. Next hearing type: " . $next_hearing_type;
        } else {
            // Update the complaint with the new hearing details
            $stmt = $pdo->prepare("
                UPDATE tbl_complaints
                SET hearing_date = ?, hearing_time = ?, hearing_type = ?, hearing_missed = FALSE
                WHERE complaints_id = ?
            ");
            $stmt->execute([$hearing_date, $hearing_time, $hearing_type, $complaint_id]);

            echo "Hearing updated successfully.";
        }
    } catch (PDOException $e) {
        echo "Error updating hearing: " . $e->getMessage();
    }
}
?>
