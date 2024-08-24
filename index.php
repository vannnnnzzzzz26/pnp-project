<?php

include './connection/dbconn.php';
session_start();

// Initialize the $announcements variable as an empty array
$announcements = [];

try {
    // Fetch announcements from the database, excluding deleted ones
    $sql = "SELECT announcement_id, title, content, date_posted, image_path, share_count FROM tbl_announcement WHERE deleted = 0 ORDER BY date_posted DESC";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute()) {
        $announcements = $stmt->fetchAll();
    } else {
        // Log or handle the error appropriately
        error_log("Error fetching announcements: Failed to execute query.");
    }
} catch (Exception $e) {
    // Log or handle the error appropriately
    error_log("Error fetching announcements: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        .announcement-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .announcement-card {
            margin: 15px 0;
            width: 100%;
            max-width: 600px;
            border-radius: 10px;
            overflow: hidden;
        }
        .announcement-image {
            max-height: 200px;
            object-fit: cover;
            width: 100%;
        }
    </style>
    <script>
        function shareAnnouncement(id, title, content, url) {
            Swal.fire({
                title: 'Do you want to share this announcement?',
                text: "You can share this announcement with others.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, share it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (navigator.share) {
                        navigator.share({
                            title: title,
                            text: content,
                            url: url
                        })
                        .then(() => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Shared successfully!',
                                text: 'Thank you for sharing this announcement.',
                                confirmButtonText: 'OK'
                            });

                            // Update share count in the UI
                            let shareCount = document.getElementById('share-count-' + id);
                            shareCount.textContent = parseInt(shareCount.textContent) + 1;

                            // Optionally update share count on the server
                            fetch('update_share_count.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({ announcement_id: id })
                            });
                        })
                        .catch(error => {
                            console.log('Error sharing', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Sharing failed',
                                text: 'There was an error sharing the announcement.',
                                confirmButtonText: 'OK'
                            });
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Sharing not supported',
                            text: 'Web Share API is not supported in your browser.',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        }
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">Excel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="bi bi-house-door"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php"><i class="bi bi-box-arrow-in-right"></i> Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center">Announcements</h2>
        <div class="announcement-container">
            <?php if (!empty($announcements)): ?>
                <?php foreach ($announcements as $announcement): ?>
                    <div class="card announcement-card">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($announcement['date_posted']); ?></h6>
                            <h5 class="card-title"><?php echo htmlspecialchars($announcement['title']); ?></h5>
                            <p class="card-text"><?php echo nl2br(htmlspecialchars($announcement['content'])); ?></p>
                            <?php if ($announcement['image_path']): ?>
                                <img src="<?php echo htmlspecialchars($announcement['image_path']); ?>" class="img-fluid announcement-image" alt="Announcement Image">
                            <?php endif; ?>
                            <button class="btn btn-primary share-button" 
                                    onclick="shareAnnouncement(
                                        <?php echo $announcement['announcement_id']; ?>, 
                                        '<?php echo addslashes(htmlspecialchars($announcement['title'])); ?>', 
                                        '<?php echo addslashes(htmlspecialchars($announcement['content'])); ?>', 
                                        window.location.href
                                    )">
                                Share <i class="bi bi-share"></i> <span id="share-count-<?php echo $announcement['announcement_id']; ?>"><?php echo $announcement['share_count']; ?></span>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No announcements found.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
