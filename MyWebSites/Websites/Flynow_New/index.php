<?php
// Secure session settings
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // Ensure HTTPS is used
ini_set('session.use_strict_mode', 1);

session_start();

// Regenerate session ID for logged-in users
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    session_regenerate_id();
}

// Generate a CSRF token if it doesn't exist
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle login logic
if (isset($_POST['login'])) {
    // Add your authentication logic here
    $_SESSION['login'] = true;
    $_SESSION['username'] = htmlspecialchars($_POST['username']);
    header("Location: index.php");
    exit;
}

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: index.php");
    exit;
}

// CSRF token validation for POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("Invalid CSRF token.");
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flynow Landing Page</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="Styles/style.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.mobiscroll.com/5.0.4/js/mobiscroll.jquery.min.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr" defer></script>
    <script src="Scripts/dateAndPicker.js" defer></script>
    <script src="Scripts/updateCardOfDateTime.js" defer></script>
    <script src="Scripts/validateForm.js" defer></script>
    <script src='Scripts/logoutModal.js' defer></script>
    <script src="Scripts/script.js" defer></script>

</head>
<body id="indexBody">
<div id="outer-container" class="vibrant-border">
    <header></header>
  <div id="container" class="vibrant-border">
            <div class="rectangle-wrapper top-section">
            <div class="rectangle" id="rect1">
    <img id="animated-plane" class="animated-img" src="../Flynow_New/Images/plane.png" alt="Plane">
    <span class="title">Flynow</span>
</div>


                <div class="rectangle" id="rect2">
                    <div class="content-center">
                    <span class="date-time" id="currentDate"></span>

                    </div>
                </div>
                <div class="rectangle" id="rect3"></div>
            </div>
            <div class="rectangle large-rectangle">
                <div class="content-center">
                    <div class="auth-buttons">
                        <?php if (isset($_SESSION['login']) && $_SESSION['login']) : ?>
                            <div class="welcome-message">
                                <span><?php echo "Welcome, " . htmlspecialchars($_SESSION['username']) . "!"; ?></span>
                            </div>
                            <div class="button-container">
    <!--<a href="undo.php" class="button">Remove</a>-->
    <!-- Booked Button with a different id -->
    <a href="./LoadMyFlights/loadMyFlights.php?csrf_token=<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>" id="bookedButton" class="button">Booked</a>
<!-- Main Logout Button -->
<a href="#" id="logoutButton" class="button">Log out</a>

