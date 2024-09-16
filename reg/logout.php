<?php
session_start();
session_unset();
session_destroy();
header("Location:../reg/login.php"); // Adjust the path if necessary
exit();
?>
