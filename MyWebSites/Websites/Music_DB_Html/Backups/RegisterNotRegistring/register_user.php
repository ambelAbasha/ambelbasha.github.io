<?php 
// Start session
session_start();

// Database connection
$servername = "localhost";
$dbusername = "root"; // Database username
$dbpassword = ""; // Database password
$dbname = "musicdb";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check for connection error
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => "Sorry, there was a problem connecting to the database. Please try again later."]));
}

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(trim($data));
}

// Initialize variables for form data
$name = '';
$surname = '';
$email = '';
$password_raw = '';

// Initialize an empty error array to collect validation issues
$errors = [];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize data from the form
    $name = isset($_POST['name']) ? sanitizeInput($_POST['name']) : '';
    $surname = isset($_POST['surname']) ? sanitizeInput($_POST['surname']) : '';
    $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
    $password_raw = isset($_POST['password']) ? $_POST['password'] : '';

    // Validate that all required fields are not empty
    if (empty($name)) {
        $errors[] = "Error: Name is required.";
    }
    if (empty($surname)) {
        $errors[] = "Error: Surname is required.";
    }
    
    // Check for valid email format
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Error: A valid email is required.";
    }

    // Validate password strength
    if (empty($password_raw)) {
        $errors[] = "Error: Password is required.";
    } else {
        if (strlen($password_raw) < 8 || !preg_match('/[A-Za-z]/', $password_raw) || !preg_match('/[0-9]/', $password_raw)) {
            $errors[] = "Error: Password must be at least 8 characters long and contain both letters and numbers.";
        }
    }

    // Proceed only if there are no validation errors
    if (empty($errors)) {
        // Check if the email (stored as username) is already registered
        $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Email already exists
            $stmt->close();
            echo json_encode(['success' => false, 'error' => 'Email already exists.', 'email' => $email]);
            exit();
        }
        $stmt->close();

        // Hash the password
        $pass = password_hash($password_raw, PASSWORD_DEFAULT);
        $regdate = date('Y-m-d'); // Current date for registration

        // Use prepared statements to insert into the users table
        $stmt = $conn->prepare("INSERT INTO users (name, surname, username, password, regdate) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $surname, $email, $pass, $regdate);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Registration successful
            echo json_encode(['success' => true, 'message' => "Registration successful! An activation link has been sent to: $email"]);
            exit();
        } else {
            $errors[] = "Error: Something went wrong. Please try again later.";
            error_log("MySQL error: " . $stmt->error); // Log error for admin
        }
        $stmt->close();
    }

    // Return errors in JSON format
    echo json_encode(['success' => false, 'errors' => $errors]); // Return validation errors
    exit();
}

// Close connection
$conn->close();
?>
