<?php
// Error handling
$error_messages = [];

// Collect error messages
if (!empty($name_error)) {
    $error_messages[] = $name_error;
}
if (!empty($surname_error)) {
    $error_messages[] = $surname_error;
}
if (!empty($user_error)) {
    $error_messages[] = $user_error;
}
if (!empty($email_error)) {
    $error_messages[] = $email_error;
}
if (!empty($password_error)) {
    $error_messages[] = $password_error;
}

// Display error messages within a styled container
if (!empty($error_messages)) {
    echo '<div id="error-container">';
    foreach ($error_messages as $error) {
        echo "<p>$error</p>";
    }
    echo '</div>';
}
?>
