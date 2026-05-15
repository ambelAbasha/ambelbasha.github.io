<?php
session_start(); // Start the session

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Error: You must be logged in to view your booking confirmation.");
}

// Fetch flight IDs from the query parameters
$flight_ids = isset($_GET['flight_ids']) ? explode(',', $_GET['flight_ids']) : [];

if (empty($flight_ids)) {
    die("Error: No flight IDs provided.");
}

// Database connection
$con = new mysqli("localhost", "root", "", "flynow");
if ($con->connect_error) {
    error_log("Database connection failed: " . $con->connect_error);
    die("We are experiencing technical issues. Please try again later.");
}

// Fetch flight details based on booked flight IDs
$flightDetails = [];
foreach ($flight_ids as $flight_id) {
    $stmt = $con->prepare("SELECT flight_number, departure, arrival FROM flights WHERE flight_id = ?");
    if (!$stmt) {
        error_log("Failed to prepare SELECT statement: " . $con->error);
        continue;
    }

    $stmt->bind_param("i", $flight_id);
    $stmt->execute();
    $stmt->bind_result($flight_number, $departure, $arrival);
    while ($stmt->fetch()) {
        $flightDetails[] = [
            'flight_number' => $flight_number,
            'departure' => $departure,
            'arrival' => $arrival
        ];
    }
    $stmt->close();
}

// Display confirmation
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./Styles/Confirmation/styles.css"> <!-- Link the CSS file -->
    <title>Booking Confirmation</title>
</head>
<body>
    <h1>Booking Confirmation</h1>
    <p>Thank you for booking your flights with us!</p>

    <h2>Your booked flights:</h2>
    <ul>
        <?php foreach ($flightDetails as $flight): ?>
            <li>
                Flight Number: <?php echo htmlspecialchars($flight['flight_number'], ENT_QUOTES); ?><br>
                Departure: <?php echo htmlspecialchars($flight['departure'], ENT_QUOTES); ?><br>
                Arrival: <?php echo htmlspecialchars($flight['arrival'], ENT_QUOTES); ?><br>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="nav-container">
        <a href="index.php" class="nav-triangle left">Back to Home Page</a>
        <a href="./LoadMyFlights/loadMyFlights.php" class="nav-triangle right">Show My Bookings</a>
    </div>
</body>
</html>
