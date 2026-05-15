<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta tag for character encoding to ensure proper text display -->
    <meta charset="UTF-8">

    <!-- Meta tag for responsive design on various screen sizes -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Title of the webpage, displayed on the browser tab -->
    <title>Number Formats</title>

    <!-- Meta tags for SEO and search engines -->
    <meta name="author" content="Ambel Basha">
    <meta name="description" content="Explore different formats for displaying numbers in PHP. Learn how to output numbers in decimal, binary, octal, hexadecimal, and more.">
    <meta name="keywords" content="PHP, Number Formats, Decimal, Binary, Octal, Hexadecimal, PHP Output, Web Development">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#ffffff">

    <!-- Link to external stylesheet for consistent styling across pages -->
    <link rel="stylesheet" type="text/css" href="Styles/styles.css">
</head>

<body>

    <!-- Outer container to center the content and provide structure -->
    <div class="outer-container">

        <!-- Inner container to hold the content -->
        <div class="container">

            <!-- Heading for the content section -->
            <center><h2>Different Formats of a Number:</h2></center>

            <!-- Table displaying different formats of the number -->
            <table>
                <tr>
                    <th>Format</th>
                    <th>Result</th>
                </tr>
                <?php
                    // Define a number to demonstrate various formats
                    $number = 543;

                    // Display number in different formats using printf
                    printf("<tr><td>Decimal</td><td>%d</td></tr>", $number);  // Decimal format
                    printf("<tr><td>Binary</td><td>%b</td></tr>", $number);   // Binary format
                    printf("<tr><td>Double</td><td>%f</td></tr>", $number);    // Floating-point format
                    printf("<tr><td>Octal</td><td>%o</td></tr>", $number);    // Octal format
                    printf("<tr><td>String</td><td>%s</td></tr>", $number);   // String format
                    printf("<tr><td>Hex (lower)</td><td>%x</td></tr>", $number); // Lowercase Hexadecimal format
                    printf("<tr><td>Hex (upper)</td><td>%X</td></tr>", $number); // Uppercase Hexadecimal format
                ?>
            </table>
        </div>

        <!-- Return button to go back to the homepage -->
        <div class="return-button">
            <button onclick="window.location.href='../index.html'">Back</button>
        </div>

    </div>

</body>
</html>
