<?php
// Start the session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}

// Database connection
$servername = "localhost"; // Replace with your server name
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "flynow";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Query to get booked flights for the logged-in user
$sql = "
    SELECT b.booking_id, f.flight_id, f.departure_date, f.arrival_date, 
           f.destination_airport, f.departure_airport, ac.comp_name
    FROM bookings b
    JOIN flight f ON b.flight_id = f.flight_id
    JOIN airline_company ac ON f.air_com_id = ac.air_com_id
    WHERE b.passenger_id = ?
";

// Prepare and bind
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are bookings
if ($result->num_rows > 0) {
    // Display bookings
    echo "<h1>Your Booked Flights</h1>";
    echo "<table border='1'>
            <tr>
                <th>Booking ID</th>
                <th>Flight ID</th>
                <th>Departure Date</th>
                <th>Arrival Date</th>
                <th>Destination Airport</th>
                <th>Departure Airport</th>
                <th>Airline</th>
            </tr>";

    // Fetch data
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['booking_id']}</td>
                <td>{$row['flight_id']}</td>
                <td>{$row['departure_date']}</td>
                <td>{$row['arrival_date']}</td>
                <td>{$row['destination_airport']}</td>
                <td>{$row['departure_airport']}</td>
                <td>{$row['comp_name']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<h2>You have no booked flights.</h2>";
}

// Close connection
$stmt->close();
$conn->close();
?>
