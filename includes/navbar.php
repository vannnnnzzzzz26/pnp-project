<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        <!-- Sidebar Toggler Button -->
        <button class="sidebar-toggler d-flex align-items-center" type="button" onclick="toggleSidebar()">
            <i class="bi bi-grid-fill large-icon"></i>
            <span class="nav-text menu-icon-text ms-2">Menu</span>
        </button>

        <!-- Logo (Visible only on large screens) -->
        <img src="../assets/logo.png" alt="Sample Image" width="4%" height="6%" class="ms-2 d-none d-lg-inline">

        <!-- Navbar Brand -->
        <a class="navbar-brand mx-auto ms-lg-3" href="#"> <p class='white-text'><?php echo "Barangay $barangay_name "; ?></p>        </a>

        <!-- Navbar Toggler for Collapsible Menu -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <div class="d-flex align-items-center ms-auto">
                <!-- Search Bar -->
                <form class="d-flex me-3 my-2 my-lg-0" role="search" method="get" action="./barangaylogs.php">
                    <input class="form-control me-2" type="search" name="search" placeholder="Registered Complainants" aria-label="Search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    <button class="btn btn-outline-light" type="submit">Search</button>
                </form>

                <!-- Notifications Button with Popover -->
                <button type="button" class="btn btn-no-border my-2 my-lg-0" id="notificationButton"
                    data-bs-toggle="popover" data-bs-html="true" title="Notifications"
                    data-bs-content="<div id='notificationList' class='d-flex flex-nowrap p-2' style='max-height: 300px; overflow-x: auto; white-space: nowrap;'><div class='d-flex flex-row'><div class='dropdown-item text-center'>No new notifications</div></div></div>">
                    <i class="bi bi-bell" style="color: yellow;"></i>
                    <span class="badge bg-danger d-none" id="notificationCount">0</span>
                </button>
            </div>
        </div>
    </div>
</nav>
