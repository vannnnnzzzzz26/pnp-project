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
        $paramsTotal = [];
        $paramsFiledCourt = [];
        $paramsSettledBarangay = [];
        $paramsRejected = [];

        if ($year) {
            $dateConditions[] = "YEAR(c.date_filed) = ?";
            $paramsTotal[] = $year;
            $paramsFiledCourt[] = $year;
            $paramsSettledBarangay[] = $year;
            $paramsRejected [] = $year;

        }
        if ($month_from && $month_to) {
            $dateConditions[] = "MONTH(c.date_filed) BETWEEN ? AND ?";
            $paramsTotal[] = $month_from;
            $paramsTotal[] = $month_to;
            $paramsFiledCourt[] = $month_from;
            $paramsFiledCourt[] = $month_to;
            $paramsSettledBarangay[] = $month_from;
            $paramsSettledBarangay[] = $month_to;
            $paramsRejected[] = $month_from;
            $paramsRejected[] = $month_to;
        } elseif ($month_from) {
            $dateConditions[] = "MONTH(c.date_filed) >= ?";
            $paramsTotal[] = $month_from;
            $paramsFiledCourt[] = $month_from;
            $paramsSettledBarangay[] = $month_from;
            $paramsRejected[] = $month_from;
        } elseif ($month_to) {
            $dateConditions[] = "MONTH(c.date_filed) <= ?";
            $paramsTotal[] = $month_to;
            $paramsFiledCourt[] = $month_to;
            $paramsSettledBarangay[] = $month_to;
            $paramsRejected[] = $month_to;
        }

        if ($month) {
            $dateConditions[] = "MONTH(c.date_filed) = ?";
            $paramsTotal[] = $month;
            $paramsFiledCourt[] = $month;
            $paramsSettledBarangay[] = $month;
            $paramsRejected [] = $month;
        }

        $dateSql = $dateConditions ? implode(' AND ', $dateConditions) : '';

        // Fetch total complaints
        $whereSql = $dateSql ? 'WHERE ' . $dateSql : '';
        $stmtTotal = $pdo->prepare("SELECT COUNT(*) AS total_complaints FROM tbl_complaints c $whereSql");
        $stmtTotal->execute($paramsTotal);
        $totalComplaints = $stmtTotal->fetchColumn();

        // Fetch Filed in the Court
        $additionalWhere = $dateSql ? ' AND ' . $dateSql : '';
        $stmtFiledCourt = $pdo->prepare("SELECT COUNT(*) AS filed_in_court FROM tbl_complaints c WHERE c.status = 'Filed in the Court' AND c.responds = 'pnp' $additionalWhere");
        $stmtFiledCourt->execute($paramsFiledCourt);
        $filedInCourt = $stmtFiledCourt->fetchColumn();

        // Fetch settled in Barangay
        $stmtSettledBarangay = $pdo->prepare("SELECT COUNT(*) AS settled_in_barangay FROM tbl_complaints c WHERE c.status = 'settled_in_barangay' AND c.responds = 'barangay' $additionalWhere");
        $stmtSettledBarangay->execute($paramsSettledBarangay);
        $settledInBarangay = $stmtSettledBarangay->fetchColumn();



        $stmtRejected = $pdo->prepare("SELECT COUNT(*) AS rejected FROM tbl_complaints c WHERE c.status = 'Rejected' AND c.responds = 'barangay' $additionalWhere");
        $stmtRejected->execute($paramsRejected);
        $Rejected = $stmtRejected->fetchColumn();

        return [
            'totalComplaints' => $totalComplaints,
            'filedInCourt' => $filedInCourt,
            'settledInBarangay' => $settledInBarangay,
            'Rejected' => $Rejected
        ];
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

$data = fetchDashboardData($pdo, $year, $month,$month_from, $month_to,);

