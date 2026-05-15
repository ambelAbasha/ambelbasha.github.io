<?php
session_start();
include('db_connect.php');

// Initialize variables
$user = isset($_POST['login']) ? trim($_POST['login']) : '';
$password_raw = isset($_POST['password']) ? trim($_POST['password']) : '';

// Validate that required fields are not empty
$error_message = '';
if (isset($_GET['session']) && $_GET['session'] === 'lost') {
    $error_message = "Session expired. Please log in again.";
} elseif ($user === '' || $password_raw === '') {
    $error_message = "Error: All fields are required.";
} else {
    // Prepare and execute the query
    $sql = "SELECT username, password FROM users WHERE username = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if a user was found
        if ($result->num_rows > 0) {
            $user_data = $result->fetch_assoc();
            $stored_pass = $user_data['password'];

            // Verify the password
            if (password_verify($password_raw, $stored_pass)) {
                // Password is correct, start a session
                $_SESSION['username'] = $user_data['username'];
                header("Location: dashboard.php");
                exit;
            } else {
                $error_message = "Invalid password. Try again.";
            }
        } else {
            $error_message = "User not found. Try again.";
        }

        // Close the statement
        $stmt->close();
    } else {
        $error_message = "Internal server error. Please try again later.";
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="Styles/userLogin/styles.css">
    <script>
        // JavaScript to handle the automatic redirect with a warning
        setTimeout(function() {
            document.getElementById('warning').style.display = 'block'; // Show the warning message
        }, 5000); // Show the warning after 5 seconds

        setTimeout(function() {
            window.location.href = 'index.html'; // Redirect after 10 seconds
        }, 10000);
    </script>
</head>
<body>
    <div class="container">
        <div class="center">
            <?php if (!empty($error_message)): ?>
                <div class="error-message">
                    <h2>You were redirected for some reason here!</h2>
                    <p><?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></p>
                    <p><a href="index.html" class="return-button">Return to Home Page</a></p>
                    
                    <!-- Warning message for redirect -->
                    <div id="warning" style="display:none; color: red; font-weight: bold;">
                        <p>If you don't do anything, you will be redirected to the login page in 5 seconds...</p>
                    </div>
                </div>
            <?php endif; ?>
            <form action="" method="post">
                <label for="login">Username:</label>
                <input type="text" id="login" name="login" required>
                <br>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <br>
                <input type="submit" value="Login">
            </form>
        </div>
    </div>
</body>
</html>
