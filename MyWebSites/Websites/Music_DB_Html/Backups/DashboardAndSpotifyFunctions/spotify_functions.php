<?php
function fetchSpotifyData($endpoint, $method = 'GET', $body = null) {
    $token = getenv('SPOTIFY_API_TOKEN');
    
    // Check if the token is retrieved correctly
    if (!$token) {
        error_log("Spotify API token not found or invalid.");
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
    $query = urlencode("$artist $track");
    $endpoint = "search?q=$query&type=track&limit=1";
    $data = fetchSpotifyData($endpoint);

    // Check if the Spotify API returned any tracks
    if (isset($data['tracks']['items']) && !empty($data['tracks']['items'])) {
        // Log the track info for debugging
        error_log("Found track for $artist - $track: " . print_r($data['tracks']['items'][0], true));

        // Return the Spotify link
        return $data['tracks']['items'][0]['external_urls']['spotify'];
    } else {
        // Log an error if no track was found
        error_log("No Spotify track found for artist: $artist, track: $track");
        return '#'; // Default link if no track is found
    }
}
?>
