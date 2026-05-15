<?php   
session_start();

// CSRF token generation
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check if the request method is POST for CSRF validation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = $_POST; // Get data from POST request

    // CSRF token verification
    if (!isset($data['csrf_token']) || $data['csrf_token'] !== $_SESSION['csrf_token']) {
        echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
        exit;
    }

    // Handle the removal of selected flights
    if (isset($data['action']) && $data['action'] === 'remove_flights') {
        $booking_ids = isset($data['booking_ids']) ? array_map('intval', $data['booking_ids']) : [];

        if (!empty($booking_ids)) {
            require 'config.php'; 
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Prepare SQL for deletion
            $ids = implode(',', $booking_ids);
            $sqlDelete = "DELETE FROM bookings WHERE booking_id IN ($ids)";
            if ($conn->query($sqlDelete) === TRUE) {
                // Set success message in session
                $_SESSION['success_message'] = 'Selected flights removed successfully.';
            } else {
                // Set error message in session
                $_SESSION['error_message'] = 'Error removing flights: ' . $conn->error;
            }
            $conn->close();
            // Redirect back to the same page
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}

// Ensure user is logged in
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true || !isset($_SESSION['user_id'])) {
    header("Location: ../log.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Database connection
require 'config.php'; 
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch passenger_id based on user_id
$sqlPassenger = "SELECT passenger_id FROM passenger WHERE user_id = ?";
$stmtPassenger = $conn->prepare($sqlPassenger);
$stmtPassenger->bind_param("i", $user_id);
$stmtPassenger->execute();
$resultPassenger = $stmtPassenger->get_result();

if ($rowPassenger = $resultPassenger->fetch_assoc()) {
    $passenger_id = $rowPassenger['passenger_id'];
} else {
    echo "<h2>No passenger found. Please book a flight.</h2>";
    exit; 
}

// Prepare SQL for bookings
$sql = "
    SELECT b.booking_id,
           ac.comp_name AS Airline, 
           f.departure_airport AS `From (City)`, 
           f.destination_airport AS `To (City)`, 
           CASE WHEN f.business_class > 0 THEN 'Business' ELSE 'Economy' END AS Class, 
           p.passenger_type AS Person,
           DATE_FORMAT(f.departure_date, '%d-%m-%Y %H:%i') AS Departure, 
           DATE_FORMAT(f.return_date, '%d-%m-%Y %H:%i') AS `Return Date`
    FROM bookings b
    JOIN flight f ON b.flight_id = f.flight_id
    JOIN airline_company ac ON f.air_com_id = ac.air_com_id
    JOIN passenger p ON b.passenger_id = p.passenger_id
    WHERE b.passenger_id = ?
    ORDER BY b.booking_id";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $passenger_id); 
$stmt->execute();
$result = $stmt->get_result();

// Check for messages in session and clear them after displaying
$successMessage = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$errorMessage = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

if ($successMessage) {
    unset($_SESSION['success_message']); // Clear success message after displaying
}
if ($errorMessage) {
    unset($_SESSION['error_message']); // Clear error message after displaying
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Booked Flights</title>
    <link rel="stylesheet" href="./Styles/styles.css">
    <script src="./Scripts/LogoutModal/logoutModal.js" defer></script>
    <script src="./Scripts/GetCSRF/getCSRF.js" defer></script>
    <script src="./Scripts/PreventConflicts/preventConflicts.js" defer></script>
</head>
<body>
    <div class="container" id="container">
        <h1>Your Booked Flights</h1>

        <?php if ($errorMessage): ?>
            <div class="alert alert-danger" style="text-align: center;">
                <?= htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <?php if ($successMessage): ?>
            <div class="alert alert-success" style="text-align: center;">
                <?= htmlspecialchars($successMessage); ?>
            </div>
        <?php endif; ?>

        <?php if ($result->num_rows > 0): ?>
            <table role="table">
                <thead>
                    <tr>
                        <th>Select</th>
                        <th>Airline</th>
                        <th>From (City)</th>
                        <th>To (City)</th>
                        <th>Class</th>
                        <th>Person</th>
                        <th>Departure</th>
                        <th>Return Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><input type="checkbox" class="row-select" value="<?= htmlspecialchars($row['booking_id']); ?>"></td>
                            <td><?= htmlspecialchars($row['Airline']); ?></td>
                            <td><?= htmlspecialchars($row['From (City)']); ?></td>
                            <td><?= htmlspecialchars($row['To (City)']); ?></td>
                            <td><?= htmlspecialchars($row['Class']); ?></td>
                            <td><?= htmlspecialchars($row['Person']); ?></td>
                            <td><?= htmlspecialchars($row['Departure']); ?></td>
                            <td><?= htmlspecialchars($row['Return Date']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        <?php else: ?>
            <div class="no-flights">
                <h2>You have no booked flights.</h2>
                <p>Would you like to book a new flight?</p>
            </div>
        <?php endif; ?>
        
        <div class="navigation">
            <form method="POST" action="../index.php">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
                <button type="submit" class="button" id="book-another-flight">Book Another Flight</button>
            </form>
            <input type="hidden" id="csrf-token" value="<?= htmlspecialchars($_SESSION['csrf_token']); ?>">
            <button id="remove-selected" class="button">Remove Selected Flights</button>
            <!-- Main Logout Button -->
<a href="#" id="logout-button" class="button">Log Out</a>

<!-- Logout Confirmation Modal -->
<div id="logoutModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeModal">&times;</span>
        <h2>Logout Confirmation</h2> <!-- Added a header for clarity -->
        <p>Are you sure you want to log out?</p>
        <div class="modal-buttons">
            <button id="confirm-logout" class="button-confirm glassy-button">Yes, Logout</button>
            <button id="cancel-logout" class="button-cancel glassy-button">Cancel</button>
        </div>
    </div>
</div>

        <!-- Confirmation modal for flight removal -->
        <div id="confirmation-modal" class="modal">
            <div class="modal-content">
                <p>Are you sure you want to remove the selected flights?</p>
                <div class="modal-buttons">
                    <button id="confirm-remove" class="button-confirm glassy-button">Yes, Remove</button>
                    <button id="cancel-remove" class="button-cancel glassy-button">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <script src="./Scripts/Remove/removeScript.js"></script>
    <script>
        document.getElementById('remove-selected').onclick = function() {
            const selected = Array.from(document.querySelectorAll('.row-select:checked')).map(cb => cb.value);
            if (selected.length > 0) {
                document.getElementById('confirmation-modal').style.display = 'block';
            } else {
                alert('Please select at least one flight to remove.');
            }
        };

        document.getElementById('confirm-remove').onclick = function() {
            const selected = Array.from(document.querySelectorAll('.row-select:checked')).map(cb => cb.value);
            const csrfToken = document.getElementById('csrf-token').value;

            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    csrf_token: csrfToken,
                    action: 'remove_flights',
                    booking_ids: selected,
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                }
            });
        };

        document.getElementById('cancel-remove').onclick = function() {
            document.getElementById('confirmation-modal').style.display = 'none';
        };
    </script>
    <script>
// Select buttons and audio
const removeFlightsBtn = document.getElementById('remove-selected');
const logoutBtn = document.getElementById('logout-button');
const warningSound = new Audio('path/to/your/warning-sound.mp3'); // Ensure the sound path is correct

// Track the state of the buttons
let isRemoveFlightsActive = false;
let isLogoutActive = false;

// Function to handle button clicks
function handleButtonClick(buttonType) {
    if (buttonType === 'remove') {
        if (isLogoutActive) {
            // If Log Out is active, play warning sound
            warningSound.play();
            alert("You cannot remove flights while logged out.");
        } else {
            // Only allow opening the modal if no other modal is active
            isRemoveFlightsActive = true; // Set the active state
            document.getElementById('confirmation-modal').style.display = 'block';
        }
    } else if (buttonType === 'logout') {
        if (isRemoveFlightsActive) {
            // If Remove Flights is active, play warning sound
            warningSound.play();
            alert("You cannot log out while removing flights.");
        } else {
            isLogoutActive = true; // Set the active state
            document.getElementById('logoutModal').style.display = 'block';
        }
    }
}

// Event listeners
removeFlightsBtn.addEventListener('click', function() {
    const selected = Array.from(document.querySelectorAll('.row-select:checked')).map(cb => cb.value);
    if (selected.length > 0) {
        handleButtonClick('remove'); // Trigger the remove action
    } else {
        alert('Please select at least one flight to remove.');
    }
});

document.getElementById('confirm-remove').onclick = function() {
    // Your AJAX call logic goes here
    const selected = Array.from(document.querySelectorAll('.row-select:checked')).map(cb => cb.value);
    const csrfToken = document.getElementById('csrf-token').value;

    fetch('', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            csrf_token: csrfToken,
            action: 'remove_flights',
            booking_ids: selected,
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    });

    // Close the modal after confirming
    document.getElementById('confirmation-modal').style.display = 'none';
    isRemoveFlightsActive = false; // Reset state
};

document.getElementById('cancel-remove').onclick = function() {
    document.getElementById('confirmation-modal').style.display = 'none';
    isRemoveFlightsActive = false; // Reset state
};

document.getElementById('closeModal').onclick = function() {
    document.getElementById('logoutModal').style.display = 'none';
    isLogoutActive = false; // Reset state
};

logoutBtn.addEventListener('click', function() {
    handleButtonClick('logout'); // Trigger logout action
});
</script>

</body>
</html>
