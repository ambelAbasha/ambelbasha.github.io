<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Book a Flight</title>
    <link href="./styles.css" rel="stylesheet" type="text/css"/> <!-- Link to the CSS file -->
</head>
<body>
    <?php
    session_start(); // Start the session

    // Ensure the CSRF token is set for security
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate if not set
    }

    // Initialize the database connection with error handling
    $con = new mysqli("localhost", "root", "", "flynow");
    if ($con->connect_error) {
        error_log("Database connection failed: " . $con->connect_error);
        die("We are experiencing technical issues. Please try again later.");
    }
    ?>

    <!-- Main container for the form -->
    <div class="form-container"> <!-- Consistent class for form styling -->
        <form id="messForm" name="messForm" method="post"> <!-- Form for booking -->
            <fieldset> <!-- Group related form elements -->
                <legend>Search a Flight</legend> <!-- Legend for the fieldset -->
                <p>
                    <label for="idField">Flight's ID:</label>
                    <input type="number" name="idField" id="idField" min="1" required>
                </p>

                <!-- Include the CSRF token for security -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                <?php
                if (!isset($_SESSION['username'])) { // If the user is not logged in, ask for name and email
                    echo '
                      <p>
                        <label for="nameField">Name:</label>
                        <input type="text" name="nameField" required>
                      </p>
                      <p>
                        <label for="mailField">Email:</label>
                        <input type="email" name="mailField" required>
                      </p>';
                }
                ?>

                <p>
                    <input type="submit" name="checkButton" value="Check"> <!-- Styled check button -->
                </p>
            </fieldset> <!-- End of fieldset -->
        </form>
    </div> <!-- Close the form container -->

    <!-- Container for the message displayed below the form -->
    <div class="message-container"> <!-- Container for the booking message -->
        <?php
        if (isset($_POST['checkButton'])) {
            if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                die("Invalid CSRF token");
            }

            $flight_id = intval($con->real_escape_string($_POST['idField']));

            if ($flight_id < 1) {
                die("Invalid flight ID");
            }

            $query = $con->prepare("SELECT * FROM flight WHERE flight_id = ?");
            $query->bind_param("i", $flight_id);
            $query->execute();
            $result = $query->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $departure_airport = ucfirst($row['departure_airport']);
                    $destination_airport = ucfirst($row['destination_airport']);
                    $departure_date = date('d/m/Y', strtotime($row['departure_date']));

                    // Fetch the return flight
                    $return_query = $con->prepare("SELECT * FROM flight WHERE LOWER(departure_airport) = LOWER(?) AND LOWER(destination_airport) = LOWER(?) AND DATE(departure_date) > ?");
                    if ($return_query === false) {
                        die("Failed to prepare return query");
                    }

                    $return_query->bind_param("sss", $destination_airport, $departure_airport, $departure_date);
                    $return_query->execute();
                    $return_result = $return_query->get_result();

                    if ($return_result->num_rows > 0) {
                        $return_row = $return_result->fetch_assoc();
                        $return_date = date('d/m/Y', strtotime($return_row['departure_date']));
                        echo "Congratulations, you are flying from $departure_airport to $destination_airport on $departure_date and returning from $destination_airport to $departure_airport on $return_date.";
                    } else {
                        echo "No return flight found.";
                    }
                }
            } else {
                echo "No flight found with the given ID.";
            }
        }
        ?>
    </div> <!-- Close the message container -->
</body>
</html>
