<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta charset for proper character encoding -->
    <meta charset="UTF-8">

    <!-- Viewport meta tag for responsive design, ensuring it adjusts to different screen sizes -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Title of the page displayed in the browser tab -->
    <title>Variable Types | Ambel Basha Portfolio</title>

    <!-- Meta description for search engine optimization (SEO), describing the content of the page -->
    <meta name="description" content="Explore different types of variables in PHP, including integers, strings, booleans, and arrays. A small snippet of Ambel Basha's web development portfolio, focusing on educative content.">
    
    <!-- Keywords for search engines to identify the content -->
    <meta name="keywords" content="PHP, variables, data types, programming, Ambel Basha, web development, portfolio, educational content">
    
    <!-- Author meta tag for crediting the creator -->
    <meta name="author" content="Ambel Basha">

    <!-- Robots meta tag to control indexing by search engines -->
    <meta name="robots" content="index, follow">

    <!-- Link to the external CSS file for page styling -->
    <link rel="stylesheet" href="Styles/styles.css">
</head>
<body>
    <!-- Main content container -->
    <div class="container">
        
        <!-- Table container with a 3D look, for displaying variable types -->
        <div class="table-container">
            <h3>Different Types of Variables:</h3>
            <table>
                <thead>
                    <tr>
                        <th>Variable</th>
                        <th>Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Initialize variable and show its type
                    $testing;
                    $testing = 5; // Integer value
                    echo "<tr><td>Variable's value $testing</td><td>", gettype($testing), "</td></tr>";

                    $testing = 'five'; // String value
                    echo "<tr><td>Variable's value $testing</td><td>", gettype($testing), "</td></tr>";

                    $testing = 5.024; // Float value
                    echo "<tr><td>Variable's value $testing</td><td>", gettype($testing), "</td></tr>";

                    $testing = true; // Boolean value
                    echo "<tr><td>Variable's value true</td><td>", gettype($testing), "</td></tr>";

                    $testing = array('apple', 'orange', 'pear'); // Array value
                    echo "<tr><td>Variable's value array('apple', 'orange', 'pear')</td><td>", gettype($testing), "</td></tr>";
                    ?>
                </tbody>
            </table>
        </div>
        
        <!-- Back button for navigation -->
        <div class="return-button">
            <button onclick="window.location.href='../index.html'">Back</button>
        </div>

    </div> <!-- End of container -->
</body>
</html>
