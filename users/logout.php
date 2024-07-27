<?php
// Start the session
session_start();

// Destroy the session
session_destroy();

// Redirect to the login page or home page
header("Location: user_login.php");
exit;
?>
