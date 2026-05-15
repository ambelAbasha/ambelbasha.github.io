<?php
// Ensure the content is sent as plain text (not HTML)
header('Content-Type: text/plain');

// Debugging: Show the GET parameters
// print_r($_GET); // Optional, for debugging purposes

// Define the Fibonacci function
function f1() {
    // Initialize the first two Fibonacci numbers
    $fib = [0, 1];
    
    // Calculate the first 30 Fibonacci numbers
    for ($i = 2; $i < 30; $i++) {
        $fib[] = $fib[$i - 1] + $fib[$i - 2];
    }
    
    // Return the Fibonacci sequence as a string, separated by commas
    echo implode(', ', $fib);
}

// Define the Golden Ratio Approximation function
function f2() {
    // Initialize the first two Fibonacci numbers
    $fib = [0, 1];
    
    // Calculate the first 30 Fibonacci numbers
    for ($i = 2; $i < 30; $i++) {
        $fib[] = $fib[$i - 1] + $fib[$i - 2];
    }
    
    // Create an array to store the Golden Ratio approximations (Fibonacci ratios)
    $golden_ratios = [];
    for ($i = 2; $i < count($fib); $i++) {
        $golden_ratios[] = round($fib[$i] / $fib[$i - 1], 5);  // Ratio of consecutive Fibonacci numbers (rounded to 5 decimal places)
    }
    
    // Return the Golden Ratio sequence (approximations) as a string, separated by commas
    echo implode(', ', $golden_ratios);
}

// Check if the function to call is specified in the URL
if (isset($_GET['func'])) {
    if ($_GET['func'] == 'f1') {
        f1();  // Call Fibonacci function
    } elseif ($_GET['func'] == 'f2') {
        f2();  // Call Golden Ratio approximation function
    } else {
        echo "Invalid function specified!";
    }
} else {
    echo "Please specify a function to call (e.g., ?func=f1 or ?func=f2).";
}
?>
