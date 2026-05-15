<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['theme'])) {
        setcookie('theme', $data['theme'], time() + (10 * 365 * 24 * 60 * 60), "/"); // 10 years
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Theme not set']);
    }
}
?>
