<?php
session_start();

// Check for error message in the query string
$error = isset($_GET['error']) ? urldecode($_GET['error']) : '';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Log in</title>
  <link href="Styles/Login/log.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Set initial password visibility to hidden
      const passwordField = document.getElementById("password");
      const togglePassword = document.querySelector(".password-toggle-icon i");

      // Ensure password field starts as hidden
      passwordField.type = "password";
      togglePassword.classList.add("fa-eye-slash"); // Show eye-slash initially (hidden password)

      togglePassword.addEventListener("click", function () {
        // Toggle password visibility on click
        if (passwordField.type === "password") {
          passwordField.type = "text"; // Show password
          togglePassword.classList.remove("fa-eye-slash");
          togglePassword.classList.add("fa-eye");
        } else {
          passwordField.type = "password"; // Hide password
          togglePassword.classList.remove("fa-eye");
          togglePassword.classList.add("fa-eye-slash");
        }
      });
    });
  </script>
</head>
<body id="indexBody">
  <div class="container">
    <header class="header">
      <a href="index.php" class="title">Flynow</a>
    </header>

    <form action="log2.php" id="login" method="post">
      <fieldset>
        <legend>Login</legend>
        <div class="form-group">
          <label for="username">Username:</label>
          <input type="text" name="username" id="username" placeholder="Enter username" required autofocus />
        </div>
        <div class="form-group password-group">
          <label for="password">Password:</label>
          <input type="password" name="password" id="password" placeholder="Enter password" required />
          <span class="password-toggle-icon"><i class="fas fa-eye" aria-hidden="true"></i></span>
        </div>
        <input type="submit" name="logButton" value="Log in" />
      </fieldset>
    </form>

    <?php if ($error): ?>
      <div class="error-message">
        <?php echo htmlspecialchars($error); ?>
      </div>
    <?php endif; ?>

    <div class="links" id="registerLink">
      Don't have an account? <a href="register.php">Register to Login</a>
    </div>
  </div>
</body>
</html>
