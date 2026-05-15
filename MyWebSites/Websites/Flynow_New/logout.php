<?php
session_start(); // Start the session

// Unset session variables related to login
unset($_SESSION['login']);
unset($_SESSION['username']);

// Optionally destroy the session entirely
session_destroy(); // This will clear all session data

// Redirect to the index page after logout
header("Location: index.php");
exit; // Ensure no further code is executed after the redirect
?>
