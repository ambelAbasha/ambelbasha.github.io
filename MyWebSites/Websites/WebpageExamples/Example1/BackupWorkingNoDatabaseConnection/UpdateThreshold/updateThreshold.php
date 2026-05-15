<?php
include('db.php');

// Check if the request is a POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['threshold'], $_POST['value'])) {
    $threshold = $_POST['threshold'];
    $value = $_POST['value'];

    // Sanitize inputs
    $threshold = intval($threshold);
    $value = intval($value);

    // Prepare the SQL statement to update the threshold
    $sql = "UPDATE thresholds SET threshold_$threshold = ? WHERE id = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $value);

    if ($stmt->execute()) {
        // Return success response
        echo json_encode(['success' => true, 'message' => 'Threshold updated successfully!']);
    } else {
        // Return error response
        echo json_encode(['success' => false, 'message' => 'Error updating threshold.']);
    }

    // Close the prepared statement and connection
    $stmt->close();
} else {
    // Return error response if the data is not set properly
    echo json_encode(['success' => false, 'message' => 'Invalid request data.']);
}

// Close the database connection
$conn->close();
?>
