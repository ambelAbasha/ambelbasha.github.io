<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is authenticated
if (!isset($_SESSION['username'])) {
    header("Location: login_user.php");
    exit();
}

// Retrieve and sanitize the 'username' from the session
$username = $_SESSION['username'] ?? 'Guest';
$sanitized_username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

// Include the database connection
include('db_connect.php');
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    die("Database connection failed. Please try again later.");
}

// Fetch the user's name based on the username
$user_info_sql = "SELECT name FROM users WHERE username = ?";
$user_info_stmt = $conn->prepare($user_info_sql);
if (!$user_info_stmt) {
    error_log("Failed to prepare SQL statement: " . $conn->error);
    die("Internal server error. Please try again later.");
}

$user_info_stmt->bind_param('s', $username);
$user_info_stmt->execute();
$user_info_result = $user_info_stmt->get_result();

$user_name = $sanitized_username; // Fallback to username if name is not found
if ($user_info_result->num_rows > 0) {
    $user_info = $user_info_result->fetch_assoc();
    $user_name = htmlspecialchars($user_info['name'], ENT_QUOTES, 'UTF-8');
}

// Get unique genres for the dropdown
$genres_sql = "SELECT DISTINCT genres FROM music ORDER BY genres ASC";
$genres_result = $conn->query($genres_sql);

$header_message = ""; // Initialize header message
if ($genres_result === false) {
    $header_message = "SQL query failed: " . htmlspecialchars($conn->error);
} elseif ($genres_result->num_rows === 0) {
    $header_message = "No genres found.";
} else {
    $header_message = "Genres retrieved successfully."; // Success message
}

// Execute the external PHP file to update Spotify URLs
$response = file_get_contents('update_spotify_urls.php');

// Pagination setup
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Get current page number, default to 1
$results_per_page = 15;
$start_from = ($page - 1) * $results_per_page;

// Genre sorting setup
$sort_genre = isset($_GET['genre']) ? $conn->real_escape_string($_GET['genre']) : '';

// SQL query for fetching songs with genre sorting and pagination
$sql = "SELECT m.songno, m.genres, m.artist, m.song, m.`Recording Company` AS recording_company, m.record_date AS recording_date, u.username AS db_username, m.url, m.spotify_url
        FROM music AS m
        LEFT JOIN users AS u ON m.userid = u.userid";

if ($sort_genre) {
    $sql .= " WHERE m.genres = ?";
}

$sql .= " ORDER BY m.songno LIMIT ?, ?";

// Prepare the statement
$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log("Failed to prepare SQL statement: " . $conn->error);
    die("Internal server error. Please try again later.");
}

// Bind parameters based on genre filtering
if ($sort_genre) {
    $stmt->bind_param('sii', $sort_genre, $start_from, $results_per_page);
} else {
    $stmt->bind_param('ii', $start_from, $results_per_page);
}

// Execute the statement
$stmt->execute();
$result = $stmt->get_result();
if ($result === false) {
    error_log("Failed to get result set: " . $conn->error);
    die("Internal server error. Please try again later.");
}

// Check and apply theme
$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'dark';

// Debugging option
$debug = isset($_GET['debug']) && $_GET['debug'] === 'true';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="./Styles/Dashboard/dashboard.css">
    <?php if ($debug): ?>
    <link rel="stylesheet" type="text/css" href="./Styles/Dashboard/debugging.css">
    <?php endif; ?>
    <script src="../Music_DB_Html/Scripts/Dashboard/themeToggle.js"></script>
    <script src="../Music_DB_Html/Scripts/Dashboard/sortByGenre.js"></script>
    <script src="../Music_DB_Html/Scripts/Dashboard/logoutModal.js"></script>
</head>
<body class="<?php echo htmlspecialchars($theme === 'light' ? 'light-theme' : '', ENT_QUOTES, 'UTF-8'); ?>">
<div class="outer-container">
    <div class="container">
        <div class="inner-container">
            <!-- Theme switch -->
            <label class="switch">
                <input type="checkbox" id="theme-toggle" <?php echo $theme === 'light' ? 'checked' : ''; ?>>
                <span class="slider round"></span>
            </label>
            <span class="header-message">
                <?php echo htmlspecialchars($header_message, ENT_QUOTES, 'UTF-8'); ?>
            </span>
            <div class="header">
                <div class="welcome">
                    <span class="welcome">
                        Welcome to Music Galaxy, <?php echo htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8'); ?>
                      </span>
<!-- Logout Button -->
<span class="logout-button">
    <a href="javascript:void(0);" id="logoutButton">Logout</a>
</span>

