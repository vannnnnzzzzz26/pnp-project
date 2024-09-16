<?php

function get_complaint_details($complaints_id, $conn) {
    $sql = "SELECT 
                c.complaints_name,
                c.complaints,
                c.date_filed,
                c.status,
                c.hearing_date,
                c.hearing_time,
                c.hearing_type,
                c.hearing_status,
                i.gender,
                i.age,
                i.place_of_birth,
                i.civil_status,
                i.educational_background,
                b.barangay_name,
                e.evidence_path
            FROM tbl_complaints c
            LEFT JOIN tbl_info i ON c.info_id = i.info_id
            LEFT JOIN tbl_barangay b ON c.barangays_id = b.barangay_id
            LEFT JOIN tbl_evidence e ON c.complaints_id = e.complaints_id
            WHERE c.complaints_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $complaints_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there is a result and fetch the data
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row;
    } else {
        return null;
    }
}

?>