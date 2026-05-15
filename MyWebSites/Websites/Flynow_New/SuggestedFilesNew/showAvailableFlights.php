<?php
// Secure database connection
$con = new mysqli($servername, $username, $password, $dbname);

if ($con->connect_error) {
    die("Database connection error: " . $con->connect_error);
}

// Retrieve available flights
$query = "SELECT * FROM flight";
$result = $con->query($query);

// Display available flights
while ($row = $result->fetch_assoc()) {
    echo "<div>";
    echo "Flight ID: " . $row['flight_id'] . "<br>";
    echo "Departure: " . $row['departure_airport'] . "<br>";
    echo "Destination: " . $row['destination_airport'] . "<br>";
    echo "Departure Date: " . $row['departure_date'] . "<br>";
    echo "<a href='book_flight.php?flight_id=" . $row['flight_id'] . "'>Book this Flight</a>"; // Link to booking
    echo "</div><br>";
}

$con->close();
?>
