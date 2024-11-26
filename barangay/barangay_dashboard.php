<?php
session_start();
include '../connection/dbconn.php';
include '../includes/bypass.php';


// Fetch user information from session
$firstName = $_SESSION['first_name'] ?? '';
$middleName = $_SESSION['middle_name'] ?? '';
$lastName = $_SESSION['last_name'] ?? '';
$extensionName = $_SESSION['extension_name'] ?? '';
$email = $_SESSION['email'] ?? '';
$barangay_name = $_SESSION['barangay_name'] ?? '';
$pic_data = $_SESSION['pic_data'] ?? '';

// Get filters from GET request
$year = isset($_GET['year']) ? intval($_GET['year']) : '';
$month = isset($_GET['month']) ? intval($_GET['month']) : '';
$month_from = isset($_GET['month_from']) ? intval($_GET['month_from']) : '';
$month_to = isset($_GET['month_to']) ? intval($_GET['month_to']) : '';

// Function to fetch dashboard data
function fetchDashboardData($pdo, $year, $month,  $month_from, $month_to) {
    try {
        $dateConditions = [];
        $paramsSettledBarangay = [];
        $paramsRejected = [];
        

        if ($year) {
            $dateConditions[] = "YEAR(c.date_filed) = ?";
            $paramsTotal[] = $year;
            $paramsSettledBarangay[] = $year;
            $paramsRejected[] = $year;
        }

        if ($month) {
            $dateConditions[] = "MONTH(c.date_filed) = ?";
            $paramsSettledBarangay[] = $month;
            $paramsRejected[] = $month;
        }

        if ($month_from && $month_to) {
            $dateConditions[] = "MONTH(c.date_filed) BETWEEN ? AND ?";
            $paramsTotal[] = $month_from;
            $paramsTotal[] = $month_to;
            $paramsSettledBarangay[] = $month_from;
            $paramsSettledBarangay[] = $month_to;
            $paramsRejected[] = $month_from;
            $paramsRejected[] = $month_to;
        } elseif ($month_from) {
            $dateConditions[] = "MONTH(c.date_filed) >= ?";
            $paramsTotal[] = $month_from;
            $paramsSettledBarangay[] = $month_from;
            $paramsRejected[] = $month_from;
        } elseif ($month_to) {
            $dateConditions[] = "MONTH(c.date_filed) <= ?";
            $paramsTotal[] = $month_to;
            $paramsSettledBarangay[] = $month_to;
            $paramsRejected[] = $month_to;
        }

        $dateSql = $dateConditions ? implode(' AND ', $dateConditions) : '';

        // Fetch total complaints
        $whereSql = $dateSql ? 'WHERE ' . $dateSql : '';
        $stmtTotal = $pdo->prepare("SELECT COUNT(*) AS total_complaints FROM tbl_complaints c $whereSql");
        $totalComplaints = $stmtTotal->fetchColumn();

        // Fetch settled in Barangay
        $additionalWhere = $dateSql ? ' AND ' . $dateSql : '';
        $stmtSettledBarangay = $pdo->prepare("SELECT COUNT(*) AS settled_in_barangay FROM tbl_complaints c WHERE c.status = 'settled_in_barangay' AND c.responds = 'barangay' $additionalWhere");
        $stmtSettledBarangay->execute($paramsSettledBarangay);
        $settledInBarangay = $stmtSettledBarangay->fetchColumn();

        // Fetch rejected complaints
        $stmtRejected = $pdo->prepare("SELECT COUNT(*) AS rejected FROM tbl_complaints c WHERE c.status = 'rejected' $additionalWhere");
        $stmtRejected->execute($paramsRejected);
        $rejected = $stmtRejected->fetchColumn();

        return [
            'totalComplaints' => $totalComplaints,
            'settledInBarangay' => $settledInBarangay,
            'rejected' => $rejected
        ];
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

$data = fetchDashboardData($pdo, $year, $month,  $month_from, $month_to);

// Fetch complaints by barangay data
function fetchComplaintsByBarangay($pdo, $year, $month,  $month_from, $month_to) {
    try {
        $whereClauses = [];
        $params = [];

        if ($year) {
            $whereClauses[] = "YEAR(c.date_filed) = ?";
            $params[] = $year;
        }

        if ($month) {
            $whereClauses[] = "MONTH(c.date_filed) = ?";
            $params[] = $month;
        }


        if ($month_from && $month_to) {
            $dateConditions[] = "MONTH(c.date_filed) BETWEEN ? AND ?";
            $paramsTotal[] = $month_from;
            $paramsTotal[] = $month_to;
          
            $paramsSettledBarangay[] = $month_from;
            $paramsSettledBarangay[] = $month_to;
            $paramsRejected[] = $month_from;
            $paramsRejected[] = $month_to;
        } elseif ($month_from) {
            $dateConditions[] = "MONTH(c.date_filed) >= ?";
            $paramsSettledBarangay[] = $month_from;
            $paramsRejected[] = $month_from;
        } elseif ($month_to) {
            $dateConditions[] = "MONTH(c.date_filed) <= ?";
            $paramsTotal[] = $month_to;
            $paramsSettledBarangay[] = $month_to;
            $paramsRejected[] = $month_to;
        }
        $whereSql = $whereClauses ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

        $stmt = $pdo->prepare("
            SELECT ub.barangay_name, COUNT(c.complaints_id) AS complaint_count
            FROM tbl_complaints c
            JOIN tbl_users_barangay ub ON c.barangays_id = ub.barangays_id
            $whereSql
            GROUP BY ub.barangay_name
        ");
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

$barangayData = fetchComplaintsByBarangay($pdo, $year, $month,  $month_from, $month_to);

// Fetch gender data
// Fetch gender data
function fetchPurokData($pdo, $year, $month, $barangay_name, $month_from, $month_to) {
    try {
        $whereClauses = ["ub.barangay_name = ?"];
        $params = [$barangay_name];

        if ($year) {
            $whereClauses[] = "YEAR(c.date_filed) = ?";
            $params[] = $year;
        }

        if ($month) {
            $whereClauses[] = "MONTH(c.date_filed) = ?";
            $params[] = $month;
        }

        if ($month_from && $month_to) {
            $whereClauses[] = "MONTH(c.date_filed) BETWEEN ? AND ?";
            $params[] = $month_from;
            $params[] = $month_to;
        } elseif ($month_from) {
            $whereClauses[] = "MONTH(c.date_filed) >= ?";
            $params[] = $month_from;
        } elseif ($month_to) {
            $whereClauses[] = "MONTH(c.date_filed) <= ?";
            $params[] = $month_to;
        }

        $whereSql = $whereClauses ? ' AND ' . implode(' AND ', $whereClauses) : '';

        $stmt = $pdo->prepare("
            SELECT u.purok, COUNT(u.user_id) AS purok_count
            FROM tbl_complaints c
            JOIN tbl_users u ON c.user_id = u.user_id
            JOIN tbl_users_barangay ub ON c.barangays_id = ub.barangays_id
            WHERE 1=1 $whereSql
            GROUP BY u.purok
        ");
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

// Usage
$purokData = fetchPurokData($pdo, $year, $month, $barangay_name, $month_from, $month_to);


/// Fetch complaint categories data
function fetchComplaintCategoriesData($pdo, $year, $month, $barangay_name, $month_from, $month_to) {
    try {
        $whereClauses = ["ub.barangay_name = ?"];
        $params = [$barangay_name];

        if ($year) {
            $whereClauses[] = "YEAR(c.date_filed) = ?";
            $params[] = $year;
        }

        if ($month) {
            $whereClauses[] = "MONTH(c.date_filed) = ?";
            $params[] = $month;
        }

        if ($month_from && $month_to) {
            $whereClauses[] = "MONTH(c.date_filed) BETWEEN ? AND ?";
            $params[] = $month_from;
            $params[] = $month_to;
        } elseif ($month_from) {
            $whereClauses[] = "MONTH(c.date_filed) >= ?";
            $params[] = $month_from;
        } elseif ($month_to) {
            $whereClauses[] = "MONTH(c.date_filed) <= ?";
            $params[] = $month_to;
        }

        $whereSql = $whereClauses ? ' AND ' . implode(' AND ', $whereClauses) : '';

        $stmt = $pdo->prepare("
            SELECT cc.complaints_category, COUNT(c.complaints_id) AS category_count
            FROM tbl_complaints c
            JOIN tbl_complaintcategories cc ON c.category_id = cc.category_id
            JOIN tbl_users_barangay ub ON c.barangays_id = ub.barangays_id
            WHERE 1=1 $whereSql
            GROUP BY cc.complaints_category
        ");
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}


$categoryData = fetchComplaintCategoriesData($pdo, $year, $month, $barangay_name ,  $month_from, $month_to);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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
    background-color: whitesmoke; 
  
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
            <i class="fas fa-times-circle" style="font-size:50px; color: red;"></i>

                <h2><?php echo htmlspecialchars($data['rejected']); ?></h2>
                <p>reject complaints</p>
            </div>
            <div class="card">
            <i class="fas fa-check-circle" style="font-size:50px;color: blue;"></i>

                <h2><?php echo htmlspecialchars($data['settledInBarangay']); ?></h2>
                <p>Settled in Barangay</p>
            </div>
        </div>
       
<div class="container mt-4">


    <form method="GET" action="">
        <div class="row mb-4">
            <div class="col-md-4">
                <label for="year">Select Year</label>
                <select name="year" id="year" class="form-control">
                    <option value="">All Years</option>
                    <?php
                    $currentYear = date('Y');
                    for ($i = $currentYear; $i >= 2000; $i--) {
                        $selected = ($i == $year) ? 'selected' : '';
                        echo "<option value='$i' $selected>$i</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="month">Select Month</label>
                <select name="month" id="month" class="form-control">
                    <option value="">All Months</option>
                    <?php
                    for ($m = 1; $m <= 12; $m++) {
                        $monthName = date('F', mktime(0, 0, 0, $m, 10));
                        $selected = ($m == $month) ? 'selected' : '';
                        echo "<option value='$m' $selected>$monthName</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-2">
            <label for="month_from">Month From</label>
            <select name="month_from" id="month_from" class="form-control">
                <option value="">select</option>
                <?php
                for ($m = 1; $m <= 12; $m++) {
                    $monthName = date('F', mktime(0, 0, 0, $m, 10));
                    $selected = ($m == $month_from) ? 'selected' : '';
                    echo "<option value='$m' $selected>$monthName</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-md-2">
            <label for="month_to">Month To</label>
            <select name="month_to" id="month_to" class="form-control">
                <option value="">Select</option>
                <?php
                for ($m = 1; $m <= 12; $m++) {
                    $monthName = date('F', mktime(0, 0, 0, $m, 10));
                    $selected = ($m == $month_to) ? 'selected' : '';
                    echo "<option value='$m' $selected>$monthName</option>";
                }
                ?>
            </select>
        </div>
            <div class="col-md-4">
                <label>&nbsp;</label><br>
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    
        <div class="row mb-4">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h2>Purok</h2>
                    <div class="chart-container d-flex justify-content-center align-items-center" style="height: 300px;">
                        
                        <canvas id="purokChart"></canvas>
                    </div>
                    <div class="analytics-info mt-3">
                        <h4>Highest Purok Count:</h4>
                        <p class="" id="purokMaxInfo"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h2>Complaint Categories</h2>
                    <div class="chart-container d-flex justify-content-center align-items-center" style="height: 300px;">
                    <canvas id="categoryChart"></canvas>
                    </div>
                    <div class="analytics-info mt-3">
                        <h4>Highest Complaints  Count:</h4>
                        <p id="categoryMaxInfo"></p>
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
 
   // Purok Chart
var ctxPurok = document.getElementById('purokChart').getContext('2d');
var purokDataValues = <?php echo json_encode(array_column($purokData, 'purok_count')); ?>;
var purokDataLabels = <?php echo json_encode(array_column($purokData, 'purok')); ?>;
var totalPurokCount = purokDataValues.reduce((a, b) => a + b, 0); // Total count of puroks

var purokChart = new Chart(ctxPurok, {
    type: 'doughnut',
    data: {
        labels: purokDataLabels.map((label, index) => `${label} (${((purokDataValues[index] / totalPurokCount) * 100).toFixed(1)}%)`), // Add percentages to labels
        datasets: [{
            data: purokDataValues,
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
            borderColor: '#fff',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        cutout: '50%',
        plugins: {
            legend: {
                display: false // Hide the legend if needed
            }
        }
    }
});

// Find the highest value in purok data
var maxPurokValue = Math.max(...purokDataValues);
var maxPurokIndex = purokDataValues.indexOf(maxPurokValue);
document.getElementById('purokMaxInfo').textContent = `${purokDataLabels[maxPurokIndex]}: ${((maxPurokValue / totalPurokCount) * 100).toFixed(1)}%`;

    // Most Complaints Report (Category Chart)
    var ctxCategory = document.getElementById('categoryChart').getContext('2d');
    var categoryDataValues = <?php echo json_encode(array_column($categoryData, 'category_count')); ?>;
    var categoryDataLabels = <?php echo json_encode(array_column($categoryData, 'complaints_category')); ?>;
    var totalCategoryCount = categoryDataValues.reduce((a, b) => a + b, 0); // Total count of complaints in categories

    var categoryChart = new Chart(ctxCategory, {
        type: 'pie', // Changed to pie chart
        data: {
            labels: categoryDataLabels.map((label, index) => `${label} (${((categoryDataValues[index] / totalCategoryCount) * 100).toFixed(1)}%)`), // Add percentages to labels
            datasets: [{
                label: '', // Removed the dataset label
                data: categoryDataValues,
                backgroundColor: [
                    '#4e73df', // Blue
                    '#1cc88a', // Green
                    '#36b9cc', // Light Blue
                    '#f6c23e', // Yellow
                    '#e74a3b', // Red
                    '#5a5c69'  // Gray
                ],
                borderColor: '#fff',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false, // Display the legend
                    position: 'top' // Position the legend at the top
                }
            }
        }
    });

    // Find the highest value in category data
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js@latest/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@latest/dist/chartjs-plugin-datalabels.min.js"></script>


    <script src="../scripts/script.js"></script>

</body>
</html>