// Fetch complaints by barangay data
function fetchComplaintsByBarangay($pdo, $year, $month,$month_from, $month_to) {
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

$barangayData = fetchComplaintsByBarangay($pdo, $year, $month,$month_from, $month_to);

// Fetch gender data
function fetchGenderData($pdo, $year, $month ,$month_from, $month_to) {
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

        $whereSql = $whereClauses ? 'AND ' . implode(' AND ', $whereClauses) : '';

        $stmt = $pdo->prepare("
            SELECT i.gender, COUNT(i.info_id) AS gender_count
            FROM tbl_complaints c
            JOIN tbl_info i ON c.info_id = i.info_id
            WHERE 1=1 $whereSql
            GROUP BY i.gender
        ");
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

$genderData = fetchGenderData($pdo, $year, $month,$month_from, $month_to);

// Fetch complaint categories data
function fetchComplaintCategoriesData($pdo, $year, $month,$month_from, $month_to) {
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

        $whereSql = $whereClauses ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

        $stmt = $pdo->prepare("
            SELECT cc.complaints_category, COUNT(c.complaints_id) AS category_count
            FROM tbl_complaints c
            JOIN tbl_complaintcategories cc ON c.category_id = cc.category_id
            $whereSql
            GROUP BY cc.complaints_category
        ");
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

$categoryData = fetchComplaintCategoriesData($pdo, $year, $month,$month_from, $month_to);
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
            background-color: whitesmoke;
            
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

include '../includes/pnp-nav.php';
include '../includes/pnp-bar.php';
?>


<center><div class="content">
     <center> <h1>Dashboard</h1></center>
     <div class="row">
   <div class="col-md-3">
      <div class="card">
         <i class="fas fa-file-alt" style="font-size:50px;color: green;"></i>
         <h2><?php echo htmlspecialchars($data['totalComplaints']); ?></h2>
         <p>Total Complaints</p>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card">
         <i class="fas fa-gavel" style="font-size:50px; color: cyan;"></i>
         
         <h2><?php echo htmlspecialchars($data['filedInCourt']); ?></h2>
         <p>Filed in the Court</p>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card">
         <i class="fas fa-check-circle" style="font-size:50px;color: blue;"></i>
         <h2><?php echo htmlspecialchars($data['settledInBarangay']); ?></h2>
         <p>Settled in Barangay</p>
      </div>
   </div>
   <div class="col-md-3">
      <div class="card">
         <i class="fas fa-times-circle" style="font-size:50px; color: red;"></i>
         <h2><?php echo htmlspecialchars($data['Rejected']); ?></h2>
         <p>Rejected</p>
      </div>
   </div>
</div>


     <div class="container mt-4">
    <!-- Filter Form -->


    <!-- Complaints by Barangay Chart -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h2>Complaints by Barangay</h2>
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
                <select name="month" id="month" class="form-control  ">
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
            <div>
                <label>&nbsp;</label><br>
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>


    
                    <div class="chart-container d-flex justify-content-center align-items-center" style="height: 30rem;">
                        
                    <canvas id="barangayChartSmall"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gender and Category Charts -->
    <div class="row mb-4">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h2>Gender</h2>
                    <div class="chart-container d-flex justify-content-center align-items-center" style="height: 300px;">
                        
                        <canvas id="genderChart"></canvas>
                    </div>
                    <div class="analytics-info mt-3">
                        <h4>Highest Gender Count:</h4>
                        <p class="" id="genderMaxInfo"></p>
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



<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProfileForm" action="update_profile.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="editFirstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="editFirstName" name="first_name" value="<?php echo htmlspecialchars($firstName); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="editMiddleName" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="editMiddleName" name="middle_name" value="<?php echo htmlspecialchars($middleName); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="editLastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="editLastName" name="last_name" value="<?php echo htmlspecialchars($lastName); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="editExtensionName" class="form-label">Extension Name</label>
                        <input type="text" class="form-control" id="editExtensionName" name="extension_name" value="<?php echo htmlspecialchars($extensionName); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="editProfilePic" class="form-label">Profile Picture</label>
                        <input type="file" class="form-control" id="editProfilePic" name="profile_pic">
                    </div>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
</center>


    <script>

document.addEventListener('DOMContentLoaded', function() {
    var ctxBarangay = document.getElementById('barangayChartSmall').getContext('2d');
    
    // Data from PHP
    var barangayNames = <?php echo json_encode(array_column($barangayData, 'barangay_name')); ?>;
    var complaintCounts = <?php echo json_encode(array_column($barangayData, 'complaint_count')); ?>;

    // Find the maximum number of complaints
    var maxComplaints = Math.max(...complaintCounts);

    // Calculate the percentage for each barangay
    var percentages = complaintCounts.map(count => (count / maxComplaints * 100).toFixed(2));

    // Chart data
    var barangayChart = new Chart(ctxBarangay, {
        type: 'line', // Line chart
        data: {
            labels: barangayNames,
            datasets: [{
                label: '', // Removed the dataset label
                data: complaintCounts,
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 2,
                fill: false // Do not fill under the line
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true // Show the legend
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            var index = tooltipItem.dataIndex;
                            return `Barangay: ${barangayNames[index]}, Complaints: ${complaintCounts[index]}, Percentage: ${percentages[index]}%`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: false // Hide the title
                    },
                    ticks: {
                        display: false // Hide the barangay names on the x-axis
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Complaints'
                    }
                }
            }
        }
    });




    // Gender Chart
    var ctxGender = document.getElementById('genderChart').getContext('2d');
    var genderDataValues = <?php echo json_encode(array_column($genderData, 'gender_count')); ?>;
    var genderDataLabels = <?php echo json_encode(array_column($genderData, 'gender')); ?>;
    var totalGenderCount = genderDataValues.reduce((a, b) => a + b, 0); // Total count of genders

    var genderChart = new Chart(ctxGender, {
        type: 'doughnut',
        data: {
            labels: genderDataLabels.map((label, index) => `${label} (${((genderDataValues[index] / totalGenderCount) * 100).toFixed(1)}%)`), // Add percentages to labels
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
                    display: false // Hide the legend if needed
                }
            }
        }
    });

    // Find the highest value in gender data
    var maxGenderValue = Math.max(...genderDataValues);
    var maxGenderIndex = genderDataValues.indexOf(maxGenderValue);
    document.getElementById('genderMaxInfo').textContent = `${genderDataLabels[maxGenderIndex]}: ${((maxGenderValue / totalGenderCount) * 100).toFixed(1)}%`;

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









document.addEventListener("DOMContentLoaded", function () {
    const notificationButton = document.getElementById('notificationButton');
    const modalBody = document.getElementById('notificationModalBody');

    function fetchNotifications() {
        return fetch('notifications.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json().catch(() => ({ success: false }))) // Handle JSON parsing errors
        .then(data => {
            if (data.success) {
                const notificationCount = data.notifications.length;
                const notificationCountBadge = document.getElementById("notificationCount");

                if (notificationCount > 0) {
                    notificationCountBadge.textContent = notificationCount;
                    notificationCountBadge.classList.remove("d-none");
                } else {
                    notificationCountBadge.textContent = "0";
                    notificationCountBadge.classList.add("d-none");
                }

                let notificationListHtml = '';
                if (notificationCount > 0) {
                    data.notifications.forEach(notification => {
                        notificationListHtml += `
                            <div class="dropdown-item" 
                                 data-id="${notification.complaints_id}" 
                                 data-status="${notification.status}" 
                                 data-complaint-name="${notification.complaint_name}" 
                                 data-barangay-name="${notification.barangay_name}">
                                Complaint: ${notification.complaint_name}<br>
                                Barangay: ${notification.barangay_name}<br>
                                Status: ${notification.status}
                                 <hr>
                            </div>
                        `;
                    });
                } else {
                    notificationListHtml = '<div class="dropdown-item text-center">No new notifications</div>';
                }

                const popoverInstance = bootstrap.Popover.getInstance(notificationButton);
                if (popoverInstance) {
                    popoverInstance.setContent({
                        '.popover-body': notificationListHtml
                    });
                } else {
                    new bootstrap.Popover(notificationButton, {
                        html: true,
                        content: function () {
                            return `<div class="popover-content">${notificationListHtml}</div>`;
                        },
                        container: 'body'
                    });
                }

                document.querySelectorAll('.popover-content .dropdown-item').forEach(item => {
                    item.addEventListener('click', function () {
                        const notificationId = this.getAttribute('data-id');
                        markNotificationAsRead(notificationId);
                    });
                });
            } else {
                console.error("Failed to fetch notifications");
            }
        })
        .catch(error => {
            console.error("Error fetching notifications:", error);
        });
    }

    function markNotificationAsRead(notificationId) {
        fetch('notifications.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ notificationId: notificationId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Notification marked as read');
                fetchNotifications(); // Refresh notifications
            } else {
                console.error("Failed to mark notification as read");
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    }

    fetchNotifications();

    notificationButton.addEventListener('shown.bs.popover', function () {
        markNotificationsAsRead();
    });

    function markNotificationsAsRead() {
        fetch('notifications.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ markAsRead: true })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const badge = document.querySelector(".badge.bg-danger");
                if (badge) {
                    badge.classList.add("d-none");
                }
            } else {
                console.error("Failed to mark notifications as read");
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    }
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
                // Redirect to logout URL
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