<!-- Modal Structure -->
<div id="logoutModal" class="modal">
    <div class="modal-content">
        <h3>Are you sure you want to logout?</h3>
        <p>If you logout, you will be redirected to the homepage.</p>
        
        <!-- Action buttons inside modal-content -->
        <div class="modal-footer">
            <a href="javascript:void(0);" id="cancelLogout" class="modal-close btn">Cancel</a>
            <a href="index.html" id="confirmLogout" class="btn">Logout</a>
        </div>
    </div>
</div>
                </div>
            </div>
            <table class="table">
                <tr class="outer-border">
                    <th>Song Nr:</th>
                    <th>Genres
                        <select class="genre-select" id="genre-select" onchange="sortByGenre()">
                            <option value="">All Genres</option>
                            <?php
                            if ($genres_result->num_rows > 0) {
                                while ($genre = $genres_result->fetch_assoc()) {
                                    $selected = ($sort_genre === $genre['genres']) ? "selected" : "";
                                    echo "<option value='" . htmlspecialchars($genre['genres'], ENT_QUOTES, 'UTF-8') . "' $selected>" . htmlspecialchars($genre['genres'], ENT_QUOTES, 'UTF-8') . "</option>";
                                }
                            } else {
                                echo "<option value=''>No Genres Found</option>";
                            }
                            ?>
                        </select>
                    </th>
                    <th>Artist</th>
                    <th>Track</th>
                    <th>Recording Company</th>
                    <th>Recording Date</th>
                    <th>Username</th>
                    <th>URL</th>
                    <th>Spotify</th>
                </tr>

                <?php
                // Display the fetched songs
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $spotifyLink = $row['spotify_url']; // Get the stored Spotify link

                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['songno'], ENT_QUOTES, 'UTF-8') . "</td>";
                        echo "<td>" . htmlspecialchars($row['genres'], ENT_QUOTES, 'UTF-8') . "</td>";
                        echo "<td>" . htmlspecialchars($row['artist'], ENT_QUOTES, 'UTF-8') . "</td>";
                        echo "<td>" . htmlspecialchars($row['song'], ENT_QUOTES, 'UTF-8') . "</td>";
                        echo "<td>" . htmlspecialchars($row['recording_company'], ENT_QUOTES, 'UTF-8') . "</td>";
                        echo "<td>" . htmlspecialchars($row['recording_date'], ENT_QUOTES, 'UTF-8') . "</td>";
                        echo "<td>" . htmlspecialchars($row['db_username'], ENT_QUOTES, 'UTF-8') . "</td>";
                        echo "<td><a href='" . htmlspecialchars($row['url'], ENT_QUOTES, 'UTF-8') . "' target='_blank' rel='noopener noreferrer'>Visit</a></td>";
                        echo "<td><a href='" . htmlspecialchars($spotifyLink, ENT_QUOTES, 'UTF-8') . "' target='_blank' rel='noopener noreferrer'>Listen on Spotify</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No records found.</td></tr>";
                }
                ?>
            </table>
            <div class="pagination">
    <?php
    // Pagination
    $total_sql = "SELECT COUNT(*) AS total FROM music";
    if ($sort_genre) {
        $total_sql .= " WHERE genres = ?";
    }
    $total_stmt = $conn->prepare($total_sql);

    if ($sort_genre) {
        $total_stmt->bind_param('s', $sort_genre);
    }
    $total_stmt->execute();
    $total_result = $total_stmt->get_result();
    $total_row = $total_result->fetch_assoc();
    $total_records = $total_row['total'];
    $total_pages = ceil($total_records / $results_per_page);

    // Start pagination div
    echo '<div class="pagination">';

    // Previous link
    if ($page > 1) {
        $prev_link = "?page=" . ($page - 1) . "&genre=" . urlencode($sort_genre);
        echo "<a href='$prev_link' class='prev'> &lt;&lt; Previous</a>";
    }

    // Display limited page numbers (current, next 2)
    for ($i = max(1, $page - 1); $i <= min($total_pages, $page + 1); $i++) {
        if ($i === $page) {
            // Use the same style for the current page as links
            echo "<span class='current'>$i</span>"; // Current page, not clickable
        } else {
            echo "<a href='?page=$i&genre=" . urlencode($sort_genre) . "' class='page-number'>$i</a>";
        }
    }

    // Next link
    if ($page < $total_pages) {
        $next_link = "?page=" . ($page + 1) . "&genre=" . urlencode($sort_genre);
        echo "<a href='$next_link' class='next'>Next &gt;&gt;</a>";
    }

    // End pagination div
    echo '</div>';

    // Close the statement
    $total_stmt->close();
    $conn->close();
    ?>
</div>
</body>
</html>
