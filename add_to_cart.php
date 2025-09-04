<?php
session_start();
header('Content-Type: application/json');

// Log session user_id for debugging
error_log("Session user_id: " . ($_SESSION["user_id"] ?? 'NOT SET'));

// CORS headers for local development
header("Access-Control-Allow-Origin: http://localhost");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "shopizzy";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit();
}

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit();
}
$user_id = $_SESSION["user_id"];

// Get POST data
$data = json_decode(file_get_contents("php://input"), true);
$product_name = $data["product_name"] ?? null;
$price = $data["price"] ?? null;
$quantity = $data["quantity"] ?? 1;
$image = $data["image"] ?? 'default-image.jpg'; // fallback image

// Validate input
if (!$product_name || !$price) {
    echo json_encode(["status" => "error", "message" => "Product name and price are required"]);
    exit();
}

// Log image path
error_log("Product Image: " . $image);

// Check if item already exists
$check_sql = "SELECT id, quantity FROM cart WHERE user_id = ? AND product_name = ?";
$stmt = $conn->prepare($check_sql);
$stmt->bind_param("is", $user_id, $product_name);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Update quantity
    $new_quantity = $row["quantity"] + $quantity;
    $update_sql = "UPDATE cart SET quantity = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ii", $new_quantity, $row["id"]);
    $stmt->execute();
} else {
    // Insert new item
    $insert_sql = "INSERT INTO cart (user_id, product_name, price, quantity, image) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("isdis", $user_id, $product_name, $price, $quantity, $image);
    if (!$stmt->execute()) {
        echo json_encode(["status" => "error", "message" => "Failed to add item to cart: " . $stmt->error]);
        exit();
    }
}

echo json_encode(["status" => "success", "message" => "Item added to cart"]);

$stmt->close();
$conn->close();
?>
