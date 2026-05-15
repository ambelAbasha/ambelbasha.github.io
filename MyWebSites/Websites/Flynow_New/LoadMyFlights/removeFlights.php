<?php 
session_start();
require 'config.php';

header('Content-Type: application/json');

// Debugging: Log session and CSRF token
error_log('Session login: ' . ($_SESSION['login'] ?? 'No session'));
error_log('CSRF Token: ' . ($_SESSION['csrf_token'] ?? 'No CSRF token'));

// Verify session
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

// CSRF Token validation
$csrf_token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
if ($csrf_token !== $_SESSION['csrf_token']) {
    echo json_encode(['success' => false, 'error' => 'Invalid CSRF token']);
    exit;
}

// Parse JSON input
$data = json_decode(file_get_contents('php://input'), true);

// Debugging: Log input data
error_log('Input Data: ' . json_encode($data));

if (!isset($data['booking_ids']) || empty($data['booking_ids'])) {
    echo json_encode(['success' => false, 'error' => 'No booking IDs provided']);
    exit;
}

$bookingIds = $data['booking_ids'];

// Validate booking IDs
if (!is_array($bookingIds) || count($bookingIds) === 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid booking IDs']);
    exit;
}

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    error_log('Database connection failed: ' . $conn->connect_error);
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

// Prepare SQL query to fetch booking details
$placeholders = implode(',', array_fill(0, count($bookingIds), '?'));
$sqlDetails = "
    SELECT b.booking_id,
           ac.comp_name AS Airline,
           f.departure_airport AS `FromCity`,
           f.destination_airport AS `ToCity`,
           CASE WHEN f.business_class > 0 THEN 'Business' ELSE 'Economy' END AS Class,
           p.passenger_type AS Person,
           DATE_FORMAT(f.departure_date, '%d-%m-%Y %H:%i') AS Departure,
           DATE_FORMAT(f.return_date, '%d-%m-%Y %H:%i') AS ReturnDate
    FROM bookings b
    JOIN flight f ON b.flight_id = f.flight_id
    JOIN airline_company ac ON f.air_com_id = ac.air_com_id
    JOIN passenger p ON b.passenger_id = p.passenger_id
    WHERE b.booking_id IN ($placeholders)";

$stmtDetails = $conn->prepare($sqlDetails);

// Debugging: Check if the statement preparation is successful
if (!$stmtDetails) {
    error_log('SQL prepare failed for details query: ' . $conn->error);
    echo json_encode(['success' => false, 'error' => 'SQL prepare failed for details query']);
    exit;
}

$stmtDetails->bind_param(str_repeat('i', count($bookingIds)), ...$bookingIds);
$stmtDetails->execute();
$resultDetails = $stmtDetails->get_result();

// Prepare the message with booking details
$removedBookings = [];
while ($row = $resultDetails->fetch_assoc()) {
    $removedBookings[] = "
        <strong>Booking ID:</strong> {$row['booking_id']}<br>
        <strong>Airline:</strong> {$row['Airline']}<br>
        <strong>From (City):</strong> {$row['FromCity']}<br>
        <strong>To (City):</strong> {$row['ToCity']}<br>
        <strong>Class:</strong> {$row['Class']}<br>
        <strong>Person:</strong> {$row['Person']}<br>
        <strong>Departure:</strong> {$row['Departure']}<br>
        <strong>Return Date:</strong> {$row['ReturnDate']}<br><br>";
}

// Delete the bookings
$sqlDelete = "DELETE FROM bookings WHERE booking_id IN ($placeholders)";
$stmtDelete = $conn->prepare($sqlDelete);

// Debugging: Check if the delete statement is prepared
if (!$stmtDelete) {
    error_log('SQL prepare failed for delete query: ' . $conn->error);
    echo json_encode(['success' => false, 'error' => 'SQL prepare failed for delete query']);
    exit;
}

$stmtDelete->bind_param(str_repeat('i', count($bookingIds)), ...$bookingIds);

if ($stmtDelete->execute()) {
    // Send a JSON response with a success message
    $message = implode("\n", $removedBookings);
    echo json_encode(['success' => true, 'message' => 'Successfully removed the following bookings:<br>' . $message]);
} else {
    error_log('Error executing delete query: ' . $stmtDelete->error);
    echo json_encode(['success' => false, 'error' => 'Error deleting bookings: ' . $stmtDelete->error]);
}

// Close resources
$stmtDetails->close();
$stmtDelete->close();
$conn->close();
exit;
?>
