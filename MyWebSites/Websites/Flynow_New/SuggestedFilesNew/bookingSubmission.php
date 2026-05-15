<?php
session_start();

// Generate a CSRF token if it doesn't exist
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Database connection (adjust credentials as needed)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "flynow"; // Your database name

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {

    // Form fields
    $airline = $_POST['airlineSelect'];
    $from = $_POST['fromSelect'];
    $to = $_POST['toSelect'];
    $class = $_POST['classSelect'];
    $person = $_POST['personSelect'];
    $departure = $_POST['departureDate'];
    $return = $_POST['returnDate'];

    // Insert data into the database
    try {
        $sql = "INSERT INTO bookings (username, airline, `from`, `to`, class, person, departure_date, return_date) 
                VALUES (:username, :airline, :from, :to, :class, :person, :departure, :return)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $_SESSION['username']);
        $stmt->bindParam(':airline', $airline);
        $stmt->bindParam(':from', $from);
        $stmt->bindParam(':to', $to);
        $stmt->bindParam(':class', $class);
        $stmt->bindParam(':person', $person);
        $stmt->bindParam(':departure', $departure);
        $stmt->bindParam(':return', $return);
        $stmt->execute();

        // Provide feedback to the user
        echo "Booking preferences saved successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