<!-- Logout Confirmation Modal -->
<div id="logoutModal" class="modal">
<div class="modal-content">
<span class="close" id="closeModal">&times;</span>
<h3>Are you sure you want to log out?</h3> <!-- Ensure this line is present -->
<div class="modal-buttons">
<button id="confirm-remove" class="button-confirm ">Yes, Logout</button>
<button id="cancel-remove" class="button-cancel ">Cancel</button>
</div>
</div>
</div>
</div>
<?php else : ?>
<a href="log.php" class="button" id="login-button">Log in</a>
<a href="register.php" class="button" id="register-button">Register</a>
<?php endif; ?>
                    </div>
                    <form action="search.php" method="post" name="main_form" id="main_form" onsubmit="return validateForm()">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>" />
    <div class="box">
        <h2>Search for Flights</h2>
        <table class="search-table">
            <tr>
                <td>
                    <label for="airlineSelect">Airline:</label>
                    <div class="slot-select">
                        <select name="airlineSelect" id="airlineSelect" required>
                            <option value="">Select an Airline</option>
                            <option value="Aegean Airlines">Aegean Airlines</option>
                            <option value="Air France">Air France</option>
                            <option value="British Airways">British Airways</option>
                            <option value="Lufthansa">Lufthansa</option>
                            <option value="Emirates">Emirates</option>
                            <option value="Delta Air Lines">Delta Air Lines</option>
                            <option value="United Airlines">United Airlines</option>
                            <option value="Qatar Airways">Qatar Airways</option>
                            <option value="Singapore Airlines">Singapore Airlines</option>
                            <option value="Turkish Airlines">Turkish Airlines</option>
                        </select>
                        <div id="airlineError" class="error-message" style="display: none;">Please select an airline.</div>
                    </div>
                </td>
                <td>
                    <label for="fromSelect">From:</label>
                    <div class="slot-select">
                        <select name="fromSelect" id="fromSelect" required>
                            <option value="">Select a location</option>
                            <option value="Athens">Athens</option>
                            <option value="Barcelona">Barcelona</option>
                            <option value="London">London</option>
                            <option value="New York">New York</option>
                            <option value="Los Angeles">Los Angeles</option>
                            <option value="Tokyo">Tokyo</option>
                            <option value="Berlin">Berlin</option>
                            <option value="Paris">Paris</option>
                            <option value="Rome">Rome</option>
                            <option value="Sydney">Sydney</option>
                            <option value="Dubai">Dubai</option>
                            <option value="Singapore">Singapore</option>
                            <option value="Toronto">Toronto</option>
                            <option value="Rio de Janeiro">Rio de Janeiro</option>
                            <option value="Bangkok">Bangkok</option>
                            <option value="Amsterdam">Amsterdam</option>
                            <option value="Vienna">Vienna</option>
                            <option value="Zurich">Zurich</option>
                            <option value="Moscow">Moscow</option>
                            <option value="Beijing">Beijing</option>
                            <option value="Seoul">Seoul</option>
                        </select>
                        <div id="fromError" class="error-message" style="display: none;">Please select a departure location.</div>
                    </div>
                </td>
                <td>
                    <label for="toSelect">To:</label>
                    <div class="slot-select">
                        <select name="toSelect" id="toSelect" required>
                            <option value="">Select a location</option>
                            <option value="Athens">Athens</option>
                            <option value="Barcelona">Barcelona</option>
                            <option value="London">London</option>
                            <option value="New York">New York</option>
                            <option value="Los Angeles">Los Angeles</option>
                            <option value="Tokyo">Tokyo</option>
                            <option value="Berlin">Berlin</option>
                            <option value="Paris">Paris</option>
                            <option value="Rome">Rome</option>
                            <option value="Sydney">Sydney</option>
                            <option value="Dubai">Dubai</option>
                            <option value="Singapore">Singapore</option>
                            <option value="Toronto">Toronto</option>
                            <option value="Rio de Janeiro">Rio de Janeiro</option>
                            <option value="Bangkok">Bangkok</option>
                            <option value="Amsterdam">Amsterdam</option>
                            <option value="Vienna">Vienna</option>
                            <option value="Zurich">Zurich</option>
                            <option value="Moscow">Moscow</option>
                            <option value="Beijing">Beijing</option>
                            <option value="Seoul">Seoul</option>
                        </select>
                        <div id="toError" class="error-message" style="display: none;">Please select a destination.</div>
                    </div>
                </td>
                <td>
                    <label for="classSelect">Class:</label>
                    <div class="slot-select">
                        <select name="classSelect" id="classSelect" required>
                            <option value="">Select a class</option>
                            <option value="Economy">Economy</option>
                            <option value="Business">Business</option>
                            <option value="First">First</option>
                        </select>
                        <div id="classError" class="error-message" style="display: none;">Please select a class.</div>
                    </div>
                </td>
                <td>
                    <label for="personSelect">Person:</label>
                    <div class="slot-select">
                        <select name="personSelect" id="personSelect" required>
                            <option value="">Select a person</option>
                            <option value="Adult">Adult</option>
                            <option value="Child">Child</option>
                            <option value="Infant">Infant</option>
                        </select>
                        <div id="personError" class="error-message" style="display: none;">Please select a passenger type.</div>
                    </div>
                </td>
                <td>
                    <label for="departureDate">Departure:</label>
                    <div class="slot-select">
                        <input type="text" id="departureDate" name="departureDate" placeholder="dd-mm-yyyy" required readonly>
                        <div id="dateError" class="error-message" style="display: none;">Please select a departure date.</div>
                    </div>
                </td>
                <td>
                    <label for="returnDate">Return:</label>
                    <div class="slot-select">
                    <input type="text" id="returnDate" name="returnDate" placeholder="dd-mm-yyyy" required readonly>
                    <div id="returnDateError" class="error-message" style="display: none;">Please select a return date.</div>
                </div>
                </td>
                <td>
                    <button type="submit" class="button search-button" id="search-button">Search</button>
                </td>
            </tr>
        </table>
    </div>
