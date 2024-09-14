<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container-fluid">
        <!-- Sidebar Toggler Button -->
        <button class="sidebar-toggler btn btn-outline-primary" type="button" onclick="toggleSidebar()">
            <i class="bi bi-grid-fill large-icon"></i>
            <span class="nav-text menu-icon-text d-none d-lg-inline">Menu</span>
            <img src="../assets/complaints.png" alt="Sample Image" width="9%" height="9%" class="ms-2 d-none d-lg-inline">
        </button>
        
        <!-- Navbar Brand -->
        <a class="navbar-brand" href="#">Residents Account</a>

        <!-- Navbar Toggler for Collapsible Menu -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Content -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- Notifications Button with Popover -->
            <button type="button" class="btn btn-secondary ms-auto my-2 my-lg-0" id="notificationButton"
                data-bs-toggle="popover" data-bs-html="true" title="Notifications" 
                data-bs-content="<div id='notificationList' class='d-flex flex-nowrap p-2' style='max-height: 300px; overflow-x: auto; white-space: nowrap;'><div class='d-flex flex-row'><div class='dropdown-item text-center'>No new notifications</div></div></div>">
                <i class="bi bi-bell" style="color: yellow;"></i>
                <span class="badge bg-danger d-none" id="notificationCount">0</span>
            </button>
        </div>
    </div>
</nav>