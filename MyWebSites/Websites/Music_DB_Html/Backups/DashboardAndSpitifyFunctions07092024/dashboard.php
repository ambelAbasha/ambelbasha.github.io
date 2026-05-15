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

// Pagination setup
$lastId = isset($_GET['lastId']) ? (int)$_GET['lastId'] : 0;
$results_per_page = 15;

// Genre sorting setup
$sort_genre = isset($_GET['genre']) ? $conn->real_escape_string($_GET['genre']) : '';

// SQL query for fetching songs with genre sorting and pagination
$sql = "SELECT m.songno, m.genres, m.artist, m.song, m.`Recording Company` AS recording_company, m.record_date AS recording_date, u.username AS db_username, m.url, m.spotify_url
        FROM music AS m
        LEFT JOIN users AS u ON m.userid = u.userid
        WHERE m.songno > ?";

if ($sort_genre) {
    $sql .= " AND m.genres = ?";
}

$sql .= " ORDER BY m.songno LIMIT ?, ?";

// Prepare the statement
$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log("Failed to prepare SQL statement: " . $conn->error);
    die("Internal server error. Please try again later.");
}

// Determine the number of parameters
if ($sort_genre) {
    // For genre filtering, bind four parameters: lastId, sort_genre, offset, and limit
    $stmt->bind_param('ssii', $lastId, $sort_genre, $lastId, $results_per_page);
} else {
    // For no genre filtering, bind three parameters: lastId, offset, and limit
    $stmt->bind_param('iii', $lastId, $lastId, $results_per_page);
}

// Execute the statement
$stmt->execute();
$result = $stmt->get_result();
if ($result === false) {
    error_log("Failed to get result set: " . $conn->error);
    die("Internal server error. Please try again later.");
}

// Function to fetch Spotify track data
function fetchSpotifyData($endpoint, $method = 'GET', $body = null) {
    $token = getenv('SPOTIFY_API_TOKEN');
    if (!$token) {
        error_log("Spotify API token is not set.");
        return [];
    }
    $url = "https://api.spotify.com/v1/$endpoint";
    $headers = [
        "Authorization: Bearer $token",
        "Content-Type: application/json"
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

    if ($body) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    }

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($http_code != 200) {
        error_log("Spotify API request failed with status code $http_code: $response");
        error_log("Curl error: $error");
        return [];
    }

    return json_decode($response, true);
}

function searchSpotifyTrack($artist, $track) {
    $query = urlencode($artist . ' ' . $track);
    $endpoint = "search?q=$query&type=track&limit=1";
    $data = fetchSpotifyData($endpoint);

    if (isset($data['tracks']['items'][0]['external_urls']['spotify'])) {
        return $data['tracks']['items'][0]['external_urls']['spotify'];
    } else {
        error_log("Spotify track not found: " . print_r($data, true));
    }
    return '#';
}

// Update Spotify links in the database
while ($row = $result->fetch_assoc()) {
    $spotifyLink = searchSpotifyTrack($row['artist'], $row['song']);

    // Log Spotify link for debugging
    if ($spotifyLink === '#') {
        error_log("Invalid Spotify link for artist: {$row['artist']}, track: {$row['song']}");
    } else {
        // Update the music table with the Spotify link
        $update_sql = "UPDATE music SET spotify_url = ? WHERE songno = ?";
        $update_stmt = $conn->prepare($update_sql);
        if ($update_stmt) {
            $update_stmt->bind_param('si', $spotifyLink, $row['songno']);
            $update_stmt->execute();
            $update_stmt->close();
        } else {
            error_log("Failed to prepare update SQL statement: " . $conn->error);
        }
    }
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
                // Fetch songs again to display updated Spotify links
                $result->data_seek(0); // Reset result pointer to the start
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

            <script>
            function sortByGenre() {
                const genreSelect = document.getElementById("genre-select");
                const selectedGenre = genreSelect.value;
                let url = "?lastId=0";

                if (selectedGenre) {
                    url += `&genre=${encodeURIComponent(selectedGenre)}`;
                }

                window.location.href = url;
            }
            </script>

            <?php
            try {
                $total_sql = "SELECT COUNT(*) AS total FROM music";
                if ($sort_genre) {
                    $total_sql .= " WHERE genres = ?";
                }

                $total_stmt = $conn->prepare($total_sql);
                if (!$total_stmt) {
                    throw new Exception("Failed to prepare SQL statement for pagination: " . $conn->error);
                }

                if ($sort_genre) {
                    $total_stmt->bind_param('s', $sort_genre);
                }

                $total_stmt->execute();
                $total_result = $total_stmt->get_result();
                if ($total_result === false) {
                    throw new Exception("Failed to fetch pagination results: " . $conn->error);
                }

                $total_rows = $total_result->fetch_assoc()['total'];
                $total_pages = ceil($total_rows / $results_per_page);

                echo "<div class='pagination'>";

                // Previous link
                if ($lastId > 0) {
                    $prevId = max(0, $lastId - $results_per_page);
                    $prev_link = "?lastId=" . $prevId;
                    if ($sort_genre) {
                        $prev_link .= "&genre=" . urlencode($sort_genre);
                    }
                    echo "<a href='$prev_link' class='prev'>&lt;&lt; Previous</a> | ";
                }

                // Page numbers
                $range = 2;
                $startId = max(0, $lastId - ($results_per_page * $range));
                $endId = min($total_rows, $startId + ($results_per_page * ($range + 1)));

                for ($i = $startId; $i < $endId; $i += $results_per_page) {
                    if ($i > $startId) {
                        echo " | ";
                    }
                    $link = "?lastId=" . $i;
                    if ($sort_genre) {
                        $link .= "&genre=" . urlencode($sort_genre);
                    }
                    $is_current = ($i == $lastId) ? "current" : "";
                    echo "<a href='$link' class='page-number $is_current'>" . ($i / $results_per_page + 1) . "</a>";
                }

                // Next link
                if ($lastId + $results_per_page < $total_rows) {
                    echo " | ";
                    $next_link = "?lastId=" . ($lastId + $results_per_page);
                    if ($sort_genre) {
                        $next_link .= "&genre=" . urlencode($sort_genre);
                    }
                    echo "<a href='$next_link' class='next'>Next &gt;&gt;</a>";
                }

                echo "</div>";

            } catch (Exception $e) {
                echo "<div class='error-message'>An error occurred while fetching pagination information. Please try again later.</div>";
                error_log($e->getMessage());
            }
            ?>

            <!-- Debugging Information -->
            <?php if ($debug): ?>
            <div class="debugging-info">Debugging Section
                <strong>Debugging Information:</strong><br>
                <?php
                $token = getenv('SPOTIFY_API_TOKEN');
                if ($token) {
                    echo "<strong>Spotify API Token:</strong> " . htmlspecialchars($token, ENT_QUOTES, 'UTF-8');
                } else {
                    echo "<strong>Spotify API Token:</strong> Not set";
                }
                ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$conn->close();
?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleSwitch = document.getElementById('theme-toggle');
    toggleSwitch.addEventListener('change', function () {
        const isChecked = toggleSwitch.checked;
        const theme = isChecked ? 'light' : 'dark';
        document.body.classList.toggle('light-theme', isChecked);
        document.cookie = `theme=${theme}; path=/; samesite=strict`;
    });
});
</script>
</body>
</html>
