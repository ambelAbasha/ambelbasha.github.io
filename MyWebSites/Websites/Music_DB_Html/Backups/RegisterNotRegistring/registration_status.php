<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Status</title>
    <link rel="stylesheet" type="text/css" href="./Styles/Register/registerUser.css">
</head>
<body>

<div class="message-box">
    <div class="content"><?php echo isset($_SESSION['message']) ? $_SESSION['message'] : ''; ?></div>
    <?php
    if (isset($_SESSION['redirectDelay'])) {
        echo "<div class='countdown' id='countdown'></div>";
        echo "<script>
                let seconds = {$_SESSION['redirectDelay']};
                const countdownElement = document.getElementById('countdown');

                function updateCountdown() {
                    countdownElement.textContent = 'You will be redirected in: ' + seconds + ' seconds';
                    if (seconds > 0) {
                        seconds--;
                    } else {
                        window.location.href = '{$_SESSION['redirectUrl']}';
                    }
                }
                setInterval(updateCountdown, 1000);
                updateCountdown();
              </script>";
    }
    ?>
</div>

<?php unset($_SESSION['message'], $_SESSION['redirectDelay'], $_SESSION['redirectUrl']); // Clear session messages after displaying ?>
</body>
</html>
