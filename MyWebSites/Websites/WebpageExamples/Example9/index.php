<!DOCTYPE html> 
<html lang="en">
<head>
    <!-- Meta Information -->
    <meta charset="UTF-8">
    <!-- Viewport configuration for responsive design -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Author Information -->
    <meta name="author" content="Ambel Basha">
    <!-- Page description for search engines and accessibility -->
    <meta name="description" content="Educational portfolio by Ambel Basha showcasing an example of calculating the biggest, smallest, and average numbers using PHP functions.">
    <!-- Keywords for search engine optimization (SEO) -->
    <meta name="keywords" content="PHP, educational portfolio, biggest number, smallest number, average number, functions, programming">
    <!-- Robots meta tag to control search engine crawling -->
    <meta name="robots" content="index, follow">
    <!-- Title of the webpage -->
    <title>Biggest, Smallest, and Average Number</title>
    <!-- Link to the external stylesheet for styling -->
    <link rel="stylesheet" href="Styles/styles.css">
</head>
<body>
    <!-- Outer container to hold the content and center it on the page -->
    <div class="outer-container">
        <!-- Main content container that holds the box and table -->
        <div class="container">
            <!-- Box containing the content and results -->
            <div class="box">
                <!-- Heading with descriptive text about the example -->
                <h3><p>Biggest, Smallest, and Average</p><p>Numbers using Functions</p></h3>
                
                <!-- Table displaying the results of the PHP functions -->
                <table>
                    <!-- Table header with column titles -->
                    <tr>
                        <th>Values</th>
                        <th>Function Results</th>
                    </tr>
                    
                    <!-- First row showing results for a set of numbers -->
                    <tr>
                        <td>For a=58, b=32, c=19</td>
                        <td>
                            <!-- PHP code to display results using the defined functions -->
                            <?php 
                                echo "Biggest: " . biggest(58, 32, 19) . "<br>";
                                echo "Smallest: " . smallest(58, 32, 19) . "<br>";
                                echo "Average: " . average(58, 32, 19);
                            ?>
                        </td>
                    </tr>
                    
                    <!-- Second row showing results for a different set of numbers -->
                    <tr>
                        <td>For a=12, b=30, c=75</td>
                        <td>
                            <!-- PHP code to display results using the defined functions -->
                            <?php 
                                echo "Biggest: " . biggest(12, 30, 75) . "<br>";
                                echo "Smallest: " . smallest(12, 30, 75) . "<br>";
                                echo "Average: " . average(12, 30, 75);
                            ?>
                        </td>
                    </tr>
                    
                    <!-- Third row showing results with a null value -->
                    <tr>
                        <td>For a=4, b=17, c=null</td>
                        <td>
                            <!-- PHP code to display results using the defined functions, handling null values -->
                            <?php 
                                echo "Biggest: " . biggest(4, 17, null) . "<br>";
                                echo "Smallest: " . smallest(4, 17, null) . "<br>";
                                echo "Average: " . average(4, 17, null);
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Return button to navigate back to the homepage -->
        <div class="return-button">
            <button onclick="window.location.href='../index.html'">Back</button>
        </div>
    </div>

    <!-- PHP Functions to calculate biggest, smallest, and average -->
    <?php
        // Function to find the biggest number
        function biggest($a, $b, $c) {
            $values = array_filter([$a, $b, $c], function($val) {
                return $val !== null; // Filter out null values
            });

            if (empty($values)) {
                return 'No values entered for all variables!'; // Handle case when all values are null
            } else {
                return max($values); // Return the biggest number
            }
        }

        // Function to find the smallest number
        function smallest($a, $b, $c) {
            $values = array_filter([$a, $b, $c], function($val) {
                return $val !== null; // Filter out null values
            });

            if (empty($values)) {
                return 'No values entered for all variables!'; // Handle case when all values are null
            } else {
                return min($values); // Return the smallest number
            }
        }

        // Function to calculate the average of the numbers
        function average($a, $b, $c) {
            $values = array_filter([$a, $b, $c], function($val) {
                return $val !== null; // Filter out null values
            });

            if (empty($values)) {
                return 'No values entered for all variables!'; // Handle case when all values are null
            } else {
                // Return the rounded average of the values
                return round(array_sum($values) / count($values), 2);
            }
        }
    ?>
</body>
</html>
