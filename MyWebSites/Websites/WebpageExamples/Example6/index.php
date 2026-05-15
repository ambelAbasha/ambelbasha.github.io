<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Metadata for the webpage -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A simple PHP example that demonstrates how to tokenize a string into individual words using PHP's strtok function.">
    <meta name="author" content="Ambel Basha">
    <meta name="keywords" content="PHP, string tokenization, strtok function, string manipulation, web development">
    <meta name="robots" content="index, follow"> <!-- Instructs search engines to index the page and follow links -->
    <title>Tokenize String</title>
    <link rel="stylesheet" href="Styles/styles.css">
</head>
<body>
    <!-- Outer container for the page layout -->
    <div class="outer-container">
        <!-- Main container for content -->
        <div class="container">
            <!-- Header section displaying the original string -->
            <div class="header">
                <h4>Given String to tokenize:</h4>
                <p>hl=en&ie=UTF-8&q=php+development+books&btnG=Google+Search</p>
            </div>
            
            <!-- Table container to display tokenized results -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Word Index</th>
                            <th>Word</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            // Original string to be tokenized
                            $given_string = "hl=en&ie=UTF-8&q=php+development+books&btnG=Google+Search";
                            
                            // Tokenize the string based on delimiters like spaces, newline, and '&' character
                            $token = strtok($given_string, " \n&");
                            $i = 0;
                            
                            // Loop through each token and display its index and value in the table
                            while ($token !== false) {
                                $i++; // Increment index for each token
                                echo "<tr><td>$i</td><td>$token</td></tr>"; // Display token and its index
                                
                                // Get the next token
                                $token = strtok(" \n&");
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Return button to navigate back to the homepage -->
    <div class="return-button">
        <button onclick="window.location.href='../index.html'">Back</button>
    </div>
</body>
</html>
