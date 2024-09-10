 <div style="margin-top: 5rem;" class="sidebar" id="sidebar">
        <!-- Toggle button inside sidebar -->
        

        <!-- User Information -->
        <div class="user-info px-3 py-2 text-center">
            <!-- Your PHP session-based content -->
            <?php
            include '../connection/dbconn.php'; 


            if (isset($_SESSION['pic_data'])) {
                $pic_data = $_SESSION['pic_data'];
                echo "<img class='profile' src='$pic_data' alt='Profile Picture'>";
            }
            ?>
            <p class='white-text'> <?php echo $_SESSION['accountType']; ?></p>
            <h5 class='white-text'>User Information</h5>
            <p class='white-text'><?php echo $email; ?></p>
            <p class='white-text'><?php echo "$firstName $middleName $lastName $extensionName"; ?></p>
        </div>


        <!-- Menu items -->
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="pnp.php">
                    <i class="bi bi-file-earmark-text large-icon"></i><span class="nav-text">Barangay Complaints</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="pnplogs.php">
                    <i class="bi bi-file-earmark-text large-icon"></i><span class="nav-text">Complaints History</span>
                </a>
            </li>
            <li class="nav-item">
               

                <li class="nav-item">
                <a class="nav-link" href="pnp-announcement.php">
                    <i class="bi bi-check-square-fill large-iconn"></i><span class="nav-text">Announcements</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php">
                    <i class="bi bi-graph-up"></i><span class="nav-text">Dashboard </span>
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

