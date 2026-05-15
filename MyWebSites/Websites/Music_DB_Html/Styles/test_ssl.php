<?php
$url = "https://repo.packagist.org/packages.json";

// Create a stream context with SSL verification disabled
$context = stream_context_create([
    "ssl" => [
        "verify_peer" => false,
        "verify_peer_name" => false,
    ],
]);

$result = @file_get_contents($url, false, $context);
if ($result === FALSE) {
    echo "Failed to access URL.\n";
} else {
    echo "Successfully accessed URL.\n";
}
?>
