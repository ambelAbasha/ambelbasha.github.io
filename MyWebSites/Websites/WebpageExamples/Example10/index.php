<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Information -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title of the Page -->
    <title>Recursion Example</title>

    <!-- Meta Tags for SEO and Author Information -->
    <meta name="author" content="Ambel Basha">
    <meta name="description" content="This is an educational example demonstrating the use of recursion and the repeat function with substr() in PHP.">
    <meta name="keywords" content="PHP, recursion, repeat, substr, programming, educational portfolio, Ambel Basha">
    <meta name="robots" content="index, follow">
    
    <!-- Link to the external stylesheet for styling -->
    <link rel="stylesheet" href="Styles/styles.css">
</head>
<body>

<div class="outer-container">
    <div class="container">
        <h3>Using recursion and repeat with the use of substr.</h3>

        <!-- Code Box for Recursion Example -->
        <div class="code-box">
            <h3>"Recurse"</h3>
            <p>
                <?php
                // Function to demonstrate recursion by reversing a string
                function recursion($str) {
                    // Get the length of the string
                    $length = strlen($str);
                    // Extract the last character using substr
                    $x = substr($str, $length - 1, 1);
                    // Output the character
                    echo $x;
                    // Remove the last character from the string
                    $str = substr($str, 0, $length - 1);
                    // Continue recursion until the string is empty
                    if ($str != "") {
                        recursion($str);
                    }
                }

                // Display an example using the word "Programming"
                echo 'Example: Programming' . "<br>";
                echo 'Reverse: ';
                // Call the recursion function to reverse the string
                recursion("Programming");
                ?>
            </p>
        </div>

        <!-- Code Box for Repeat Example -->
        <div class="code-box">
            <h3>"Repeat"</h3>
            <p>
                <?php
                // Function to demonstrate a non-recursive approach for reversing a string
                function repeat($str) {
                    // Get the length of the string
                    $length = strlen($str);
                    // Loop through the string in reverse order
                    for ($i = $length - 1; $i >= 0; $i--) {
                        // Extract the character at the current position
                        $x = substr($str, $i, 1);
                        // Output the character
                        echo $x;
                    }
                }

                // Display an example using the word "Accessing"
                $str = "Accessing";
                echo 'Example: Accessing' . "<br>";
                echo 'Reverse: ';
                // Call the repeat function to reverse the string
                repeat($str);
                ?>
            </p>
        </div>
    </div>

    <!-- Return Button to Navigate Back to the Homepage -->
    <div class="return-button">
        <button onclick="window.location.href='../index.html'">Back</button>
    </div>
</div>

</body>
</html>
