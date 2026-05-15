<?php
function searchSpotifyTrack($artist, $track) {
    $query = urlencode($artist . ' ' . $track);
    $endpoint = "search?q=$query&type=track&limit=1";
    $token = getenv('SPOTIFY_API_TOKEN'); // Make sure this is set properly
    $url = "https://api.spotify.com/v1/$endpoint";

    $headers = [
        "Authorization: Bearer $token",
        "Content-Type: application/json"
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    return isset($data['tracks']['items'][0]['external_urls']['spotify']) ? $data['tracks']['items'][0]['external_urls']['spotify'] : '#';
}

// Example call to searchSpotifyTrack
$spotifyLink = searchSpotifyTrack('Artist Name', 'Track Name');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Test Spotify Link</title>
</head>
<body>
    <table>
        <tr>
            <td>Song</td>
            <td><a href="<?php echo htmlspecialchars($spotifyLink, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">Listen on Spotify</a></td>
        </tr>
    </table>
</body>
</html>
