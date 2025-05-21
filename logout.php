<?php
session_start();
session_unset();
session_destroy();
header("Location: land-page.php"); // or your login page
exit();
?>