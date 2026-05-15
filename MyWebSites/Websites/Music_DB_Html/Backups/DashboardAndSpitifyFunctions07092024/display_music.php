<?php
session_start(); // Start the session
include('db_connect.php'); // Database connection

if (!isset($_SESSION['username'])) {
    // If there's no session, redirect to login page
    header("Location: login_user.php");
    exit;
}

// Pagination setup
$results_per_page = 15;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $results_per_page;

// Fetch music data with pagination
$sql = "SELECT * FROM music LIMIT ?, ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log("Failed to prepare SQL statement: " . $conn->error);
    die("Internal server error. Please try again later.");
}

$stmt->bind_param('ii', $offset, $results_per_page);
$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    echo "Error executing query: " . htmlspecialchars($conn->error, ENT_QUOTES, 'UTF-8');
} elseif ($result->num_rows > 0) {
    // Display records in a table
    echo "<table border='1'>";
    echo "<tr><th>Song Number</th><th>Genres</th><th>Artist</th><th>URL</th><th>Username</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['songno'], ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td>" . htmlspecialchars($row['genres'], ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td>" . htmlspecialchars($row['artist'], ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td><a href='" . htmlspecialchars($row['url'], ENT_QUOTES, 'UTF-8') . "' target='_blank'>Visit</a></td>";
        echo "<td>" . htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') . "</td>"; // Use session username
        echo "</tr>";
    }
    echo "</table>";

    // Pagination controls
    $total_sql = "SELECT COUNT(*) AS total FROM music";
    $total_result = $conn->query($total_sql);
    $total_rows = $total_result->fetch_assoc()['total'];
    $total_pages = ceil($total_rows / $results_per_page);

    echo "<div class='pagination'>";
    if ($page > 1) {
        echo "<a href='?page=" . ($page - 1) . "'>&lt;&lt; Previous</a> ";
    }
    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<a href='?page=$i'>" . $i . "</a> ";
    }
    if ($page < $total_pages) {
        echo "<a href='?page=" . ($page + 1) . "'>Next &gt;&gt;</a>";
    }
    echo "</div>";
} else {
    echo "No records found.";
}

$stmt->close();
$conn->close();
?>
