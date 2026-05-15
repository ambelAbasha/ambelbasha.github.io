<?php
// Connect to the database
$servername = "localhost";
$dbusername = "root"; // Database username
$dbpassword = ""; // Database password
$dbname = "musicdb";

// Establish the connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . htmlspecialchars($conn->connect_error));
}
?>
