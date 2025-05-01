<?php
session_start();
session_destroy(); // Destroy all session data
header("Location: sign-in.php"); // Redirect to the sign-in page
exit();
?>