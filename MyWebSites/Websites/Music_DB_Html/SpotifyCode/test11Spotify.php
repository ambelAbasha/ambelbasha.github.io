<?php
$token = getenv('SPOTIFY_API_TOKEN');
echo $token ? "Token is set: " . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') : "Token is not set";
?>
