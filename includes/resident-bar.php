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
            
                <a class="nav-link active" href="resident.php"> <i class="bi bi-house-door-fill"></i><span class="nav-text">Complaints</span></a>
            
        </li>
        <li class="nav-item">
            
            <a class="nav-link" href="complainants_logs.php"><i class="bi bi-journal-text"></i><span class="nav-text">Complaints Logs</span></a>
            
        </li>
        <li class="nav-item">
        <a class="nav-link" href="barangay-official.php"><i class="bi bi-person-check-fill"></i><span class="nav-text">Barangay Official</span></a>
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

    
