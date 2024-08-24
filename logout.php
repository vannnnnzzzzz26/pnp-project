<?php
session_start();
session_unset();
session_destroy();
header("Location: /pnp/login.php"); // Adjust the path if necessary
exit();
?>
