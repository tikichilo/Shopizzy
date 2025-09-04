<?php
session_start();
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Optional: Debug session
// error_log("Session user_id: " . ($_SESSION['user_id'] ?? 'not set'));

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "shopizzy";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Check user session
if (!isset($_SESSION["user_id"]) || !is_numeric($_SESSION["user_id"])) {
    echo json_encode(["status" => "error", "message" => "User not logged in or invalid session"]);
    exit;
}

$user_id = intval($_SESSION["user_id"]);

$sql = "SELECT id, product_name, price, quantity, image FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);

if (!$stmt->execute()) {
    echo json_encode(["status" => "error", "message" => "Query execution failed: " . $stmt->error]);
    exit;
}

$result = $stmt->get_result();
$cart_items = [];

while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
}

echo json_encode(["status" => "success", "items" => $cart_items]);

$stmt->close();
$conn->close();
?>
