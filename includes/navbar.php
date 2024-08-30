<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">PNP Monitoring Complaints</a>

        <!-- Search Bar -->
        <form class="d-flex ms-auto" role="search" method="get" action="./barangaylogs.php">
            <input class="form-control me-2" type="search" name="search" placeholder="Search complaints by name" aria-label="Search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <button class="btn btn-outline-light" type="submit">Search</button>
        </form>

        <!-- Button to toggle sidebar visibility -->
        
    </div>
</nav>
