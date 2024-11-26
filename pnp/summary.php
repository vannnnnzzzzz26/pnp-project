<?php

session_start();
include '../connection/dbconn.php'; // Ensure this file sets up the $pdo connection

// Get user details from session or set default empty values
$firstName = $_SESSION['first_name'] ?? '';
$middleName = $_SESSION['middle_name'] ?? '';
$lastName = $_SESSION['last_name'] ?? '';
$extensionName = $_SESSION['extension_name'] ?? '';
$email = $_SESSION['email'] ?? '';
$barangay_name = $_SESSION['barangay_name'] ?? '';
$pic_data = $_SESSION['pic_data'] ?? '';

try {
    // Fetch distinct complaint categories for dynamic table columns
    $stmtCategories = $pdo->prepare("SELECT complaints_category FROM tbl_complaintcategories");
    $stmtCategories->execute();
    $categoriesList = $stmtCategories->fetchAll(PDO::FETCH_COLUMN);

    // Fetch complaint counts grouped by barangay and category
    $stmtComplaints = $pdo->prepare("
        SELECT b.barangay_name, c.complaints_category, COUNT(*) AS complaint_count
        FROM tbl_complaints comp
        JOIN tbl_users_barangay b ON comp.barangays_id = b.barangays_id
        JOIN tbl_complaintcategories c ON comp.category_id = c.category_id
        GROUP BY b.barangay_name, c.complaints_category
        ORDER BY b.barangay_name, c.complaints_category
    ");
    $stmtComplaints->execute();

    // Organize the complaints data by barangay and category
    $complaintsData = [];
    while ($row = $stmtComplaints->fetch(PDO::FETCH_ASSOC)) {
        $barangay = htmlspecialchars($row['barangay_name']);
        $category = htmlspecialchars($row['complaints_category']);
        $count = $row['complaint_count'];

        // Store the count of complaints per barangay and category
        if (!isset($complaintsData[$barangay])) {
            $complaintsData[$barangay] = [];
        }
        $complaintsData[$barangay][$category] = $count;
    }
    
    // Debug: Uncomment the following line to check the structure of $complaintsData
    // echo '<pre>'; print_r($complaintsData); echo '</pre>';

} catch (PDOException $e) {
    // Log the error or handle it in a more user-friendly way in production
    error_log("Error fetching complaint data: " . $e->getMessage());
    echo "<p>An error occurred while fetching complaint data. Please try again later.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PNP Complaints Logs</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../styles/style.css">
</head>

<style>

.sidebar-toggler {
    display: flex;
    align-items: center;
    padding: 10px;
    background-color: transparent; /* Changed from #082759 to transparent */
    border: none;
    cursor: pointer;
    color: white;
    text-align: left;
    width: auto; /* Adjust width automatically */
}
.sidebar{
  background-color: #082759;
}
.navbar{
  background-color: #082759;

}

.navbar-brand{
color: whitesmoke;
margin-left: 5rem;
}
</style>


<?php 

include '../includes/pnp-nav.php';
include '../includes/pnp-bar.php';
?>
<div class="content">
    <h3>Complaint Categories per Barangay</h3>

    <button id="exportButton" class="btn btn-success mb-3">Export to Excel</button>

       <div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Barangay</th>
                <?php
                // Define the list of complaint categories
                $categoriesList = [
                    "Alarms and Scandals (Art. 155)",
                    "Using False Certificates (Art. 175)",
                    "Using Fictitious Names and Concealing True Names (Art. 178)",
                    "Illegal Use of Uniforms and Insignias (Art. 179)",
                    "Physical Injuries Inflicted in a Tumultuous Affray (Art. 252)",
                    "Giving Assistance to Consummated Suicide (Art. 253)",
                    "Responsibility of Participants in a Duel if only Physical Injuries are Inflicted or No Physical Injuries have been Inflicted (Art. 260)",
                    "Less serious physical injuries (Art. 265)",
                    "Slight physical injuries and maltreatment (Art. 266)",
                    "Unlawful arrest (Art. 269)",
                    "Inducing a minor to abandon his/her home (Art. 271)",
                    "Abandonment of a person in danger and abandonment of one’s own victim (Art. 275)",
                    "Abandoning a minor (a child under seven (7) years old) (Art. 276)",
                    "Abandonment of a minor by persons entrusted with his/her custody; indifference of parents (Art. 277)",
                    "Qualified trespass to dwelling (without the use of violence and intimidation) (Art. 280)",
                    "Other forms of trespass (Art. 281)",
                    "Light threats (Art. 283)",
                    "Other light threats (Art. 285)",
                    "Grave coercion (Art. 286)",
                    "Light coercion (Art. 287)",
                    "Other similar coercions (compulsory purchase of merchandise and payment of wages by means of tokens) (Art. 288)",
                    "Formation, maintenance and prohibition of combination of capital or labor through violence or threats (Art. 289)",
                    "Discovering secrets through seizure and correspondence (Art. 290)",
                    "Revealing secrets with abuse of authority (Art. 291)",
                    "Theft (if the value of the property stolen does not exceed Php50.00) (Art. 309)",
                    "Qualified theft (if the amount does not exceed Php500) (Art. 310)",
                    "Occupation of real property or usurpation of real rights in property (Art. 312)",
                    "Altering boundaries or landmarks (Art. 313)",
                    "Swindling or estafa (if the amount does not exceed Php200.00) (Art. 315)",
                    "Other forms of swindling (Art. 316)",
                    "Swindling a minor (Art. 317)",
                    "Other deceits (Art. 318)",
                    "Removal, sale or pledge of mortgaged property (Art. 319)",
                    "Special cases of malicious mischief (if the value of the damaged property does not exceed Php1,000.00 Art. 328)",
                    "Other mischiefs (if the value of the damaged property does not exceed Php1,000.00) (Art. 329)",
                    "Simple seduction (Art. 338)",
                    "Acts of lasciviousness with the consent of the offended party (Art. 339)",
                    "Threatening to publish and offer to prevent such publication for compensation (Art. 356)",
                    "Prohibiting publication of acts referred to in the course of official proceedings (Art. 357)",
                    "Incriminating innocent persons (Art. 363)",
                    "Intriguing against honor (Art. 364)",
                    "Issuing checks without sufficient funds (B.P. 22)",
                    "Fencing of stolen properties if the property involved is not more than Php50.00 (P.D. 1612)"
                ];

                // Dynamically create a table header for each complaint category
                foreach ($categoriesList as $category) {
                    echo "<th>" . htmlspecialchars($category) . "</th>";
                }
                ?>
                <th>Total per Barangay</th> <!-- Add a column for total complaints per barangay -->
            </tr>
        </thead>
        <tbody>
            <?php
            // Define the full list of barangays
            $barangaysList = [
                "Angoluan", "Annafunan", "Arabiat", "Aromin", "Babaran", "Bacradal", "Benguet", "Buneg", "Busilelao", 
                "Cabugao (Poblacion)", "Caniguing", "Carulay", "Castillo", "Dammang East", "Dammang West", "Diasan", 
                "Dicaraoyan", "Dugayong", "Fugu", "Garit Norte", "Garit Sur", "Gucab", "Gumbauan", "Ipil", "Libertad", 
                "Mabbayad", "Mabuhay", "Madadamian", "Magleticia", "Malibago", "Maligaya", "Malitao", "Narra", "Nilumisu", 
                "Pag-asa", "Pangal Norte", "Pangal Sur", "Rumang-ay", "Salay", "Salvacion", "San Antonio Ugad", 
                "San Antonio Minit", "San Carlos", "San Fabian", "San Felipe", "San Juan", "San Manuel (formerly Atelan)", 
                "San Miguel", "San Salvador", "Santa Ana", "Santa Cruz", "Santa Maria", "Santa Monica", "Santo Domingo", 
                "Silauan Sur (Poblacion)", "Silauan Norte (Poblacion)", "Sinabbaran", "Soyung (Poblacion)", 
                "Taggappan (Poblacion)", "Villa Agullana", "Villa Concepcion", "Villa Cruz", "Villa Fabia", "Villa Gomez", 
                "Villa Nuesa", "Villa Padian", "Villa Pereda", "Villa Quirino", "Villa Remedios", "Villa Serafica", 
                "Villa Tanza", "Villa Verde", "Villa Vicenta", "Villa Ysmael (formerly T. Belen)"
            ];

            // Initialize an array to store the total complaints per category
            $totalPerCategory = array_fill_keys($categoriesList, 0);
            $grandTotal = 0; // Initialize a variable for the grand total of all complaints

            if (!empty($barangaysList)) {
                // Loop through each barangay
                foreach ($barangaysList as $barangay) {
                    echo "<tr>";
                    echo "<td>{$barangay}</td>";

                    $barangayTotal = 0; // Initialize total for the current barangay

                    // For each category, display the number of complaints or 0 if no complaints
                    foreach ($categoriesList as $category) {
                        $count = isset($complaintsData[$barangay][$category]) ? $complaintsData[$barangay][$category] : 0;
                        echo "<td>{$count}</td>";
                        $barangayTotal += $count; // Add to the total for this barangay
                        $totalPerCategory[$category] += $count; // Add to the category total
                    }

                    // Display total complaints for the current barangay
                    echo "<td><strong>{$barangayTotal}</strong></td>";
                    echo "</tr>";

                    // Add the barangay total to the grand total
                    $grandTotal += $barangayTotal;
                }

                // Add a row for totals at the bottom
                echo "<tr>";
                echo "<td><strong>Total per Category</strong></td>";

                // Loop through each category to display the total
                foreach ($categoriesList as $category) {
                    echo "<td><strong>{$totalPerCategory[$category]}</strong></td>";
                }

                // Display the grand total of all complaints
                echo "<td><strong>{$grandTotal}</strong></td>";
                echo "</tr>";
            } else {
                echo "<tr><td colspan='" . (count($categoriesList) + 2) . "' class='text-center'>No data available</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div> 

</html>
<script>
    document.getElementById("exportButton").addEventListener("click", function() {
        let csv = [];
        let rows = document.querySelectorAll("table tr");
        
        for (let i = 0; i < rows.length; i++) {
            let row = [], cols = rows[i].querySelectorAll("td, th");
            for (let j = 0; j < cols.length; j++) {
                row.push(cols[j].innerText.replace(/,/g, '')); // Replace commas to avoid breaking CSV format
            }
            csv.push(row.join(",")); // Join each row with commas
        }
        
        // Create a CSV file and trigger download
        let csvFile = new Blob([csv.join("\n")], { type: "text/csv" });
        let downloadLink = document.createElement("a");
        downloadLink.href = URL.createObjectURL(csvFile);
        downloadLink.download = "barangay_complaints.csv"; // File name
        downloadLink.click();
    });
</script>


<script src="../scripts/script.js"></script>

