<?php
$search_query = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">PNP Monitoring Complaints</a>

        <!-- Search Bar -->
        <form class="d-flex ms-auto" role="search" method="get" action="./pnplogs.php">
            <input class="form-control me-2" type="search" name="search" placeholder="Search complaints by name" aria-label="Search" value="<?php echo $search_query; ?>">
            <button class="btn btn-outline-light" type="submit">Search</button>
        </form>
    </div>
</nav>
