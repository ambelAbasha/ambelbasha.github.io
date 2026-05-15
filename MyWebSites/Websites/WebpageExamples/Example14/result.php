<?php
// Setting content type for UTF-8
header('Content-Type: text/html; charset=utf-8');

// Countries and their capitals
$countries = array(
    "Greece" => "Athens",
    "Brazil" => "Brasília",
    "France" => "Paris",
    "Germany" => "Berlin",
    "Italy" => "Rome",
    "Spain" => "Madrid",
    "United States" => "Washington, D.C.",
    "Canada" => "Ottawa",
    "Japan" => "Tokyo",
    "Australia" => "Canberra",
    "United Kingdom" => "London",
    "China" => "Beijing",
    "India" => "New Delhi",
    "Russia" => "Moscow",
    "Mexico" => "Mexico City",
    "South Africa" => "Pretoria",
    "South Korea" => "Seoul",
    "Saudi Arabia" => "Riyadh",
    "Argentina" => "Buenos Aires",
    "Egypt" => "Cairo",
    "Turkey" => "Ankara",
    "Thailand" => "Bangkok",
    "Indonesia" => "Jakarta",
    "Nigeria" => "Abuja",
    "Pakistan" => "Islamabad",
    "Philippines" => "Manila",
    "Colombia" => "Bogota",
    "Vietnam" => "Hanoi",
    "Iran" => "Tehran",
    "Iraq" => "Baghdad",
    "Ukraine" => "Kyiv",
    "Poland" => "Warsaw",
    "Sweden" => "Stockholm",
    "Norway" => "Oslo",
    "Finland" => "Helsinki",
    "Denmark" => "Copenhagen",
    "Austria" => "Vienna",
    "Belgium" => "Brussels",
    "Switzerland" => "Bern",
    "Czech Republic" => "Prague",
    "Portugal" => "Lisbon",
    "Hungary" => "Budapest",
    "New Zealand" => "Wellington",
    "Singapore" => "Singapore",
    "Malaysia" => "Kuala Lumpur",
    "Bangladesh" => "Dhaka",
    "Kenya" => "Nairobi",
    "Morocco" => "Rabat",
    "Algeria" => "Algiers",
    "Chile" => "Santiago",
    "Peru" => "Lima",
    "Cuba" => "Havana"
);

// Get the POST data and sanitize it
$val = isset($_POST['val']) ? htmlspecialchars($_POST['val']) : null; // Input value
$option = isset($_POST['option']) ? (int)$_POST['option'] : null;  // Selected option (1 or 2)

// Validate the input: only allow letters
if (!preg_match("/^[a-zA-Z\s]+$/", $val)) {
    // Redirect back with error message
    header("Location: index.html?error=" . urlencode("Invalid input. Please enter only letters."));
    exit;
}

$result = null; // Default result

// Find the corresponding result based on the option
if ($option == 1) { // If searching for capital
    foreach ($countries as $country => $capital) {
        if (strcasecmp($country, $val) == 0) { // Case-insensitive match
            $result = $capital; // Get the corresponding capital
            break;
        }
    }
    // Add context to the displayed value
    $val = "Country " . $val;
} else if ($option == 2) { // If searching for country
    foreach ($countries as $country => $capital) {
        if (strcasecmp($capital, $val) == 0) { // Case-insensitive match
            $result = $country; // Get the corresponding country
            break;
        }
    }
    // Add context to the displayed value
    $val = "Capital " . $val;
}

if ($result === null) { // If no match found
    $result = "Not found"; // "Not found" message
}

// Redirect to another page with the result
header("Location: result_display.php?val=" . urlencode($val) . "&result=" . urlencode($result)); // Send result to 'result_display.php'
exit; // Ensure the script stops after redirecting
?>
