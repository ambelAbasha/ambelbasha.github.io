<?php 
include('../db.php');

// Query to fetch threshold values from the 'thresholds' table
$sqlThresholds = "SELECT * FROM thresholds LIMIT 1";
$stmtThresholds = $conn->prepare($sqlThresholds);
$stmtThresholds->execute();
$thresholdsResult = $stmtThresholds->get_result();

// Check if we have data
if ($thresholdsResult->num_rows > 0) {
    $thresholds = $thresholdsResult->fetch_assoc();
} else {
    $thresholds = [
        'threshold_100' => 100,
        'threshold_50' => 50,
        'threshold_15' => 15,
        'threshold_14' => 14
    ]; // Default values in case of an empty result
}

// Return the thresholds as a JSON response
echo json_encode($thresholds);

// Close the database connection
$conn->close();
?>
