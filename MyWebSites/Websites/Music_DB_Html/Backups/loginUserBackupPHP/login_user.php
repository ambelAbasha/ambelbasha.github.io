<?php
session_start();
include('db_connect.php');

// Retrieve form data with isset() checks
$user = isset($_POST['login']) ? $_POST['login'] : '';
$password_raw = isset($_POST['password']) ? $_POST['password'] : '';

// Debugging output
echo "Login: $user<br>";
echo "Password: $password_raw<br>";

// Validate that required fields are not empty
if ($user === '' || $password_raw === '') {
    echo "Error: All fields are required.";
    exit; // Prevent further code execution
}

// Use prepared statements to select from the users table
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

// Check if a user was found
if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
    
    // Debugging output
    var_dump($user_data);
    
    $stored_pass = $user_data['password'];
    
    // Verify the password
    if (password_verify($password_raw, $stored_pass)) {
        // Password is correct, start a session
        $_SESSION['username'] = $user_data['username'];
        header("Location: dashboard.php");
        exit;
    } else {
        // Invalid password
        echo "Invalid password. Try again.";
    }
} else {
    // No user found with that username
    echo "User not found. Try again.";
}

// Close the statement and the connection
$stmt->close();
$conn->close();
?>
