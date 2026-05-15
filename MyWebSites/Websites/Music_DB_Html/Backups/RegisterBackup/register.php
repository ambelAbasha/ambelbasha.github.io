<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="./Styles/Index/styles.css">
    <style>
        /* Add the CSS for the error container */
        /* Error container */
        #error-container {
            width: 300px;
            margin: 0 auto;
            background-color: #f8d7da; /* Red background color */
            color: #721c24; /* Dark red text color */
            padding: 10px;
            border: 1px solid #f5c6cb; /* Dark red border */
            border-radius: 5px;
            text-align: center; /* Center text */
            font-size: 16px; /* Larger font size */
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999; /* Ensure it's on top */
        }

        /* Center the content vertically */
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        #wrapper {
            min-height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .container {
            text-align: center;
        }

        /* Ensure input fields match the styles from the external CSS file */
        .login input[type="text"],
        .login input[type="password"] {
            width: 300px; /* Adjust as needed */
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }

        .login .submit input[type="submit"] {
            width: 300px; /* Adjust as needed */
            padding: 10px;
            border: none;
            border-radius: 3px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }
    </style>
    <script>
        // Function to display error messages dynamically
        function displayError(inputId, errorMessage) {
            var errorElement = document.getElementById(inputId + '-error');
            if (errorMessage) {
                errorElement.innerText = errorMessage;
                errorElement.style.display = 'block';
            } else {
                errorElement.innerText = '';
                errorElement.style.display = 'none';
            }
        }

        // Function to validate input format
        function validateInput(input, inputId) {
            // Check if the input contains the required characters and is at least 8 characters long
            if (input.trim().length < 8) {
                displayError(inputId, "Password must be at least 8 characters long");
                return false;
            }
            if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,}$/.test(input)) {
                displayError(inputId, "Password requirements not met.");
                return false;
            }
            displayError(inputId, ''); // Clear error message
            return true;
        }

        // Function to validate form on submission
        function validateForm() {
            console.log("Validating form...");
            var name = document.getElementById('name').value.trim();
            var surname = document.getElementById('surname').value.trim();
            var email = document.getElementById('email').value.trim();
            var password = document.getElementById('password').value.trim(); // Trim password

            // Check for empty fields
            if (name === '' || surname === '' || email === '' || password === '') {
                displayError('name', name === '' ? 'Name is required.' : '');
                displayError('surname', surname === '' ? 'Surname is required.' : '');
                displayError('email', email === '' ? 'Email is required.' : '');
                displayError('password', password === '' ? 'Password is required.' : '');
                console.log("Form validation failed.");
                return false;
            }

            // Validate each input field
            var passwordValid = validateInput(password, 'password');

            // Display error messages
            displayError('name', '');
            displayError('surname', '');
            displayError('email', '');
            displayError('password', passwordValid ? '' : 'Password requirements not met.');

            // Prevent form submission if any field is invalid
            if (!passwordValid) {
                console.log("Form validation failed.");
                return false;
            }
            console.log("Form validation successful.");
            return true;
        }
    </script>
</head>
<body>
    <div id="wrapper">
        <!-- Your existing HTML content goes here -->
        <div class="container">
            <div class="login">
                <form method="post" action="register_user.php" onsubmit="return validateForm()">
                    <h2>Register</h2>
                    <input type="text" name="name" id="name" placeholder="Name">
                    <span id="name-error" style="color: red;"></span>
                    <input type="text" name="surname" id="surname" placeholder="Surname">
                    <span id="surname-error" style="color: red;"></span>
                    <input type="text" name="email" id="email" placeholder="Email">
                    <span id="email-error" style="color: red;"></span>
                    <input type="password" name="password" id="password" placeholder="Password (min. 8 characters, including lowercase, uppercase, digit, and special character)">
                    <span id="password-error" style="color: red;"></span>
                    <input type="submit" name="commit" value="Register" class="submit">
                </form>
            </div>
        </div>
        <!-- Error container -->
        <div id="error-container">
            <?php
            // Display PHP errors
            if (!empty($name_error)) {
                echo "<p>$name_error</p>";
            }
            if (!empty($surname_error)) {
                echo "<p>$surname_error</p>";
            }
            if (!empty($user_error)) {
                echo "<p>$user_error</p>";
            }
            if (!empty($email_error)) {
                echo "<p>$email_error</p>";
            }
            if (!empty($password_error)) {
                echo "<p>$password_error</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>