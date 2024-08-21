<?php
require 'dbconn.php';

// Fetch the dashboard data
function fetchDashboardData($pdo) {
    try {
        // Fetch total complaints
        $stmtTotal = $pdo->prepare("SELECT COUNT(*) AS total_complaints FROM tbl_complaints");
        $stmtTotal->execute();
        $totalComplaints = $stmtTotal->fetchColumn();

        // Fetch settled in PNP
        $stmtSettledPNP = $pdo->prepare("SELECT COUNT(*) AS settled_in_pnp FROM tbl_complaints WHERE status = 'settled in pnp' AND responds = 'pnp'");
        $stmtSettledPNP->execute();
        $settledInPNP = $stmtSettledPNP->fetchColumn();

        // Fetch settled in Barangay
        $stmtSettledBarangay = $pdo->prepare("SELECT COUNT(*) AS settled_in_barangay FROM tbl_complaints WHERE status = 'settled_in_barangay' AND responds = 'barangay'");
        $stmtSettledBarangay->execute();
        $settledInBarangay = $stmtSettledBarangay->fetchColumn();

        return [
            'totalComplaints' => $totalComplaints,
            'settledInPNP' => $settledInPNP,
            'settledInBarangay' => $settledInBarangay
        ];
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [
            'totalComplaints' => 0,
            'settledInPNP' => 0,
            'settledInBarangay' => 0
        ];
    }
}

$data = fetchDashboardData($pdo);

