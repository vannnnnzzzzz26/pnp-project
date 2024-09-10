<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container-fluid">

    
    <button class="sidebar-toggler" type="button" onclick="toggleSidebar()">
        <i class="bi bi-grid-fill large-icon"></i><span class="nav-text menu-icon-text">Menu</span>
        <img src="../assets/complaints.png" alt="Sample Image" width="9%" height="9%" style="margin-left: 1rem;">

        </button>
        <a class="navbar-brand" href="#">PNP Monitoring Complaints</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- Notification Bell Icon with Popover -->
         

            <button type="button" class="btn btn-secondary ms-auto my-2 my-lg-0" id="notificationButton"
        data-bs-toggle="popover" data-bs-html="true" title="Notifications" 
        data-bs-content="<div id='notificationList' class='d-flex flex-nowrap p-2' style='max-height: 300px; overflow-x: auto; white-space: nowrap;'><div class='d-flex flex-row'><div class='dropdown-item text-center'>No new notifications</div></div></div>">
    <i class="bi bi-bell" style="color: yellow;"></i>
    <span class="badge bg-danger d-none" id="notificationCount">0</span>
</button>
        </div>
    </div>
</nav>