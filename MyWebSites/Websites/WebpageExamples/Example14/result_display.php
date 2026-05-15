<?php
// Setting content type for UTF-8
header('Content-Type: text/html; charset=utf-8');

// Get the input values from the URL
$val = isset($_GET['val']) ? htmlspecialchars($_GET['val']) : '';
$result = isset($_GET['result']) ? htmlspecialchars($_GET['result']) : '';

// Split the input value to determine context
$context = strpos($val, 'Country') !== false ? 'Country' : (strpos($val, 'Capital') !== false ? 'Capital' : '');

// Define the output messages based on the context
$outputMessage = "";
if ($context === 'Country') {
    // Searching for the capital of a country
    $countryName = str_replace('Country ', '', $val); // Extract the country name
    if (!empty($result) && $result !== "Not found") {
        $outputMessage = "<span class='result-success'>$result</span> is the capital city of <strong>$countryName</strong>.";
    } else {
        $outputMessage = "<span class='result-error'>Sorry, we couldn't find the capital for the country: <strong>$countryName</strong>. Please check the country name.</span>";
    }
} elseif ($context === 'Capital') {
    // Searching for the country with a given capital
    $capitalName = str_replace('Capital ', '', $val); // Extract the capital name
    if (!empty($result) && $result !== "Not found") {
        $outputMessage = "The city <span class='result-success'>$capitalName</span> is the capital of <strong>$result</strong>.";
    } else {
        $outputMessage = "<span class='result-error'>Sorry, we couldn't find the country for the capital: <strong>$capitalName</strong>. Please check the capital name.</span>";
    }
} else {
    // If the context is not recognized
    $outputMessage = "<span class='result-error'>Invalid search request. Please specify a valid query.</span>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Result</title>
    <link rel="stylesheet" href="./Styles/resultStyles.css"> <!-- Link to external CSS file -->
</head>
<body>
    <div class="outer-container">
        <div class="container">
            <div class="box">
                <h1>Search Result</h1>
                <p class="outputMessage"><?php echo $outputMessage; ?></p> <!-- Apply the class to the <p> tag -->
                <div class="return-button">
                    <button onclick="window.location.href='./index.html'">Back</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
