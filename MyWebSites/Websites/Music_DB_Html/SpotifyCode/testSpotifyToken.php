<?php
// Load environment variables (if using a library like vlucas/phpdotenv, include it here)
// require_once 'vendor/autoload.php';
// (new Dotenv\Dotenv(__DIR__))->load();

// Retrieve the Spotify API token from the environment variable
$token = getenv('SPOTIFY_API_TOKEN');

// Check if the token is retrieved successfully
if ($token) {
    echo "Spotify API Token is successfully retrieved: " . htmlspecialchars($token, ENT_QUOTES, 'UTF-8');
} else {
    echo "Spotify API Token is not set or cannot be retrieved.";
}
?>
