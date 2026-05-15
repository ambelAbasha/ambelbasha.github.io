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
    die("Connection failed: " . htmlspecialchars($conn->connect_error));
}

// Retrieve data from the form with isset() checks
$name = isset($_POST['name']) ? $_POST['name'] : '';
$surname = isset($_POST['surname']) ? $_POST['surname'] : '';
$user = isset($_POST['login']) ? $_POST['login'] : '';
$password_raw = isset($_POST['password']) ? $_POST['password'] : '';
$pass = password_hash($password_raw, PASSWORD_DEFAULT); // Securely hash the password
$regdate = date('Y-m-d'); // Current date for registration

// Debugging output
echo "Name: $name<br>";
echo "Surname: $surname<br>";
echo "Login: $user<br>";
echo "Password: $password_raw<br>";

// Validate that all required fields are not empty
if ($name === '' || $surname === '' || $user === '' || $password_raw === '') {
    echo "Error: All fields are required.";
    exit; // Prevent further execution of the code
}

// Check for duplicate usernames
$stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "Error: Username already exists.";
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

// Use prepared statements to insert into the users table
$stmt = $conn->prepare("INSERT INTO users (name, surname, username, password, regdate) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $name, $surname, $user, $pass, $regdate); // "sssss" represents five strings

// Attempt to execute the prepared statement
try {
    if ($stmt->execute()) {
        echo "Registration successful!"; // Confirmation message
    } else {
        echo "Error: " . htmlspecialchars($stmt->error); // Proper error handling
    }
} finally {
    // Always close the statement and connection to prevent memory leaks
    $stmt->close();
    $conn->close();
}
?>
