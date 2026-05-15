<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Book a Flight</title>
</head>
<body>
<?php
session_start(); // Start the session

// Ensure the CSRF token is set for security
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate if not set
}

// Database connection initialization with error handling
$con = new mysqli("localhost", "root", "", "flynow");
if ($con->connect_error) {
    error_log("Database connection failed: " . $con->connect_error);
    die("We are experiencing technical issues. Please try again later.");
}

?>

<form id="messForm" name="messForm" method="post" action="">
  <p>
    <label for="idField">Flight's ID:</label>
    <input type="number" name="idField" id="idField" min="1" required>
  </p>

  <!-- Include CSRF token for security -->
  <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

  <?php
  // If the user is not logged in, ask for their name and email
  if (!isset($_SESSION['username'])) {
    echo '
      <p>
        <label for="nameField">Name:</label>
        <input type="text" name="nameField" id="nameField" required>
      </p>
      <p>
        <label for="mailField">E-mail:</label>
        <input type="email" name="mailField" required>
      </p>';
  }
  ?>

  <p>
    <input type="submit" name="checkButton" value="Check">
  </p>

  <p>
  <?php
  if (isset($_POST['checkButton'])) { // If the "Check" button is clicked
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
              echo "Congratulations, you are flying from " . $row['departure_airport'] . " to " . $row['destination_airport'] . ".";

              if (isset($_SESSION['username'])) {
                  $username = $_SESSION['username'];

                  $insert_query = $con->prepare("INSERT INTO reserv (fid, username) VALUES (?, ?)");
                  $insert_query->bind_param("is", $flight_id, $username);

                  if ($insert_query->execute()) {
                      $_SESSION['result'] = [
                          'booking_id' => $con->insert_id, // Store booking ID
                          'flight_id' => $flight_id,
                      ];

                      // Redirect to message.php with booking information
                      echo '<form method="post" action="message.php" id="redirectForm">';
                      echo '<input type="hidden" name="result[booking_id]" value="' . htmlspecialchars($con->insert_id) . '">';
                      echo '<input type="hidden" name="result[flight_id]" value="' . htmlspecialchars($flight_id) . '">';
                      echo '</form>';
                      echo '<script>document.getElementById("redirectForm").submit();</script>'; // Redirect using JavaScript
                      exit();
                  } else {
                      error_log("Error inserting reservation: " . $con->error);
                      die("An error occurred while booking the flight. Please try again later.");
                  }
              } else {
                  echo "You must be logged in to make a reservation.";
              }
          }
      } else {
          echo "No flight found with the given ID.";
      }
  }

  // Close the database connection
  $con->close();
  ?>
  </p>
</form>
</body>
</html>
