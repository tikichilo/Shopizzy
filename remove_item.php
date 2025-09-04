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

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION["user_id"];

// Get item ID from POST request
$data = json_decode(file_get_contents("php://input"));
$item_id = $data->id;

// Delete item from cart
$sql = "DELETE FROM cart WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $item_id, $user_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Item removed successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to remove item"]);
}

$stmt->close();
$conn->close();
?>
