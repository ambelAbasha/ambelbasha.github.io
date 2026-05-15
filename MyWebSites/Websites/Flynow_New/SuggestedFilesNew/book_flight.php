<?php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['user_id'])) {
    die("Please log in to book a flight.");
}

if (isset($_GET['flight_id'])) {
    $flight_id = $_GET['flight_id'];
    
    // Secure database connection
    $con = new mysqli($servername, $username, $password, $dbname);

    if ($con->connect_error) {
        die("Database connection error: " . $con->connect_error);
    }

    // Find the passenger associated with the logged-in user
    $query = "SELECT passenger_id FROM passenger WHERE user_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $passenger_id = $row['passenger_id'];

        // Insert a new booking
        $booking_query = "INSERT INTO booking (passenger_id, flight_id, booking_date) VALUES (?, ?, NOW())";
        $booking_stmt = $con->prepare($booking_query);
        $booking_stmt->bind_param("ii", $passenger_id, $flight_id);
        $booking_stmt->execute();

        echo "Flight successfully booked!";
    } else {
        echo "No passenger information found for the current user.";
    }

    $stmt->close();
    $booking_stmt->close();
    $con->close();
} else {
    echo "No flight ID provided.";
}
?>
