<?php
// Start the session if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Debugging: Check if the return date is set in the session
if (!isset($_SESSION['returnDate'])) {
    var_dump($_SESSION); // Check session variables for debugging
    die("Error: Return date not set.");
}

// Database connection using mysqli
$con = new mysqli("localhost", "root", "", "flynow");
if ($con->connect_error) {
    die("Database connection failed: " . $con->connect_error);
}

// Validate session variables
$requiredFields = ['toSelect', 'fromSelect', 'personSelect', 'airlineSelect'];
foreach ($requiredFields as $field) {
    if (!isset($_SESSION[$field])) {
        die("Error: Required session variable '$field' not found.");
    }
}

// Fetch airline ID based on the user's airline choice
$airline_name = strtolower(trim($_SESSION['airlineSelect']));
$airline_query = "SELECT air_com_id FROM airline_company WHERE LOWER(comp_name) LIKE ?";
$stmt = $con->prepare($airline_query);
$like_airline_name = "%$airline_name%";
$stmt->bind_param('s', $like_airline_name);
$stmt->execute();
$airline_result = $stmt->get_result();

if ($airline_result->num_rows === 0) {
    die("Error: Specified airline '$airline_name' not found.");
}

$airline_row = $airline_result->fetch_assoc();
$airline_id = $airline_row['air_com_id'];

// Prepare SQL to fetch return flights
$return_airport = strtolower(trim($_SESSION['toSelect']));
$return_destination = strtolower(trim($_SESSION['fromSelect']));
$person_count = intval($_SESSION['personSelect']);
$return_date = date('Y-m-d', strtotime($_SESSION['returnDate'])); // Ensure returnDate is in the correct format

$return_query = "SELECT * FROM flight 
                 WHERE LOWER(departure_airport) = ?
                   AND LOWER(destination_airport) = ?
                   AND economy_seat >= ?
                   AND air_com_id = ?
                   AND DATE(departure_date) = ?";

$stmt = $con->prepare($return_query);
$stmt->bind_param('ssiss', $return_airport, $return_destination, $person_count, $airline_id, $return_date);
$stmt->execute();
$return_result = $stmt->get_result();

// Output the results for return flights
echo '<div class="results-container">';
if ($return_result->num_rows > 0) {
    echo "<h1>Return Flights:</h1>";
    echo '<form id="returnForm" method="post" action="insert.php">';
    echo '<table class="CSSTable">';
    echo '<tr>
            <th>Select</th>
            <th>Flight ID</th>
            <th>Departure Airport</th>
            <th>Destination Airport</th>
            <th>Airline</th>
            <th>Departure Date</th>
            <th>Price Economy</th>
          </tr>';

    while ($row = $return_result->fetch_assoc()) {
        echo "<tr>
              <td><input type='checkbox' name='selectedReturnFlights[]' value='" . htmlspecialchars($row['flight_id']) . "'></td>
              <td>" . htmlspecialchars($row['flight_id']) . "</td>
              <td>" . htmlspecialchars($row['departure_airport']) . "</td>
              <td>" . htmlspecialchars($row['destination_airport']) . "</td>
              <td>" . htmlspecialchars($airline_name) . "</td>
              <td>" . htmlspecialchars($row['departure_date']) . "</td>
              <td>" . htmlspecialchars($row['price_economy']) . "</td>
              </tr>";
    }

    // CSRF token for security
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '">';
    echo '</table>';
    echo '<input id="bookReturnButton" type="submit" name="bookReturnButton" value="Book Selected Return Flights">';
    echo '</form>';
} else {
    echo '<p>No return flights found with the given criteria.</p>';
}
echo '</div>';

// Close the database connection
$con->close();
?>
