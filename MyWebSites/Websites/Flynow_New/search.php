<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Search Results</title>
    <link href="./Styles/Search/styles.css" rel="stylesheet" type="text/css"/>
    <script src="./LoadMyFlights/Scripts/Calculate/calculateTotal.js" defer></script>
</head>
<body>
    <div class="main-container">

        <?php
        session_start();

        // Initialize or increment the session counter
        if (!isset($_SESSION['counter'])) {
            $_SESSION['counter'] = 0;
        }
        $_SESSION['counter']++;

        // Reset the session if the counter exceeds 10
        if ($_SESSION['counter'] > 10) {
            session_unset();
            session_destroy();
            session_start();
            $_SESSION['counter'] = 1;
        }

        // Validate required fields and store them in the session
        $requiredFields = ['fromSelect', 'toSelect', 'airlineSelect', 'departureDate', 'classSelect', 'personSelect'];
        $missingFields = [];

        foreach ($requiredFields as $field) {
            if (empty(trim($_POST[$field]))) {
                $missingFields[] = $field; // Track missing fields
            } else {
                $_SESSION[$field] = htmlspecialchars(trim($_POST[$field])); // Store field in session
            }
        }

        // Capture the return date if provided
        if (!empty(trim($_POST['returnDate']))) {
            $_SESSION['returnDate'] = htmlspecialchars(trim($_POST['returnDate']));
        }

        // Display error if any required field is missing
        if (!empty($missingFields)) {
            die("Error: Missing fields: " . implode(", ", $missingFields));
        }

        // Database connection
        $con = new mysqli("localhost", "root", "", "flynow");
        if ($con->connect_error) {
            die("Database connection failed: " . $con->connect_error);
        }

        // Fetch airline ID based on the user's airline choice
        $airline_name = strtolower(trim($_SESSION['airlineSelect']));
        $airline_query = "SELECT air_com_id, comp_name FROM airline_company WHERE LOWER(comp_name) LIKE ?";
        $stmt = $con->prepare($airline_query);
        $like_airline_name = "%$airline_name%";
        $stmt->bind_param('s', $like_airline_name);
        $stmt->execute();
        $airline_result = $stmt->get_result();

        if ($airline_result->num_rows === 0) {
            die("Error: Specified airline '$airline_name' not found.");
        }

        // Initialize price variables
        $departure_price = 0;
        $return_price = 0;

        $airline_row = $airline_result->fetch_assoc();
        $airline_id = $airline_row['air_com_id'];
        $airline_display_name = htmlspecialchars($airline_row['comp_name']);

        // Prepare SQL to fetch departure flights
        $departure_airport = strtolower(trim($_SESSION['fromSelect']));
        $destination_airport = strtolower(trim($_SESSION['toSelect']));
        $person_count = intval($_SESSION['personSelect']);
        $departure_date = date('Y-m-d', strtotime($_SESSION['departureDate']));

        // Departure flight query
        $departure_query = "SELECT flight_id, departure_airport, destination_airport, 
                                   departure_date, return_date, price_economy, price_business, economy_seat, 
                                   business_class 
                            FROM flight 
                            WHERE LOWER(departure_airport) = ? 
                              AND LOWER(destination_airport) = ? 
                              AND economy_seat >= ? 
                              AND air_com_id = ? 
                              AND DATE(departure_date) = ?";

        $stmt = $con->prepare($departure_query);
        $stmt->bind_param('ssiss', $departure_airport, $destination_airport, $person_count, $airline_id, $departure_date);
        $stmt->execute();
        $departure_result = $stmt->get_result();

        // Display departure flights
        if ($departure_result->num_rows > 0) {
            echo '<div class="departure-container" id="departure">';
            echo '<div class="header" id="departure-header"><h1>Departure Flights:</h1></div>';
            echo '<form id="departureForm" method="post" action="insert.php">';
            echo '<table class="CSSTable">';
            echo '<tr>
                    <th>Select</th>
                    <th>Flight ID</th>
                    <th>Departure Airport</th>
                    <th>Destination Airport</th>
                    <th>Airline</th>
                    <th>Departure Date</th>
                    <th>Arrival Date</th>
                    <th>Class Available</th>
                    <th>Price</th>
                  </tr>';

            while ($row = $departure_result->fetch_assoc()) {
                // Show only the selected class and its price
                $classOptions = '';
                $priceDisplay = ''; // Initialize price display
                if ($_SESSION['classSelect'] === 'Business' && $row['business_class'] >= $person_count) {
                    $classOptions = "Business";
                    $priceDisplay = htmlspecialchars($row['price_business']);
                } elseif ($_SESSION['classSelect'] === 'Economy' && $row['economy_seat'] >= $person_count) {
                    $classOptions = "Economy";
                    $priceDisplay = htmlspecialchars($row['price_economy']);
                }

                // Output the flight information
                if ($classOptions) {
                    echo "<tr>
                            <td>
                            <div class='checkbox'>
                                <input type='checkbox' data-price='" . htmlspecialchars($priceDisplay) . "' name='selectedFlights[]' value='" . htmlspecialchars($row['flight_id']) . "' id='flight-" . htmlspecialchars($row['flight_id']) . "' onchange='calculateTotal()'>
                                    <label for='flight-" . htmlspecialchars($row['flight_id']) . "'></label>
                            </div>
                            </td>
                          <td>" . htmlspecialchars($row['flight_id']) . "</td>
                          <td>" . htmlspecialchars($row['departure_airport']) . "</td>
                          <td>" . htmlspecialchars($row['destination_airport']) . "</td>
                          <td>" . htmlspecialchars($airline_display_name) . "</td>
                          <td>" . htmlspecialchars($row['departure_date']) . "</td>
                          <td>" . htmlspecialchars($row['return_date']) . "</td>
                          <td>" . $classOptions . "</td>
                          <td>€" . $priceDisplay . "</td>
                          </tr>";
                }
            }

            // CSRF token for security
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '">';
            echo '</table>';

            // Add total price display for departure flights
            $initialDeparturePrice = $classOptions ? htmlspecialchars($priceDisplay) : '0.00';
            echo '<h2>Departure Price: €<span id="departurePrice">' . $initialDeparturePrice . '</span></h2>';

            echo '</form>';
        } else {
            echo '<p class="no-flights-message">No departure flights found with the given criteria.</p>';
        }
        echo '</div>';

        // Fetch return flights if return date is set
        if (isset($_SESSION['returnDate'])) {
            $return_airport = strtolower(trim($_SESSION['toSelect']));
            $return_destination = strtolower(trim($_SESSION['fromSelect']));
            $return_date = date('Y-m-d', strtotime($_SESSION['returnDate']));

            $return_query = "SELECT flight_id, departure_airport, destination_airport, 
                                     departure_date, return_date, price_economy, price_business, economy_seat, 
                                     business_class 
                             FROM flight 
                             WHERE LOWER(departure_airport) = ? 
                               AND LOWER(destination_airport) = ? 
                               AND economy_seat >= ? 
                               AND air_com_id = ? 
                               AND DATE(departure_date) = ?";

            $stmt = $con->prepare($return_query);
            $stmt->bind_param('ssiss', $return_airport, $return_destination, $person_count, $airline_id, $return_date);
            $stmt->execute();
            $return_result = $stmt->get_result();

            if ($return_result->num_rows > 0) {
                echo '<div class="return-container" id="return">';
                echo '<div class="header" id="return-header"><h1>Return Flights:</h1></div>';
                echo '<form id="returnForm" method="post" action="insert.php">';
                echo '<table class="CSSTable">';

                echo '<tr>
                        <th>Select</th>
                        <th>Flight ID</th>
                        <th>Departure Airport</th>
                        <th>Destination Airport</th>
                        <th>Airline</th>
                        <th>Departure Date</th>
                        <th>Arrival Date</th>
                        <th>Class Available</th>
                        <th>Price</th>
                      </tr>';

                while ($row = $return_result->fetch_assoc()) {
                    $classOptions = '';
                    $priceDisplay = '';

                    if ($_SESSION['classSelect'] === 'Business' && $row['business_class'] >= $person_count) {
                        $classOptions = "Business";
                        $priceDisplay = htmlspecialchars($row['price_business']);
                    } elseif ($_SESSION['classSelect'] === 'Economy' && $row['economy_seat'] >= $person_count) {
                        $classOptions = "Economy";
                        $priceDisplay = htmlspecialchars($row['price_economy']);
                    }

                    if ($classOptions) {
                        echo "<tr>
                                <td>
                                    <div class='checkbox'>
                                        <input type='checkbox' data-price='" . htmlspecialchars($priceDisplay) . "' 
                                            name='selectedReturnFlights[]' value='" . htmlspecialchars($row['flight_id']) . "' 
                                            id='return-flight-" . htmlspecialchars($row['flight_id']) . "' 
                                            onchange='calculateTotal()'>
                                        <label for='return-flight-" . htmlspecialchars($row['flight_id']) . "'></label>
                                    </div>
                                </td>
                                <td>" . htmlspecialchars($row['flight_id']) . "</td>
                                <td>" . htmlspecialchars($row['departure_airport']) . "</td>
                                <td>" . htmlspecialchars($row['destination_airport']) . "</td>
                                <td>" . htmlspecialchars($airline_display_name) . "</td>
                                <td>" . htmlspecialchars($row['departure_date']) . "</td>
                                <td>" . htmlspecialchars($row['return_date']) . "</td>
                                <td>" . $classOptions . "</td>
                                <td>€" . $priceDisplay . "</td>
                              </tr>";
                    }
                }

                echo '</table>';

                // Ensure the CSRF token is included in the form
                echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '">';

                // Ensure the return price is inside the return-container
                echo '<h2>Return Price: €<span id="returnPrice">0.00</span></h2>';

                echo '<input type="submit" value="Book Selected Flights">';
                echo '</form>';
            } else {
                echo '<p class="no-flights-message">No return flights found with the given criteria.</p>';
            } 
            echo '</div>'; // Close .return-container properly           
        }
        ?>

    <!-- Move the Total Price inside the main-container -->
    <div class="total-container" style="display: none;">
    <div class="total-price" id="total-class-sum">
        <h2>Total Price: €<span id="totalPrice">0.00</span></h2>
    </div>
</div>

</div> <!-- End of .main-container -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const submitButton = document.querySelector('input[type="submit"]');
    const departureCheckboxes = document.querySelectorAll('input[name="selectedFlights[]"]');
    const returnCheckboxes = document.querySelectorAll('input[name="selectedReturnFlights[]"]');

    // Function to check if both checkboxes are selected
    function checkSelection() {
        const isDepartureSelected = Array.from(departureCheckboxes).some(checkbox => checkbox.checked);
        const isReturnSelected = Array.from(returnCheckboxes).some(checkbox => checkbox.checked);

        // Enable or disable the submit button based on checkbox selection
        submitButton.disabled = !(isDepartureSelected && isReturnSelected);
    }

    // Attach change event listeners to the checkboxes
    departureCheckboxes.forEach(checkbox => checkbox.addEventListener('change', checkSelection));
    returnCheckboxes.forEach(checkbox => checkbox.addEventListener('change', checkSelection));

    // Initial check on page load
    checkSelection();
});
</script>

</body>
</html>
