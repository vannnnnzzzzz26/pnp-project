<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">Excel</a>

        <!-- Search Bar -->
        <form class="d-flex ms-auto" role="search" method="get" action="./pnplogs.php">
            <input class="form-control me-2" type="search" name="search" placeholder="Search complaints by name" aria-label="Search" value="<?php echo htmlspecialchars($search_query); ?>">
            <button class="btn btn-outline-light" type="submit">Search</button>
        </form>
      
     
</nav>
