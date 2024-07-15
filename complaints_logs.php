

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uploaded Complaints</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            setInterval(function(){
                location.reload();
            }, 5000);
        });
    </script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">Excel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="content">
        <div class="container">
            <h2 class="mt-3 mb-4">Uploaded Complaints</h2>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Barangay</th>
                            <th>Contact Number</th>
                            <th>Complaints Person</th>
                            <th>Date Filed</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Start output buffering
                        ob_start();

                        // Include your database connection file
                        include_once 'dbconn.php';

                        // Define pagination variables
                        $results_per_page = 10; // Number of results per page

                        // Determine current page
                        if (!isset($_GET['page']) || !is_numeric($_GET['page']) || $_GET['page'] <= 0) {
                            $page = 1;
                        } else {
                            $page = $_GET['page'];
                        }

                        // Calculate the SQL LIMIT starting number for the results on the displaying page
                        $start_from = ($page - 1) * $results_per_page;

                        // Function to display complaints with pagination
                        function displayComplaints($pdo, $start_from, $results_per_page) {
                            try {
                                // Display complaints data with pagination
                                $stmt = $pdo->prepare("SELECT * FROM tbl_complaints ORDER BY date_filed DESC LIMIT ?, ?");
                                $stmt->bindParam(1, $start_from, PDO::PARAM_INT);
                                $stmt->bindParam(2, $results_per_page, PDO::PARAM_INT);
                                $stmt->execute();
                                
                                $id_counter = $start_from + 1;
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    // Extract each field from the $row array
                                    $complaint_id = $id_counter++;
                                    $complaint_name = isset($row['complaint_name']) ? htmlspecialchars($row['complaint_name']) : '';
                                    $complaints = isset($row['complaints']) ? htmlspecialchars($row['complaints']) : '';
                                    $date_filed = isset($row['date_filed']) ? htmlspecialchars($row['date_filed']) : '';

                                    // Fetch category name
                                    $stmtCat = $pdo->prepare("SELECT complaints_category FROM tbl_complaintcategories WHERE category_id = ?");
                                    $stmtCat->execute([$row['category_id']]);
                                    $category_name = htmlspecialchars($stmtCat->fetchColumn());

                                    // Fetch barangay name
                                    $stmtBar = $pdo->prepare("SELECT barangay_name FROM tbl_users_barangay WHERE barangays_id = ?");
                                    $stmtBar->execute([$row['barangays_id']]);
                                    $barangay_name = htmlspecialchars($stmtBar->fetchColumn());

                                    // Fetch contact number and complaints person
                                    $cp_number = isset($row['cp_number']) ? htmlspecialchars($row['cp_number']) : '';
                                    $complaints_person = isset($row['complaints_person']) ? htmlspecialchars($row['complaints_person']) : '';

                                    echo "<tr>";
                                    echo "<td>{$complaint_id}</td>";
                                    echo "<td>{$complaint_name}</td>";
                                    echo "<td>{$complaints}</td>";
                                    echo "<td>{$category_name}</td>";
                                    echo "<td>{$barangay_name}</td>";
                                    echo "<td>{$cp_number}</td>";
                                    echo "<td>{$complaints_person}</td>";
                                    echo "<td>{$date_filed}</td>";
                                    echo "</tr>";
                                }
                            } catch (PDOException $e) {
                                echo "<tr><td colspan='8'>Error fetching complaints: " . $e->getMessage() . "</td></tr>";
                            }
                        }

                        // Check if PDO connection is established
                        if (!isset($pdo)) {
                            echo "<tr><td colspan='8'>PDO connection not established.</td></tr>";
                        } else {
                            displayComplaints($pdo, $start_from, $results_per_page);
                        }

                        // End output buffering and flush output
                        ob_end_flush();
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <?php
                    // Pagination links
                    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM tbl_complaints");
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $total_pages = ceil($row['total'] / $results_per_page);

                    // Previous page link
                    if ($page > 1) {
                        echo "<li class='page-item'><a class='page-link' href='{$_SERVER['PHP_SELF']}?page=" . ($page - 1) . "'>Previous</a></li>";
                    } else {
                        echo "<li class='page-item disabled'><a class='page-link' href='#'>Previous</a></li>";
                    }

                    // Numbered page links
                    for ($i = 1; $i <= $total_pages; $i++) {
                        if ($i == $page) {
                            echo "<li class='page-item active'><a class='page-link' href='#'>$i</a></li>";
                        } else {
                            echo "<li class='page-item'><a class='page-link' href='{$_SERVER['PHP_SELF']}?page=$i'>$i</a></li>";
                        }
                    }

                    // Next page link
                    if ($page < $total_pages) {
                        echo "<li class='page-item'><a class='page-link' href='{$_SERVER['PHP_SELF']}?page=" . ($page + 1) . "'>Next</a></li>";
                    } else {
                        echo "<li class='page-item disabled'><a class='page-link' href='#'>Next</a></li>";
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </div>

   <!-- Sidebar -->
<div class="sidebar">
    <h4 class="px-3">Menu</h4>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link active" href="">Complaints</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="">Complaints Logs</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="">Complaints Responder</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="">PNP</a>
        </li>
    </ul>

      <!-- Logout Form -->
      <form action="logout.php" method="post" id="logoutForm">
            <div class="logout-btn">
                <button type="button" class="btn btn-danger btn-sm" onclick="confirmLogout()">
                    <i class="bi bi-box-arrow-left"></i> Logout
                </button>
            </div>
        </form>

</div>




    <!-- Bootstrap JS (Optional) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.all.min.js"></script>
</body>
</html>
<script>
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
                    document.getElementById('logoutForm').submit();
                }
            });
        }
    </script>
