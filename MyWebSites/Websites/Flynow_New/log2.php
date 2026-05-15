<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Secure the session settings before starting the session
ini_set('session.cookie_httponly', 1);
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    ini_set('session.cookie_secure', 1);
}

// Start the session after settings are configured
session_start();

// Database connection details
$servername = getenv('DB_SERVER') ?: 'localhost';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';
$dbname = getenv('DB_NAME') ?: 'flynow';

// Secure database connection
$con = new mysqli($servername, $username, $password, $dbname);
if ($con->connect_error) {
    error_log("Database connection error: " . $con->connect_error);
    die("An error occurred. Please try again later.");
}

// Check if username and password are provided
if (empty($_POST['username']) || empty($_POST['password'])) {
    $error_message = urlencode("Username and password are required");
    header("Location: log.php?error={$error_message}");
    exit();
}

// Prepare the SQL query for login authentication
$query = "SELECT user_id, username, password FROM user_auth WHERE username = ?";
$stmt = $con->prepare($query);
if (!$stmt) {
    error_log("Failed to prepare SQL statement: " . $con->error);
    die("An error occurred. Please try again later.");
}

// Bind the parameter and execute the query
$stmt->bind_param("s", $_POST['username']);
$stmt->execute();
$result = $stmt->get_result();

// Check if a matching record exists
if ($row = $result->fetch_assoc()) {
    // Verify the password
    if (password_verify($_POST['password'], $row['password'])) {
        session_regenerate_id(true); // Prevents session fixation
        $_SESSION['username'] = $row['username']; // Store username in session
        $_SESSION['login'] = true;
        
        // Optionally, store the user_id in the session as well
        $_SESSION['user_id'] = $row['user_id']; // Uncomment if needed

        // Redirect to index.php, assuming it handles or includes loadMyFlights.php
        header("Location: index.php");
        exit();
    } else {
        error_log("Failed login attempt for user: " . $_POST['username']);
        $error_message = urlencode("Invalid username or password");
        header("Location: log.php?error={$error_message}");
        exit();
    }
} else {
    error_log("Failed login attempt for non-existent user: " . $_POST['username']);
    $error_message = urlencode("Invalid username or password");
    header("Location: log.php?error={$error_message}");
    exit();
}

// Clean up resources
$stmt->close();
$con->close();
?>
