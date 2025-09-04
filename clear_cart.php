<?php
session_start();
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "shopizzy";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

if (!isset($_SESSION["user_id"]) || !is_numeric($_SESSION["user_id"])) {
    echo json_encode(["status" => "error", "message" => "User not logged in or invalid session"]);
    exit;
}

$user_id = intval($_SESSION["user_id"]);

$delete_sql = "DELETE FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($delete_sql);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Cart cleared successfully!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to clear cart: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