</form>
                </div>
                </div>
    </div>
        <div class="footer glossy-line">
        <ul>
            <li><a href="../../index.php" onclick="resetHex()">Back</a></li>
            <li><a href="../../About/about.html" onclick="resetHex()">About</a></li>
            <li><a href="https://gr.linkedin.com/in/ambel-basha-367156123" target="_blank" rel="noopener noreferrer" onclick="resetHex()">Contact</a></li>
        </ul>
        </div>
    <!-- Date and time display -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!---<h1>Session Debugger</h1>
    <div class="debugger-container">
        <h2>Session Data</h2>
        <div class="debug-item">
            <span>CSRF Token:</span> <?= htmlspecialchars($_SESSION['csrf_token']); ?>
        </div>
        <div class="debug-item">
            <span>Username:</span> <?= htmlspecialchars($_SESSION['username']); ?>
        </div>
        <div class="debug-item">
            <span>Login Status:</span> <?= $_SESSION['login'] ? 'True' : 'False'; ?>
        </div>
    </div>
    </div>--->
    <script>
$(function() {
    $("#returnDate").datepicker({
        dateFormat: 'dd-mm-yy', // Set format to dd-mm-yyyy
        onSelect: function(dateText) {
            $(this).val(dateText);
            $("#returnDateError").hide(); // Hide error message on selection
        }
    });

    $("#returnDate").on("focus", function() {
        $("#returnDateError").hide(); // Hide error message on focus
    });
});
</script>
<script>
function validateForm() {
    var returnDate = document.getElementById("returnDate").value;
    if (returnDate === "") {
        document.getElementById("returnDateError").style.display = "block";
        return false; // Prevent form submission
    }
    return true; // Allow form submission
}
</script>
<script>
// Select the image element
const plane = document.getElementById("animated-plane");

// Function to move the image from bottom-right to center smoothly
function moveImage() {
  const rect = document.getElementById("rect1");
  const rectWidth = rect.offsetWidth;
  const rectHeight = rect.offsetHeight;

  // Starting position (bottom-right corner)
  const startX = rectWidth - plane.offsetWidth;  // Right edge
  const startY = rectHeight - plane.offsetHeight; // Bottom edge

  // Final position (center of the rectangle)
  const targetX = (rectWidth - plane.offsetWidth) / 2;  // Center horizontally
  const targetY = (rectHeight - plane.offsetHeight) / 2; // Center vertically

  let currentX = startX;
  let currentY = startY;
  const speed = 0.05; // Adjust this value for smoother/faster movement

  // Animation function using requestAnimationFrame
  function animate() {
    currentX += (targetX - currentX) * speed;
    currentY += (targetY - currentY) * speed;

    // Apply new position
    plane.style.transform = `translate(${currentX}px, ${currentY}px)`;

    // Check if the animation is near complete
    if (Math.abs(currentX - targetX) > 1 || Math.abs(currentY - targetY) > 1) {
      requestAnimationFrame(animate); // Continue animation
    }
  }

  animate(); // Start the animation
}

// Wait for the page to fully load and then trigger the animation
document.addEventListener("DOMContentLoaded", function () {
  if (plane && document.getElementById("rect1")) {
    moveImage(); // Start moving the plane
  } else {
    console.error("Animation elements not found.");
  }
});

</script>
</body>
</html>

