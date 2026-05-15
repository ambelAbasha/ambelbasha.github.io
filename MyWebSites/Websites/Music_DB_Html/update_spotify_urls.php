<?php
// Include database connection
include('db_connect.php');
if ($conn->connect_error) {
    die("Database connection failed: " . htmlspecialchars($conn->connect_error));
}

// Function to fetch Spotify track data
function fetchSpotifyData($endpoint, $method = 'GET', $body = null) {
    $token = getenv('SPOTIFY_API_TOKEN'); // Ensure this environment variable is set
    if (!$token) {
        error_log("Spotify API token is not set.");
        return false;
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

    // Log detailed information for debugging
    error_log("Request URL: $url");
    error_log("Request Method: $method");
    error_log("Request Headers: " . print_r($headers, true));
    error_log("Request Body: " . json_encode($body));
    error_log("Response: $response");
    error_log("HTTP Code: $http_code");
    if ($error) {
        error_log("Curl Error: $error");
    }

    if ($http_code == 429) {
        error_log("Rate limit exceeded. Retrying...");
        sleep(2); // Retry after 2 seconds
        return fetchSpotifyData($endpoint, $method, $body); // Retry
    } elseif ($http_code == 401) {
        error_log("Spotify API token expired or invalid.");
        return false;
    } elseif ($http_code != 200) {
        error_log("API call failed with HTTP code: $http_code");
        return false;
    }

    return json_decode($response, true);
}

// Function to search for a track on Spotify
function searchSpotifyTrack($artist, $track) {
    $query = urlencode($artist . ' ' . $track);
    $endpoint = "search?q=$query&type=track&limit=1";

    $data = fetchSpotifyData($endpoint);

    // Log detailed search result
    error_log("Search Query: $query");
    error_log("Search Data: " . print_r($data, true));

    if (!$data) {
        error_log("Failed to fetch data from Spotify for artist: $artist, track: $track.");
        return null;
    }

    if (isset($data['tracks']['items'][0]['external_urls']['spotify'])) {
        return $data['tracks']['items'][0]['external_urls']['spotify'];
    } else {
        error_log("Spotify track not found for artist: $artist, track: $track.");
    }
    return null;
}

// Function to sanitize input data
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Update Spotify links in the database
$update_count = 0;
$offset = 0;
$limit = 100;

do {
    $select_sql = "SELECT songno, artist, song FROM music WHERE spotify_url IS NULL OR spotify_url = '' LIMIT $limit OFFSET $offset";
    $result = $conn->query($select_sql);

    if ($result) {
        $rows_processed = 0;
        while ($row = $result->fetch_assoc()) {
            $artist = sanitizeInput($row['artist']);
            $song = sanitizeInput($row['song']);
            $spotifyLink = searchSpotifyTrack($artist, $song);

            if ($spotifyLink) {
                $update_sql = "UPDATE music SET spotify_url = ? WHERE songno = ?";
                $update_stmt = $conn->prepare($update_sql);

                if ($update_stmt) {
                    $update_stmt->bind_param('si', $spotifyLink, $row['songno']);
                    $update_stmt->execute();

                    if ($update_stmt->affected_rows > 0) {
                        $update_count++;
                        error_log("Updated Spotify URL for songno: " . $row['songno'] . " - Link: $spotifyLink");
                    } else {
                        error_log("No rows affected during update for songno: " . $row['songno']);
                    }
                    $update_stmt->close();
                } else {
                    error_log("Failed to prepare update SQL statement: " . $conn->error);
                }
            } else {
                error_log("Spotify link not found for songno: " . $row['songno']);
            }
            $rows_processed++;
        }
        $result->close();

        if ($rows_processed > 0) {
            $offset += $limit;
        }
    } else {
        error_log("Failed to retrieve songs for updating: " . $conn->error);
        break;
    }
} while ($rows_processed > 0);

$conn->close();
echo "Updated $update_count Spotify URLs.";
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