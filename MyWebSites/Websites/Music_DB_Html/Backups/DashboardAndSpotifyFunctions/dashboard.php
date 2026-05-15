<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login_user.php");
    exit();
}

include('db_connect.php');
include('spotify_functions.php');

// Initialize variables
$header_message = '';
$user_name = '';
$result = null;
$sort_genre = '';
$theme = 'dark'; // Default theme

$username = $_SESSION['username'] ?? 'Guest';
$sanitized_username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');

// Fetch user information
$user_info_sql = "SELECT name FROM users WHERE username = ?";
$user_info_stmt = $conn->prepare($user_info_sql);

if (!$user_info_stmt) {
    die("Failed to prepare SQL statement: " . htmlspecialchars($conn->error));
}

$user_info_stmt->bind_param('s', $username);
$user_info_stmt->execute();
$user_info_result = $user_info_stmt->get_result();

if ($user_info_result->num_rows > 0) {
    $user_info = $user_info_result->fetch_assoc();
    $user_name = htmlspecialchars($user_info['name'], ENT_QUOTES, 'UTF-8');
}

// Get unique genres for the dropdown
$genres_sql = "SELECT DISTINCT genres FROM music ORDER BY genres ASC";
$genres_result = $conn->query($genres_sql);

if ($genres_result === false) {
    $header_message = "SQL query failed: " . htmlspecialchars($conn->error);
} elseif ($genres_result->num_rows === 0) {
    $header_message = "No genres found.";
} else {
    $header_message = "Genres retrieved successfully.";
}

// Pagination setup
$lastId = isset($_GET['lastId']) ? (int)$_GET['lastId'] : 0;
$results_per_page = 15;

// Genre sorting setup
if (isset($_GET['genre'])) {
    $sort_genre = mysqli_real_escape_string($conn, $_GET['genre']);
}

// SQL query for fetching songs with genre sorting and pagination
$sql = "SELECT m.songno, m.genres, m.artist, m.song, m.`Recording Company` AS recording_company, m.`record_date` AS recording_date, u.username AS db_username, m.url
        FROM music AS m
        LEFT JOIN users AS u ON m.userid = u.userid
        WHERE m.songno > ?";

if ($sort_genre) {
    $sql .= " AND m.genres = ?";
}

$sql .= " ORDER BY m.songno LIMIT ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Failed to prepare SQL statement: " . htmlspecialchars($conn->error));
}

if ($sort_genre) {
    $stmt->bind_param('isi', $lastId, $sort_genre, $results_per_page);
} else {
    $stmt->bind_param('ii', $lastId, $results_per_page);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    die("Failed to get result set: " . htmlspecialchars($conn->error));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="./Styles/Dashboard/dashboard.css">
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
                    <span class="logout-button">
                        <a href="index.html">Logout</a>
                    </span>
                </div>
            </div>
            <table class="table">
                <tr class="outer-border">
                    <th>Song Nr:</th>
                    <th>Genres
                        <select class="genre-select" id="genre-select" onchange="sortByGenre()">
                            <option value="">All Genres</option>
                            <?php
                            // Populate genre dropdown
                            while ($genre_row = $genres_result->fetch_assoc()) {
                                $genre = htmlspecialchars($genre_row['genres'], ENT_QUOTES, 'UTF-8');
                                $selected = $genre === $sort_genre ? 'selected' : '';
                                echo "<option value='$genre' $selected>$genre</option>";
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
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Fetch Spotify link
                        $spotifyLink = searchSpotifyTrack($row['artist'], $row['song']);

                        // Output the row
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['songno'], ENT_QUOTES, 'UTF-8') . "</td>";
                        echo "<td>" . htmlspecialchars($row['genres'], ENT_QUOTES, 'UTF-8') . "</td>";
                        echo "<td>" . htmlspecialchars($row['artist'], ENT_QUOTES, 'UTF-8') . "</td>";
                        echo "<td>" . htmlspecialchars($row['song'], ENT_QUOTES, 'UTF-8') . "</td>";
                        echo "<td>" . htmlspecialchars($row['recording_company'], ENT_QUOTES, 'UTF-8') . "</td>";
                        echo "<td>" . date("d-m-Y", strtotime($row['recording_date'])) . "</td>";
                        echo "<td>" . htmlspecialchars($row['db_username'], ENT_QUOTES, 'UTF-8') . "</td>";
                        echo "<td><a href='" . htmlspecialchars($row['url'], ENT_QUOTES, 'UTF-8') . "' target='_blank'>Visit</a></td>";
                        echo "<td><a href='" . htmlspecialchars($spotifyLink, ENT_QUOTES, 'UTF-8') . "' target='_blank' rel='noopener noreferrer'>Listen on Spotify</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No records found.</td></tr>";
                }
                ?>
            </table>

            <!-- JavaScript for sorting by genre -->
            <script>
            function sortByGenre() {
                const genreSelect = document.getElementById("genre-select");
                const selectedGenre = genreSelect.value;
                let url = "?lastId=0"; // Reset to first record

                if (selectedGenre) {
                    url += `&genre=${encodeURIComponent(selectedGenre)}`;
                }

                window.location.href = url;
            }

            // Theme switch toggle
            const themeToggle = document.getElementById("theme-toggle");
            themeToggle.addEventListener("change", function () {
                const currentUrl = window.location.href.split('?')[0];
                const newTheme = this.checked ? 'light' : 'dark';
                window.location.href = `${currentUrl}?theme=${newTheme}`;
            });
            </script>

        </div>
    </div>
</div>
</body>
</html>
