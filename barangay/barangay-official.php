<?php
session_start();
include '../connection/dbconn.php';

// Check if user is logged in and set barangay information in session
$firstName = $_SESSION['first_name'] ?? '';
$middleName = $_SESSION['middle_name'] ?? '';
$lastName = $_SESSION['last_name'] ?? '';
$extensionName = $_SESSION['extension_name'] ?? '';
$email = $_SESSION['email'] ?? '';
$barangay_name = $_SESSION['barangay_name'] ?? '';
$pic_data = $_SESSION['pic_data'] ?? '';

// Ensure barangay_name is set in the session
if (!$barangay_name) {
    // Redirect to login page or handle unauthorized access
    header("Location: login.php");
    exit();
}

// Fetch barangays_id based on barangay_name
$stmt = $pdo->prepare("SELECT barangays_id FROM tbl_users_barangay WHERE barangay_name = ?");
$stmt->execute([$barangay_name]);
$barangay = $stmt->fetch();

if ($barangay) {
    $barangays_id = $barangay['barangays_id'];
} else {
    $_SESSION['error'] = "Barangay not found.";
    header("Location: login.php");
    exit();
}

// Handle form submission for adding new official
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    if ($_POST['action'] == 'add_official') {
        $name = $_POST['name'];
        $position = $_POST['position'];

        // File upload handling
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is an actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            $_SESSION['error'] = "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size (adjust as necessary)
        if ($_FILES["image"]["size"] > 50000000) {
            $_SESSION['error'] = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats (adjust as necessary)
        $allowed_formats = ["jpg", "jpeg", "png", "gif"];
        if (!in_array($imageFileType, $allowed_formats)) {
            $_SESSION['error'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $_SESSION['error'] = "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                // Image uploaded successfully, now insert data into database
                $image_path = $target_file;

                // Insert into database with barangays_id as foreign key
                $stmt = $pdo->prepare("INSERT INTO tbl_brg_official (name, position, image, barangays_id) VALUES (?, ?, ?, ?)");
                if ($stmt->execute([$name, $position, $image_path, $barangays_id])) {
                    $_SESSION['success'] = "Official added successfully.";
                } else {
                    $_SESSION['error'] = "Failed to add official. Error: " . implode(", ", $stmt->errorInfo());
                }
            } else {
                $_SESSION['error'] = "Sorry, there was an error uploading your file.";
            }
        }

        // Redirect back to barangay-official.php after processing
        header("Location: barangay-official.php");
        exit();
    } elseif ($_POST['action'] == 'edit_official') {
        $official_id = $_POST['official_id'];
        $edit_name = $_POST['edit_name'];
        $edit_position = $_POST['edit_position'];
        $image_path = $_POST['existing_image_path'];

        // Check if a new image is uploaded
        if (!empty($_FILES['edit_image']['name'])) {
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($_FILES["edit_image"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if file is an actual image
            $check = getimagesize($_FILES["edit_image"]["tmp_name"]);
            if ($check !== false) {
                if (move_uploaded_file($_FILES["edit_image"]["tmp_name"], $target_file)) {
                    $image_path = $target_file; // Update image path only if upload is successful
                } else {
                    $_SESSION['error'] = "Sorry, there was an error uploading your file.";
                }
            } else {
                $_SESSION['error'] = "File is not an image.";
            }
        }

        // Update official in database
        $stmt = $pdo->prepare("UPDATE tbl_brg_official SET name = ?, position = ?, image = ? WHERE official_id = ? AND barangays_id = ?");
        if ($stmt->execute([$edit_name, $edit_position, $image_path, $official_id, $barangays_id])) {
            $_SESSION['success'] = "Official updated successfully.";
        } else {
            $_SESSION['error'] = "Failed to update official. Error: " . implode(", ", $stmt->errorInfo());
        }

        // Redirect back to barangay-official.php after processing
        header("Location: barangay-official.php");
        exit();
    }
}

// Soft delete action
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['official_id'])) {
    $official_id = $_GET['official_id'];

    // Ensure the official belongs to the logged-in barangay
    $stmt = $pdo->prepare("UPDATE tbl_brg_official SET is_deleted = 1 WHERE official_id = ? AND barangays_id = ?");
    if ($stmt->execute([$official_id, $barangays_id])) {
        $_SESSION['success'] = "Official deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete official. Error: " . implode(", ", $stmt->errorInfo());
    }

    // Redirect back to barangay-official.php after deletion
    header("Location: barangay-official.php");
    exit();
}

// Fetch officials only from the logged-in barangay (excluding deleted officials)
$stmt = $pdo->prepare("SELECT * FROM tbl_brg_official WHERE barangays_id = ? AND is_deleted = 0");
$stmt->execute([$barangays_id]);

$officials = $stmt->fetchAll();

// Print fetched officials to verify data (optional)
// echo "<pre>";
// print_r($officials);
// echo "</pre>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Barangay Officials</title>
    <!-- Bootstrap CSS link -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../styles/style.css">
