<?php
session_start();

// Get POST data
$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

// Store in PHP session
if (is_array($data)) {
    foreach ($data as $key => $value) {
        $_SESSION[$key] = $value;
    }

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
}
?>