// Fetch complaints by barangay data
function fetchComplaintsByBarangay($pdo) {
    try {
        $stmt = $pdo->prepare("
            SELECT ub.barangay_name, COUNT(c.complaints_id) AS complaint_count
            FROM tbl_complaints c
            JOIN tbl_users_barangay ub ON c.barangays_id = ub.barangays_id
            GROUP BY ub.barangay_name
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}

$barangayData = fetchComplaintsByBarangay($pdo);

// Fetch gender data
function fetchGenderData($pdo) {
    try {
        $stmt = $pdo->prepare("
            SELECT gender, COUNT(info_id) AS gender_count
            FROM tbl_info
            GROUP BY gender
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}

$genderData = fetchGenderData($pdo);

// Fetch complaint categories data
function fetchComplaintCategoriesData($pdo) {
    try {
        $stmt = $pdo->prepare("
            SELECT cc.complaints_category, COUNT(c.complaints_id) AS category_count
            FROM tbl_complaints c
            JOIN tbl_complaintcategories cc ON c.category_id = cc.category_id
            GROUP BY cc.complaints_category
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}

$categoryData = fetchComplaintCategoriesData($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
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
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">Excel</a>
        <button class="navbar-toggler" type="button" onclick="toggleSidebar()">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<div class="content">
    <div class="container">
        <h1>Dashboard</h1>
        <div class="card-container">
            <div class="card">
                <h2><?php echo htmlspecialchars($data['totalComplaints']); ?></h2>
                <p>Total Complaints</p>
            </div>
            <div class="card">
                <h2><?php echo htmlspecialchars($data['settledInPNP']); ?></h2>
                <p>Settled in PNP</p>
            </div>
            <div class="card">
                <h2><?php echo htmlspecialchars($data['settledInBarangay']); ?></h2>
                <p>Settled in Barangay</p>
            </div>
        </div>
        
        <div class="pie-chart-container">
            <div class="card smalls-card">
                <h2>Complaints by Barangay</h2>
                <div class="chart-container">
                    <canvas id="barangayChartSmall"></canvas>
                </div>
            </div>
       

            <div class="card smalls-card">
                <h2>most complaints report</h2>
                <div class="chart-container">
                    <canvas id="categoryChart"></canvas>
                </div>
           
            </div>
        </div>
     
        <div class="card small-card">
                <h2>Gender </h2>
                <div class="chart-container">
                    <canvas id="genderChart"></canvas>
                </div>
            </div>

       
    </div>
</div>

<div style="margin-top: 3rem;" class="sidebar bg-dark" id="sidebar">
    <button class="sidebar-toggler" type="button" onclick="toggleSidebar()">
        <i class="bi bi-grid-fill large-icon"></i><span class="nav-text menu-icon-text">Menu</span>
    </button>

    <div class="user-info px-3 py-2 text-center">
        <?php
        session_start();

        if (isset($_SESSION['email'])) {
            $email = $_SESSION['email'];
            $firstName = isset($_SESSION['first_name']) ? $_SESSION['first_name'] : '';
            $middleName = isset($_SESSION['middle_name']) ? $_SESSION['middle_name'] : '';
            $lastName = isset($_SESSION['last_name']) ? $_SESSION['last_name'] : '';
            $extensionName = isset($_SESSION['extension_name']) ? $_SESSION['extension_name'] : '';
            $accountType = isset($_SESSION['accountType']) ? $_SESSION['accountType'] : '';
            if (isset($_SESSION['pic_data'])) {
                $pic_data = $_SESSION['pic_data'];
                echo "<img class='profile' src='$pic_data' alt='Profile Picture'>";
            }
        }
        ?>
        <p class='white-text'><?php echo $_SESSION['accountType']; ?></p>
        <h5 class='white-text'>User Information</h5>
        <p class='white-text'><?php echo $email; ?></p>
        <p class='white-text'><?php echo "$firstName $middleName $lastName $extensionName"; ?></p>
    </div>
    <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="pnp.php">
                    <i class="bi bi-file-earmark-text large-icon"></i><span class="nav-text">Complaints</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="pnplogs.php">
                    <i class="bi bi-file-earmark-text large-icon"></i><span class="nav-text">Complaints Logs</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="pnp-announcement.php">
                <i class="bi bi-check-square-fill large-icon"></i><span class="nav-text">Announcement</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">
                <i class="bi bi-graph-up"></i><span class="nav-text">Dashboard</span>
                </a>
            </li>
        </ul>
    

        <!-- Logout Form -->
        <form action="logout.php" method="post" id="logoutForm">
            <div class="logout-btn">
                <button type="button" class="btn btn-danger btn-sm" onclick="confirmLogout()">
                    <i class="bi bi-box-arrow-left"></i><span class="nav-text">Logout</span>
                </button>
            </div>
        </form>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    var ctxBarangay = document.getElementById('barangayChartSmall').getContext('2d');
    var barangayChart = new Chart(ctxBarangay, {
        type: 'line', // Changed to 'line'
        data: {
            labels: <?php echo json_encode(array_column($barangayData, 'barangay_name')); ?>,
            datasets: [{
                label: 'Number of Complaints',
                data: <?php echo json_encode(array_column($barangayData, 'complaint_count')); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.2)', // Light blue background
                borderColor: 'rgba(54, 162, 235, 1)', // Darker blue border
                borderWidth: 2,
                fill: false // Do not fill under the line
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Barangay'
                    },
                    ticks: {
                        autoSkip: false, // Ensures all labels are shown
                        maxRotation: 90, // Rotates labels for better readability
                        minRotation: 45
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

    var ctxGender = document.getElementById('genderChart').getContext('2d');
    var genderChart = new Chart(ctxGender, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode(array_column($genderData, 'gender')); ?>,
            datasets: [{
                data: <?php echo json_encode(array_column($genderData, 'gender_count')); ?>,
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                borderColor: '#fff',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            cutout: '50%'
        }
    });

    var ctxCategory = document.getElementById('categoryChart').getContext('2d');
    var categoryChart = new Chart(ctxCategory, {
        type: 'bar', // Kept as 'bar'
        data: {
            labels: <?php echo json_encode(array_column($categoryData, 'complaints_category')); ?>,
            datasets: [{
                label: 'Number of Complaints',
                data: <?php echo json_encode(array_column($categoryData, 'category_count')); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Category'
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
});
</script>


<script src="script.js"></script>
</body>
</html>
