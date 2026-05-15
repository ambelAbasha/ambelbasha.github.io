<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Input Form</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Ambel Basha">
    <meta name="description" content="Part of Ambel Basha's portfolio, showcasing web development skills.">
    <meta name="keywords" content="PHP, HTML, CSS, JavaScript, User Input, Web Development, Ambel Basha">
    <meta name="robots" content="index, follow">
    <link rel="stylesheet" type="text/css" href="Styles/styles.css">
</head>
<body>

    <div class="outer-container">
        <div class="container">

            <!-- User input form to take a number from 1 to 100 -->
            <form method="post" id="inputForm">
                <label for="number">Please enter a number from 1 to 100:</label><br>
                <input type="number" id="number" name="number" min="1" max="100"><br><br>
                
                <!-- Loading bar placed here -->
                <div id="loadingBar" class="hidden"></div><br>

                <input type="submit" value="Submit" class="submit-button">
            </form>

            <div id="message" class="hidden">
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $number = $_POST['number'];
                    echo "<div class='table-container'>";
                    echo "<table>";
                    for ($i = 1; $i <= $number; $i++) {
                        echo "<tr><td>Welcome to PHP " . $i . "</td></tr>";
                    }
                    echo "</table>";
                    echo "</div>";
                }
                ?>
            </div>

            <div id="pagination"></div>
        </div>

        <div class="return-button">
            <button onclick="window.location.href='../index.html'">Back</button>
        </div>

    </div>

    <script src="Scripts/script.js"></script>
    <script>
        // Add event listener for form submission
        document.getElementById("inputForm").addEventListener("submit", function(event) {
            event.preventDefault();  // Prevent the form from submitting immediately
            showLoadingBar();        // Show the loading bar
            setTimeout(function() {
                // After 3 seconds (simulating processing time), hide the loading bar and show the message
                document.getElementById("loadingBar").classList.add("hidden");
                document.getElementById("message").classList.remove("hidden");
            }, 3000);
        });

        function showLoadingBar() {
            document.getElementById("loadingBar").classList.remove("hidden");
        }
    </script>         
</body>
</html>
