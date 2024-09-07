<?php
session_start(); // Start the session

include '../connection/dbconn.php'; 

// Session variables
$firstName = $_SESSION['first_name'] ?? '';
$middleName = $_SESSION['middle_name'] ?? '';
$lastName = $_SESSION['last_name'] ?? '';
$extensionName = $_SESSION['extension_name'] ?? '';
$email = $_SESSION['email'] ?? '';
$barangay_name = $_SESSION['barangay_name'] ?? '';
$pic_data = $_SESSION['pic_data'] ?? '';

// Fetch the dashboard data
function fetchDashboardData($pdo, $userBarangayName) {
    try {
        // Fetch Settled in Barangay
        $stmtSettledBarangay = $pdo->prepare("
            SELECT COUNT(*) AS settled_in_barangay 
            FROM tbl_complaints c
            JOIN tbl_users_barangay ub ON c.barangays_id = ub.barangays_id
            WHERE c.status = 'settled_in_barangay' 
            AND c.responds = 'barangay'
            AND ub.barangay_name = :barangay_name
        ");
        $stmtSettledBarangay->bindParam(':barangay_name', $userBarangayName);
        $stmtSettledBarangay->execute();
        $settledInBarangay = $stmtSettledBarangay->fetchColumn();

        // Fetch Rejected in Barangay
        $stmtRejectedBarangay = $pdo->prepare("
            SELECT COUNT(*) AS Rejected 
            FROM tbl_complaints c
            JOIN tbl_users_barangay ub ON c.barangays_id = ub.barangays_id
            WHERE c.status = 'Rejected' 
            AND c.responds = 'barangay'
            AND ub.barangay_name = :barangay_name
        ");
        $stmtRejectedBarangay->bindParam(':barangay_name', $userBarangayName);
        $stmtRejectedBarangay->execute();
        $rejectedInBarangay = $stmtRejectedBarangay->fetchColumn();

        return [
            'settledInBarangay' => $settledInBarangay,
            'rejectedInBarangay' => $rejectedInBarangay
        ];
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [
            'settledInBarangay' => 0,
            'rejectedInBarangay' => 0
        ];
    }
}

// Fetch complaints by barangay data
function fetchComplaintsByBarangay($pdo, $userBarangayName) {
    try {
        $stmt = $pdo->prepare("
            SELECT ub.barangay_name, COUNT(c.complaints_id) AS complaint_count
            FROM tbl_complaints c
            JOIN tbl_users_barangay ub ON c.barangays_id = ub.barangays_id
            WHERE ub.barangay_name = :barangay_name
            GROUP BY ub.barangay_name
        ");
        $stmt->bindParam(':barangay_name', $userBarangayName);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}

// Fetch gender data specific to the user's barangay
function fetchGenderData($pdo, $userBarangayName) {
    try {
        $stmt = $pdo->prepare("
            SELECT ti.gender, COUNT(ti.info_id) AS gender_count
            FROM tbl_info ti
            JOIN tbl_complaints c ON ti.info_id = c.info_id
            JOIN tbl_users_barangay ub ON c.barangays_id = ub.barangays_id
            WHERE ub.barangay_name = :barangay_name
            GROUP BY ti.gender
        ");
        $stmt->bindParam(':barangay_name', $userBarangayName);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}

// Fetch complaint categories data specific to the user's barangay
function fetchComplaintCategoriesData($pdo, $userBarangayName) {
    try {
        $stmt = $pdo->prepare("
            SELECT cc.complaints_category, COUNT(c.complaints_id) AS category_count
            FROM tbl_complaints c
            JOIN tbl_complaintcategories cc ON c.category_id = cc.category_id
            JOIN tbl_users_barangay ub ON c.barangays_id = ub.barangays_id
            WHERE ub.barangay_name = :barangay_name
            GROUP BY cc.complaints_category
        ");
        $stmt->bindParam(':barangay_name', $userBarangayName);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}

// Fetch data with the user's barangay name
$data = fetchDashboardData($pdo, $barangay_name);
$barangayData = fetchComplaintsByBarangay($pdo, $barangay_name);
$genderData = fetchGenderData($pdo, $barangay_name); // Updated to use the user's barangay
$categoryData = fetchComplaintCategoriesData($pdo, $barangay_name); // Updated to use the user's barangay
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../styles/style.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }
        .card h2 {
            margin: 0;
            font-size: 2em;
            color: #333;
        }
        .card p {
            margin: 10px 0 0;
            font-size: 1.2em;
            color: #666;
        }
        .card-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }
        .small-card {
            width: 300px;
            height: 400px;
            margin: 20px;
        }

        .card.smalls-card {
            width: 600px; /* Increase width */
            height: 400px; /* Increase height */
            margin: 20px auto; /* Center the card */
        }
        .chart-container {
            width: 100%;
            height: 300px;
            margin: 0 auto;
        }
        .pie-chart-container {
            display: flex;
            justify-content: space-around; /* Adjust space between cards */
            flex-wrap: wrap;
            margin: 20px 0; /* Add margin at the top and bottom */
        }

        .charts-container {
            width: 100%;
            height: 500px;
            margin: 0 auto;
        }



        .popover-content {
    background-color: #343a40; /* Dark background to contrast with white */
    color: #ffffff; /* White text color */
    padding: 10px; /* Add some padding */
    border: 1px solid #495057; /* Optional: border for better visibility */
    border-radius: 5px; /* Optional: rounded corners */
    max-height: 300px; /* Ensure it doesn't grow too large */
    overflow-y: auto; /* Add vertical scroll if needed */
}

/* Adjust the arrow for the popover to ensure it points correctly */
.popover .popover-arrow {
    border-top-color: #343a40; /* Match the background color */
}  
        
        

    </style>
</head>
<body>

<?php 

include '../includes/navbar.php';
include '../includes/sidebar.php';
include '../includes/edit-profile.php';
?>

<div class="content">
    <div class="container">
        <h1>Dashboard</h1>
        <div class="card-container">
          
            <div class="card">
                <h2><?php echo htmlspecialchars($data['rejectedInBarangay']); ?></h2>
                <p>reject</p>
            </div>
            <div class="card">
                <h2><?php echo htmlspecialchars($data['settledInBarangay']); ?></h2>
                <p>Settled in Barangay</p>
            </div>
        </div>
       
<div class="container mt-4">
    <h1>Dashboard</h1>
    
    <!-- Second Row: Gender and Most Complaints Report side by side -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h2>Gender</h2>
                    <div class="chart-container">
                        <canvas id="genderChart"></canvas>
                    </div>
                    <div class="analytics-info mt-3">
                        <h4>Highest Gender Count:</h4>
                        <p id="genderMaxInfo"></p> <!-- Placeholder for highest gender data -->
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h2>Most Complaints Report</h2>
                    <div class="chart-container">
                        <canvas id="categoryChart"></canvas>
                    </div>
                    <div class="analytics-info mt-3">
                        <h4>Category with Most Complaints:</h4>
                        <p id="categoryMaxInfo"></p> <!-- Placeholder for highest category data -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


       
    </div>
</div>




    <script>

document.addEventListener('DOMContentLoaded', function () {
    var profilePic = document.querySelector('.profile');
    var editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));

    profilePic.addEventListener('click', function () {
        editProfileModal.show();
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // Gender Chart
    var ctxGender = document.getElementById('genderChart').getContext('2d');
    var genderDataValues = <?php echo json_encode(array_column($genderData, 'gender_count')); ?>;
    var genderDataLabels = <?php echo json_encode(array_column($genderData, 'gender')); ?>;
    var totalGenderCount = genderDataValues.reduce((a, b) => a + b, 0);

    var genderChart = new Chart(ctxGender, {
        type: 'doughnut',
        data: {
            labels: genderDataLabels.map((label, index) => `${label} (${((genderDataValues[index] / totalGenderCount) * 100).toFixed(1)}%)`),
            datasets: [{
                data: genderDataValues,
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                borderColor: '#fff',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            cutout: '50%',
            plugins: {
                legend: {
                    display: true // Show the legend if needed
                }
            }
        }
    });

    var maxGenderValue = Math.max(...genderDataValues);
    var maxGenderIndex = genderDataValues.indexOf(maxGenderValue);
    document.getElementById('genderMaxInfo').textContent = `${genderDataLabels[maxGenderIndex]}: ${((maxGenderValue / totalGenderCount) * 100).toFixed(1)}%`;

    // Category Chart
    var ctxCategory = document.getElementById('categoryChart').getContext('2d');
    var categoryDataValues = <?php echo json_encode(array_column($categoryData, 'category_count')); ?>;
    var categoryDataLabels = <?php echo json_encode(array_column($categoryData, 'complaints_category')); ?>;
    var totalCategoryCount = categoryDataValues.reduce((a, b) => a + b, 0);

    var categoryChart = new Chart(ctxCategory, {
        type: 'pie',
        data: {
            labels: categoryDataLabels.map((label, index) => `${label} (${((categoryDataValues[index] / totalCategoryCount) * 100).toFixed(1)}%)`),
            datasets: [{
                data: categoryDataValues,
                backgroundColor: [
                    '#4e73df', 
                    '#1cc88a', 
                    '#36b9cc', 
                    '#f6c23e', 
                    '#e74a3b', 
                    '#5a5c69'  
                ],
                borderColor: '#fff',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false,
                    position: 'top'
                }
            }
        }
    });

    var maxCategoryValue = Math.max(...categoryDataValues);
    var maxCategoryIndex = categoryDataValues.indexOf(maxCategoryValue);
    document.getElementById('categoryMaxInfo').textContent = `${categoryDataLabels[maxCategoryIndex]}: ${((maxCategoryValue / totalCategoryCount) * 100).toFixed(1)}%`;
});
function confirmLogout() {
    Swal.fire({
        title: "Are you sure?",
        text: "You will be logged out.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#212529",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, logout"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = " ../reg/login.php?logout=<?php echo $_SESSION['user_id']; ?>";
        }
    });
}

</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@latest/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@latest/dist/chartjs-plugin-datalabels.min.js"></script>

    <script src="../scripts/script.js"></script>

</body>
</html>
