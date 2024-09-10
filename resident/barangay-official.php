<?php
session_start();

$firstName = $_SESSION['first_name'] ?? '';
$middleName = $_SESSION['middle_name'] ?? '';
$lastName = $_SESSION['last_name'] ?? '';
$extensionName = $_SESSION['extension_name'] ?? '';
$email = $_SESSION['email'] ?? '';
$barangay_name = $_SESSION['barangay_name'] ?? '';
$pic_data = $_SESSION['pic_data'] ?? '';

// Include database connection file
include '../connection/dbconn.php';

// Check if barangay_name is not set but barangays_id is set
if (empty($barangay_name) && isset($_SESSION['barangays_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT barangay_name FROM tbl_users_barangay WHERE barangays_id = ?");
        $stmt->execute([$_SESSION['barangays_id']]);
        $barangay_name = $stmt->fetchColumn();
        
        if ($barangay_name) {
            $_SESSION['barangay_name'] = $barangay_name;
        } else {
            $_SESSION['error'] = "Barangay name not found for the given ID.";
            header("Location: login.php");
            exit();
        }
    } catch (PDOException $e) {
        echo "Error fetching barangay name: " . htmlspecialchars($e->getMessage());
        exit();
    }
}

// Ensure barangay_name and barangays_id are set
if (empty($barangay_name) || !isset($_SESSION['barangays_id'])) {
    $_SESSION['error'] = "Barangay name or ID is not set in the session.";
    header("Location: login.php");
    exit();
}

// Fetch officials data from the database for the specific barangay, excluding deleted officials
try {
    $stmt = $pdo->prepare("
        SELECT o.* 
        FROM tbl_brg_official o
        JOIN tbl_users_barangay b ON o.barangays_id = b.barangays_id
        WHERE b.barangay_name = ? AND o.barangays_id = ? AND o.is_deleted = 0
    ");
    $stmt->execute([$barangay_name, $_SESSION['barangays_id']]);
    $officials = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error fetching officials: " . htmlspecialchars($e->getMessage());
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Officials</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../styles/style.css">
    <style>
        .official-image {
            width: 100px;
            height: 100px;
            object-fit: cover; /* Keeps the aspect ratio of the image */
            border-radius: 50%; /* Makes the image circular */
        }
        .table thead th {
            background-color: #082759;

            color: #ffffff;
            text-align: center;
        }
        .table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .table tbody td {
            vertical-align: middle;
            text-align: center;
        }
        .table img {
            max-width: 100px;
            max-height: 100px;
        }


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
include '../includes/resident-nav.php';
include '../includes/resident-bar.php';
?>

<div class="content">
    <div class="container mt-5">
        <h4 class="mb-4">Officials List</h4>
        <div class="table">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th> <!-- Added for row numbers -->
                        <th>Name</th>
                        <th>Position</th>
                        <th>Image</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Separate barangay captains and kagawads
                    $captains = array_filter($officials, function($official) {
                        return $official['position'] === 'Barangay Captain';
                    });
                    $kagawads = array_filter($officials, function($official) {
                        return strpos($official['position'], 'Kagawad') === 0;
                    });

                    // Merge captains and kagawads
                    $officialsToShow = array_merge($captains, $kagawads);

                    // Initialize row number
                    $rowNumber = 1;

                    foreach ($officialsToShow as $official): ?>
                        <tr>
                            <td><?php echo $rowNumber++; ?></td> <!-- Display row number -->
                            <td><?php echo htmlspecialchars($official['name']); ?></td>
                            <td><?php echo htmlspecialchars($official['position']); ?></td>
                            <td>
                                <?php if (!empty($official['image'])): ?>
                                    <img src="<?php echo htmlspecialchars($official['image']); ?>" alt="Official Image" class="official-image">
                                <?php else: ?>
                                    <img src="default-image.jpg" alt="Default Image" class="official-image">
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.all.min.js"></script>
<script src="../scripts/script.js"></script>

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
            // Correct URL for logout
            window.location.href = "../reg/logout.php";
        }
    });
}
</script>
</body>
</html>
