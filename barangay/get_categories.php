<?php
require_once 'connection/dbconn.php'; // Assuming the connection is handled in this file

try {
    // SQL query to fetch all complaint categories
    $query = "SELECT complaints_category FROM tbl_complaintcategories";
    $stmt = $pdo->prepare($query);  // Assuming $pdo is already defined in dbconn.php
    $stmt->execute();

    // Fetch all results
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the categories as JSON
    header('Content-Type: application/json');
    echo json_encode($categories);

} catch (PDOException $e) {
    // Handle error if the query fails
    echo "Error: " . $e->getMessage();
}
?>
