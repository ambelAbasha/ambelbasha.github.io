<?php
// Include your database connection file
require 'db_connect.php';

// Initialize error variables
$name_error = $surname_error = $email_error = $password_error = "";
$name = $surname = $email = ""; // Initialize variables to hold form values

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user input to prevent SQL injection and XSS attacks
    $name = htmlspecialchars(trim($_POST['name']));
    $surname = htmlspecialchars(trim($_POST['surname']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = trim($_POST['password']); // Do not escape password yet, hash it first
    
    // Validation flags
    $form_valid = true;
    
    // Validate name
    if (empty($name)) {
        $name_error = "Name is required.";
        $form_valid = false;
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
        $name_error = "Only letters and spaces are allowed.";
        $form_valid = false;
    }

    // Validate surname
    if (empty($surname)) {
        $surname_error = "Surname is required.";
        $form_valid = false;
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $surname)) {
        $surname_error = "Only letters and spaces are allowed.";
        $form_valid = false;
    }

    // Validate email
    if (empty($email)) {
        $email_error = "Email is required.";
        $form_valid = false;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email format.";
        $form_valid = false;
    }

    // Validate password
    if (empty($password)) {
        $password_error = "Password is required.";
        $form_valid = false;
    } elseif (strlen($password) < 8 || !preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password) || !preg_match("/\d/", $password) || !preg_match("/[!@#$%^&*]/", $password)) {
        $password_error = "Password must be at least 8 characters long and include uppercase, lowercase, a digit, and a special character.";
        $form_valid = false;
    }

    // If form is valid, proceed with registration
    if ($form_valid) {
        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Check if the email already exists in the database
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $email_error = "Email is already registered.";
        } else {
            // Insert user data into the database
            $stmt = $conn->prepare("INSERT INTO users (name, surname, email, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $surname, $email, $hashed_password);

            if ($stmt->execute()) {
                // Redirect to a success page or login page
                header("Location: success.php");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        }

        // Close statement and connection
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="./Styles/Register/registerError.css">
</head>
<body>
    <div id="wrapper">
        <div class="container">
            <div class="login">
                <form method="post" action="register.php">
                    <h2>Register</h2>
                    <input type="text" name="name" id="name" placeholder="Name" value="<?php echo htmlspecialchars($name); ?>">
                    <span id="name-error" style="color: red;"><?php echo $name_error; ?></span>
                    
                    <input type="text" name="surname" id="surname" placeholder="Surname" value="<?php echo htmlspecialchars($surname); ?>">
                    <span id="surname-error" style="color: red;"><?php echo $surname_error; ?></span>
                    
                    <input type="text" name="email" id="email" placeholder="Email" value="<?php echo htmlspecialchars($email); ?>">
                    <span id="email-error" style="color: red;"><?php echo $email_error; ?></span>
                    
                    <input type="password" name="password" id="password" placeholder="Password">
                    <span id="password-error" style="color: red;"><?php echo $password_error; ?></span>
                    
                    <input type="submit" name="commit" value="Register" class="submit">
                </form>
            </div>
        </div>
    </div>
</body>
</html>
