<?php
// Secure session start
if (session_status() == PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 1);
    ini_set('session.cookie_lifetime', 0);
    session_start();
    session_regenerate_id(true);
}

// CSRF Token Initialization
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$success_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted_csrf_token = $_POST['csrf_token'] ?? '';
    if ($submitted_csrf_token !== $_SESSION['csrf_token']) {
        header("HTTP/1.1 403 Forbidden");
        die("Invalid CSRF token. Please try again.");
    }

    // Sanitization & Validation
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = $_POST['password'] ?? '';
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address. Please enter a valid one.";
    } else {
        // Database Connection
        $servername = getenv('DB_SERVERNAME') ?: 'localhost';
        $db_username = getenv('DB_USERNAME') ?: 'root';
        $db_password = getenv('DB_PASSWORD') ?: '';
        $dbname = "flynow";

        $mysqli = new mysqli($servername, $db_username, $db_password, $dbname);
        if ($mysqli->connect_error) {
            die("Database connection error: " . $mysqli->connect_error);
        }

        // Check for Unique Username
        $query = "SELECT * FROM user_auth WHERE username = ?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
            $insert_query = "INSERT INTO user_auth (username, password, email) VALUES (?, ?, ?)";
            $insert_stmt = $mysqli->prepare($insert_query);
            $insert_stmt->bind_param("sss", $username, $hashed_password, $email);

            try {
                $insert_stmt->execute();
                $success_message = "Registration successful! Redirecting...";
                header("refresh:3;url=log.php");
            } catch (mysqli_sql_exception $e) {
                echo "Error during registration: " . $e->getMessage();
            }
        } else {
            echo "The username '$username' is already taken. Please choose a different one.";
        }

        $stmt->close();
        $insert_stmt->close();
        $mysqli->close();
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Register</title>
    <link rel="stylesheet" href="Styles/Register/register.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const passwordField = document.getElementById("password");
            const togglePassword = document.querySelector(".password-toggle-icon i");

            togglePassword.addEventListener("click", function () {
                passwordField.type = passwordField.type === "password" ? "text" : "password";
                togglePassword.classList.toggle("fa-eye");
                togglePassword.classList.toggle("fa-eye-slash");
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <?php if (!empty($success_message)): ?>
        <div class="message"> <?php echo $success_message; ?> </div>
        <?php endif; ?>
        <header class="header">
      <a href="index.php" class="title">Flynow</a>
    </header>
        <form action="register.php" method="post">
            <fieldset>
                <legend>Register</legend>
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>"/>
                <label for='username'>Username:</label>
                <input type='text' name='username' required placeholder="Enter your username" pattern="^[a-zA-Z0-9_]{3,}$"/>
                <br>
                <div class="password-group">
                    <label for='password'>Password:</label>
                    <input type='password' name='password' id="password" required minlength='8'/>
                    <span class="password-toggle-icon"><i class="fas fa-eye" aria-hidden="true"></i></span>
                </div>
                <br>
                <label for='email'>Email:</label>
                <input type='email' required name='email' placeholder="Enter your email"/>
                <br>
                <input type='submit' name='registerButton' value='Register'/>
            </fieldset>
        </form>
        <div class="links"> Already have an account? <a href="log.php">Login</a> </div>
    </div>
</body>
</html>