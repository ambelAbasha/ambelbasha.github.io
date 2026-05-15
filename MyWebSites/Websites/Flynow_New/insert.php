<?php 
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Error: You must be logged in to book a flight.");
}

// Database connection
$con = new mysqli("localhost", "root", "", "flynow");
if ($con->connect_error) {
    error_log("Database connection failed: " . $con->connect_error);
    die("We are experiencing technical issues. Please try again later.");
}

// CSRF token handling
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Fetch user ID from the session
$userId = $_SESSION['user_id'];

// Ensure userId is not null
if (empty($userId)) {
    die("Error: User ID cannot be null. Please log in.");
}

// Function to fetch or insert a passenger and return their ID
function getPassengerId($con, $firstName, $lastName, $city, $passengerType, $userId) {
    $stmt = $con->prepare("SELECT passenger_id FROM passenger WHERE p_name = ? AND p_lastname = ? AND city = ? AND passenger_type = ? AND user_id = ?");
    if (!$stmt) {
        error_log("Failed to prepare SELECT statement: " . $con->error);
        return false;
    }

    $stmt->bind_param("ssssi", $firstName, $lastName, $city, $passengerType, $userId);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($passenger_id);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
    } else {
        $stmt->close();
        $stmt = $con->prepare("INSERT INTO passenger (p_name, p_lastname, city, passenger_type, user_id) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            error_log("Failed to prepare INSERT statement: " . $con->error);
            return false;
        }
        $stmt->bind_param("ssssi", $firstName, $lastName, $city, $passengerType, $userId);
        if (!$stmt->execute()) {
            error_log("Error inserting passenger: " . $stmt->error);
            return false;
        }
        $passenger_id = $stmt->insert_id;
    }
    $stmt->close();
    return $passenger_id;
}

function renderPassengerFields($firstName = "", $lastName = "", $city = "", $passengerType = "") {
    echo '
        <p>
            <label for="firstNameField">First Name:</label>
            <input type="text" name="firstNameField" value="' . htmlspecialchars($firstName, ENT_QUOTES) . '" required>
        </p>
        <p>
            <label for="lastNameField">Last Name:</label>
            <input type="text" name="lastNameField" value="' . htmlspecialchars($lastName, ENT_QUOTES) . '" required>
        </p>
        <p>
            <label for="cityField">City:</label>
            <input type="text" name="cityField" value="' . htmlspecialchars($city, ENT_QUOTES) . '" required>
        </p>
        <p>
            <label for="passengerType">Passenger Type:</label>
            <select name="passengerType" id="passengerType" required>
                <option value="">Select a type</option>
                <option value="Adult" ' . ($passengerType === "Adult" ? "selected" : "") . '>Adult</option>
                <option value="Child" ' . ($passengerType === "Child" ? "selected" : "") . '>Child</option>
                <option value="Infant" ' . ($passengerType === "Infant" ? "selected" : "") . '>Infant</option>
            </select>
        </p>';
}

$firstName = $lastName = $city = $passengerType = '';
if ($userId) {
    $stmt = $con->prepare("SELECT p_name, p_lastname, city, passenger_type FROM passenger WHERE user_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($firstName, $lastName, $city, $passengerType);
        $stmt->fetch();
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./Styles/Insert/styles.css">
    <title>Book Flight</title>
    <!--<style>
        /* Modal styling */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); }
        .modal-content { background-color: #fff; margin: 15% auto; padding: 20px; border-radius: 8px; width: 300px; text-align: center; }
        .modal-buttons button { margin: 5px; }
    </style> -->
</head>
<body>
    <div class="form-container">
        <form id="messForm" name="messForm" method="post">
            <p>
                <label for="selectedFlights">Flight's ID(s):</label>
                <input type="text" name="selectedFlights" id="selectedFlights" required>
                <small>Use commas to separate multiple IDs.</small>
            </p>
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES); ?>">
            <?php renderPassengerFields($firstName, $lastName, $city, $passengerType); ?>
            <input id="submit" type="submit" name="submit" value="Book Flight">
        </form>
    </div>

    <div class="nav-container">
        <a href="index.php" class="nav-triangle left">Back to Home Page</a>
        <a href="./LoadMyFlights/loadMyFlights.php" class="nav-triangle right">Show My Bookings</a>
    </div>
    <!-- Success Modal -->
<div id="confirmation-modal" class="modal">
    <div class="modal-content">
        <p>Your booking was successful!</p>
        <div class="modal-buttons">
            <button onclick="closeModal()">OK</button>
        </div>
    </div>
</div>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        error_log("Form data: " . print_r($_POST, true));

        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            die("CSRF token validation failed.");
        }

        $selectedFlightsInput = $_POST['selectedFlights'] ?? '';
        $firstName = $_POST['firstNameField'] ?? '';
        $lastName = $_POST['lastNameField'] ?? '';
        $city = $_POST['cityField'] ?? '';
        $passengerType = $_POST['passengerType'] ?? '';

        $firstName = htmlspecialchars(trim($firstName), ENT_QUOTES);
        $lastName = htmlspecialchars(trim($lastName), ENT_QUOTES);
        $city = htmlspecialchars(trim($city), ENT_QUOTES);
        $passengerType = htmlspecialchars(trim($passengerType), ENT_QUOTES);

        if (empty($selectedFlightsInput) || empty($firstName) || empty($lastName) || empty($city) || empty($passengerType)) {
            die("Error: All fields are required.");
        }

        $selectedFlights = array_map('trim', explode(',', $selectedFlightsInput));

        foreach ($selectedFlights as $flight_id) {
            if (!is_numeric(trim($flight_id))) {
                die("Error: Flight ID must be numeric.");
            }
        }

        $passenger_id = getPassengerId($con, $firstName, $lastName, $city, $passengerType, $userId);
        if ($passenger_id === false) {
            die("Error fetching or inserting passenger data.");
        }

        $bookingSuccess = true;
        foreach ($selectedFlights as $flight_id) {
            $stmt = $con->prepare("INSERT INTO bookings (flight_id, passenger_id) VALUES (?, ?)");
            if (!$stmt) {
                error_log("Failed to prepare booking insert statement: " . $con->error);
                $bookingSuccess = false;
                continue;
            }

            $stmt->bind_param("ii", $flight_id, $passenger_id);
            if (!$stmt->execute()) {
                error_log("Error inserting booking for flight ID $flight_id: " . $stmt->error);
                $bookingSuccess = false;
            }
            $stmt->close();
        }

        if ($bookingSuccess) {
            echo "<script>document.getElementById('confirmation-modal').style.display = 'block';</script>";
        } else {
            echo "<script>alert('Some bookings failed. Please check the logs.');</script>";
        }
    }

    $con->close();
    ?>

    <script>
        function closeModal() {
            document.getElementById('confirmation-modal').style.display = 'none';
        }
    </script>
</body>
</html>
