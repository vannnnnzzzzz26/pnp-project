 <div  style="margin-top: 5rem;" class="sidebar" id="sidebar">
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
            <a class="nav-link" href="manage-complaints.php">
                <i class="bi bi-file-earmark-text large-icon"></i><span class="nav-text">Resident's Complaint</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="barangay-responder.php">
                <i class="bi bi-file-earmark-text large-icon"></i><span class="nav-text">Barangay Responder</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="barangaylogs.php">
            <i class="bi bi-check-square-fill large-icon"></i><span class="nav-text">Complaints Histrory</span>
            </a>
        </li>

        
        <li class="nav-item">
            <a class="nav-link" href="barangay-official.php">
                <i class="bi bi-person large-icon"></i><span class="nav-text">Barangay Officials</span>
            </a>
        </li>


        <li class="nav-item">
            <a class="nav-link" href="barangay_dashboard.php">
                <i class="bi bi-person large-icon"></i><span class="nav-text">dasboard</span>
            </a>
        </li>
     
     
    </ul>
    
        <!-- Logout Form -->
        <form action="../logout.php" method="post" id="logoutForm">

            <div class="logout-btn">
                <button type="button" class="btn btn-danger btn-sm" onclick="confirmLogout()">
                    <i class="bi bi-box-arrow-left"></i><span class="nav-text">Logout</span>
                </button>
            </div>
        </form>

        
    </div>

    
