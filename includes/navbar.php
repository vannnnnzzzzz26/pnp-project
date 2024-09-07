<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">PNP Monitoring Complaints</a>

        <!-- Search Bar -->
        <form class="d-flex ms-auto" role="search" method="get" action="./barangaylogs.php">
            <input class="form-control me-2" type="search" name="search" placeholder="Search complaints by name" aria-label="Search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <button class="btn btn-outline-light" type="submit">Search</button>
        </form>
        <button type="button" class="btn btn-secondary" id="notificationButton" data-bs-toggle="popover" data-bs-html="true" title="Notifications" data-bs-content="<div id='notificationList' class='d-flex flex-nowrap p-2' style='max-height: 300px; overflow-x: auto; white-space: nowrap;'><div class='d-flex flex-row'><div class='dropdown-item text-center'>No new notifications</div></div></div>">
    <i class="bi bi-bell"></i>
    <span class="badge bg-danger d-none" id="notificationCount">0</span>
</button>

        <!-- Button to toggle sidebar visibility -->
        
    </div>
</nav>
