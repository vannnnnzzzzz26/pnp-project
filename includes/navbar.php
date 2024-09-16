<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container-fluid">

    <button class="sidebar-toggler" type="button" onclick="toggleSidebar()">
        <i class="bi bi-grid-fill large-icon"></i><span class="nav-text menu-icon-text">Menu</span>
        </button>

        <img src="../assets/logo.png" alt="Sample Image" width="4%" height="6%" style="margin-left: 1rem;">

        <a class="navbar-brand" href="#">Barangay Complaints</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- Search Bar -->
            <form class="d-flex ms-auto my-2 my-lg-0" role="search" method="get" action="./barangaylogs.php">
                <input class="form-control me-2" type="search" name="search" placeholder="Search complaints by name" aria-label="Search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <button class="btn btn-outline-light" type="submit">Search</button>
            </form>

            <button type="button" class="btn btn-no-border ms-3 my-2 my-lg-0" id="notificationButton" 
        data-bs-toggle="popover" data-bs-html="true" title="Notifications" 
        data-bs-content="<div id='notificationList' class='d-flex flex-nowrap p-2' style='max-height: 300px; overflow-x: auto; white-space: nowrap;'><div class='d-flex flex-row'><div class='dropdown-item text-center'>No new notifications</div></div></div>">
    <i class="bi bi-bell" style="color: yellow;"></i>
    <span class="badge bg-danger d-none" id="notificationCount">0</span>
</button>

        </div>
    </div>
</nav>