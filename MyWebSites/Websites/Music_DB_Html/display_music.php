<?php
session_start(); // Start the session
include('db_connect.php'); // Database connection

if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: login_user.php");
    exit;
}

// Pagination setup
$results_per_page = 15;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, $page); // Ensure page is at least 1
$offset = ($page - 1) * $results_per_page;

// Debugging output
error_log("Page: $page, Offset: $offset, Results per page: $results_per_page");

// Fetch music data with pagination
$sql = "SELECT * FROM music ORDER BY songno LIMIT ?, ?";
error_log("SQL Query: $sql");

// Prepare and execute the statement
$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log("Failed to prepare SQL statement: " . $conn->error);
    die("Internal server error. Please try again later.");
}

// Bind parameters
$stmt->bind_param('ii', $offset, $results_per_page);
$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    error_log("Error executing query: " . $conn->error);
    die("Internal server error. Please try again later.");
}

// Display music records
echo "<table border='1'>";
echo "<tr><th>Song Nr</th><th>Genres</th><th>Artist</th><th>Track</th><th>Recording Company</th><th>Recording Date</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['songno'], ENT_QUOTES, 'UTF-8') . "</td>";
    echo "<td>" . htmlspecialchars($row['genres'], ENT_QUOTES, 'UTF-8') . "</td>";
    echo "<td>" . htmlspecialchars($row['artist'], ENT_QUOTES, 'UTF-8') . "</td>";
    echo "<td>" . htmlspecialchars($row['song'], ENT_QUOTES, 'UTF-8') . "</td>";
    echo "<td>" . htmlspecialchars($row['recording_company'], ENT_QUOTES, 'UTF-8') . "</td>";
    echo "<td>" . htmlspecialchars($row['record_date'], ENT_QUOTES, 'UTF-8') . "</td>";
    echo "</tr>";
}
echo "</table>";

// Pagination controls
$total_sql = "SELECT COUNT(*) AS total FROM music";
$total_result = $conn->query($total_sql);
if ($total_result === false) {
    error_log("Failed to fetch total count: " . $conn->error);
    die("Internal server error. Please try again later.");
}

$total_rows = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $results_per_page);

// Display pagination links
echo "<div class='pagination'>";
if ($page > 1) {
    echo "<a href='?page=" . ($page - 1) . "' class='prev'>&lt;&lt; Previous</a> | ";
}
for ($i = 1; $i <= $total_pages; $i++) {
    $class = ($i == $page) ? 'class="current"' : '';
    echo "<a href='?page=$i' $class>$i</a> ";
}
if ($page < $total_pages) {
    echo "| <a href='?page=" . ($page + 1) . "' class='next'>Next &gt;&gt;</a>";
}
echo "</div>";

$conn->close();
?>
