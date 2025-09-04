<?php
require 'db_config.php';

$action = $_POST['action'] ?? null;
$user_id = $_POST['user_id'] ?? null; // Pass this from JS if logged in

if ($action && in_array($action, ['abandoned', 'recovered'])) {
    $stmt = $conn->prepare("INSERT INTO cart_tracking (user_id, action) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $action);
    $stmt->execute();
    echo "Recorded";
} else {
    http_response_code(400);
    echo "Invalid action.";
}

$conn->close();
?>
