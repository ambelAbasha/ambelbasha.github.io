<?php
// Start the session and check if user is logged in
session_start(); // Start the session

if (!isset($_SESSION['username'])) {
    header("Location: login_user.php"); // Redirect if not logged in
    exit; // Prevent further code execution
}
?>

