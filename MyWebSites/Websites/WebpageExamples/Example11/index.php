<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Information for the page -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Ambel Basha"> <!-- Author Information -->
    <meta name="description" content="User input form with validation for numbers between 1 and 100. Displays paginated results.">
    <meta name="keywords" content="HTML, CSS, PHP, form, pagination, user input">
    <meta name="robots" content="index, follow">
    <title>User Input Form</title>
    
    <!-- Link to external stylesheet -->
    <link rel="stylesheet" type="text/css" href="Styles/styles.css">
</head>
<body>
<!-- Outer container that holds the content -->
<div class="outer-container">
    <!-- Main content container -->
    <div class="container">
        <!-- User input form -->
        <form method="post" id="inputForm">
            <label for="number">Please enter a number from 1 to 100:</label><br><br>
            <input type="number" id="number" name="number" min="1" max="100"><br><br>
            <input type="submit" value="Submit" class="submit-button">
        </form>

        <!-- Div to display the result and pagination -->
        <div id="message" class="hidden"></div>

        <?php
            // Handling the form submission via POST method
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["times"])) {
                // Fetch and validate user input
                $times = $_POST["times"];
                if (!empty($times) && is_numeric($times) && $times > 0 && $times <= 100) {
                    // If valid, display result and pagination
                    echo "<div id='result'></div>";
                    echo "<div id='pagination'></div>";
                    echo "<script>";
                    echo "document.addEventListener('DOMContentLoaded', function () {";
                    echo "var resultDiv = document.getElementById('result');";
                    echo "var paginationDiv = document.getElementById('pagination');";
                    echo "var currentPage = 1;";
                    echo "var totalPages = Math.ceil($times / 5);"; // Assuming 5 items per page
                    echo "var itemsPerPage = 5;";
                    echo "var totalVisiblePages = 3;";
                    echo "displayMessages(currentPage);";
                    echo "generatePaginationButtons();";
                    echo "});";
                    echo "</script>";
                } elseif ($times > 100) {
                    // If number is greater than 100, show an error
                    echo "<p style='color: red; text-align: center;'>Error: Please enter a number less than or equal to 100.</p>";
                } else {
                    // Handle invalid inputs
                    echo "<p style='color: red; text-align: center;'>Error: Please enter a valid positive number.</p>";
                }
            }
        ?>
        <!-- Pagination div for displaying page controls -->
        <div id="pagination"></div>
    </div>
    <!-- Return button to navigate back to the homepage -->
    <div class="return-button">
        <button onclick="window.location.href='../index.html'">Back</button>
    </div>
</div>

<!-- Link to external JavaScript -->
<script src="Scripts/script.js"></script>
</body>
</html>
