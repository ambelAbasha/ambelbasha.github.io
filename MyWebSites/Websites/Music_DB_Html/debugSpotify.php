<?php
function fetchSpotifyData($endpoint) {
    $token = getenv('SPOTIFY_API_TOKEN');
    if (!$token) {
        die("Spotify API token is not set.");
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

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    echo "HTTP Code: $http_code\n";
    echo "Response: $response\n";
    if ($error) {
        echo "Curl Error: $error\n";
    }
}

fetchSpotifyData('search?q=Firework&type=track&limit=1');
?>