</head>
<style>
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
.table thead th {
            background-color: #082759;

            color: #ffffff;
            text-align: center;
        }
</style>
<body>

<?php 

include '../includes/navbar.php';
include '../includes/sidebar.php';
include '../includes/edit-profile.php';
?>

<div class="content">
    <div class="container mt-4">
        <!-- Display session messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Add Official Form -->
        <div class="row">
            <div class="col-md-4">
                <h2 class="mb-4">Barangay Officials</h2>
               
                <form action="barangay-official.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="add_official">
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="position">Position:</label>
                        <select id="position" name="position" class="form-control" required>
                            <option value="">Select Position</option>
                            <option value="Barangay Captain">Barangay Captain</option>
                            <?php for ($i = 1; $i <= 7; $i++): ?>
                                <option value="Kagawad <?php echo $i; ?>">Kagawad <?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="image">Image:</label>
                        <input type="file" id="image" name="image" class="form-control-file" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Add Official</button>
                </form>
            </div>

            <!-- Officials Table -->
            <div class="col-md-8">
            <label for="barangay">Barangay:</label>
                    
                <h4>Officials List</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Actions</th>
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

                        // Sort kagawads based on their position number
                        usort($kagawads, function($a, $b) {
                            $a_num = (int) str_replace('Kagawad ', '', $a['position']);
                            $b_num = (int) str_replace('Kagawad ', '', $b['position']);
                            return $a_num - $b_num;
                        });

                        // Display barangay captains
                        foreach ($captains as $official): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($official['image'])): ?>
                                        <img src="<?php echo htmlspecialchars($official['image']); ?>" class="img-thumbnail" width="100" alt="Official Image">
                                    <?php else: ?>
                                        <img src="default-image.jpg" class="img-thumbnail" width="100" alt="Default Image">
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($official['name']); ?></td>
                                <td><?php echo htmlspecialchars($official['position']); ?></td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-sm edit-official-btn" data-bs-toggle="modal" data-bs-target="#editOfficialModal"
                                        data-official-id="<?php echo $official['official_id']; ?>"
                                        data-name="<?php echo htmlspecialchars($official['name']); ?>"
                                        data-position="<?php echo htmlspecialchars($official['position']); ?>"
                                        data-image="<?php echo htmlspecialchars($official['image']); ?>">
                                        Edit
                                    </a>
                                    <a href="barangay-official.php?action=delete&official_id=<?php echo $official['official_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <!-- Display kagawads -->
                        <?php foreach (array_slice($kagawads, 0, 7) as $official): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($official['image'])): ?>
                                        <img src="<?php echo htmlspecialchars($official['image']); ?>" class="img-thumbnail" width="100" alt="Official Image">
                                    <?php else: ?>
                                        <img src="default-image.jpg" class="img-thumbnail" width="100" alt="Default Image">
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($official['name']); ?></td>
                                <td><?php echo htmlspecialchars($official['position']); ?></td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-sm edit-official-btn" data-bs-toggle="modal" data-bs-target="#editOfficialModal"
                                        data-official-id="<?php echo $official['official_id']; ?>"
                                        data-name="<?php echo htmlspecialchars($official['name']); ?>"
                                        data-position="<?php echo htmlspecialchars($official['position']); ?>"
                                        data-image="<?php echo htmlspecialchars($official['image']); ?>">
                                        Edit
                                    </a>
                                    <a href="barangay-official.php?action=delete&official_id=<?php echo $official['official_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Edit Official Modal -->
        <div class="modal fade" id="editOfficialModal" tabindex="-1" aria-labelledby="editOfficialModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editOfficialModalLabel">Edit Official</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editOfficialForm" action="barangay-official.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="edit_official">
                            <input type="hidden" name="official_id" id="edit_official_id">
                            <input type="hidden" name="existing_image_path" id="existing_image_path">
                            <div class="form-group">
                                <label for="edit_name">Name:</label>
                                <input type="text" id="edit_name" name="edit_name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_position">Position:</label>
                                <input type="text" id="edit_position" name="edit_position" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="edit_image">Image:</label>
                                <input type="file" id="edit_image" name="edit_image" class="form-control-file" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label>Existing Image:</label>
                                <div id="currentImageContainer">
                                    <img id="currentImage" src="" alt="Current Image" class="img-thumbnail" width="100">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Update Official</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.2/dist/sweetalert2.all.min.js"></script>
<script src="../scripts/script.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var editButtons = document.querySelectorAll('.edit-official-btn');
    
    editButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var officialId = this.getAttribute('data-official-id');
            var name = this.getAttribute('data-name');
            var position = this.getAttribute('data-position');
            var image = this.getAttribute('data-image');
            
            document.getElementById('edit_official_id').value = officialId;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_position').value = position;
            document.getElementById('existing_image_path').value = image;
            document.getElementById('currentImage').src = image;
        });
    });
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

</body>
</html>
