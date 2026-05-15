<?php
session_start();  // Start the session to store messages

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sales_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    $_SESSION['db_message'] = [
        'type' => 'error',
        'message' => 'Connection failed: ' . $conn->connect_error
    ];
    die();
}

// Query the thresholds table
$sql = "SELECT * FROM thresholds WHERE id = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output the current threshold values
    $row = $result->fetch_assoc();
    $_SESSION['db_message'] = [
        'type' => 'success',
        'message' => 'Thresholds retrieved successfully'
    ];
} else {
    $_SESSION['db_message'] = [
        'type' => 'error',
        'message' => 'No thresholds found in the database'
    ];
}

// Do not close the connection here so that it's available for other queries
?>
