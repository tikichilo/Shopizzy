<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  http_response_code(401);
  echo json_encode(['error' => 'Not logged in']);
  exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['productId'], $data['category'], $data['action'])) {
  http_response_code(400);
  echo json_encode(['error' => 'Invalid data']);
  exit;
}

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'shopizzy';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
  http_response_code(500);
  echo json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]);
  exit;
}

$user_id = $_SESSION['user_id'];
$product_id = intval($data['productId']);
$category = $data['category'];
$action = $data['action'];
$size = $data['size'] ?? null;
$color = $data['color'] ?? null;
$material = $data['material'] ?? null;
$brand = $data['brand'] ?? null;
$specs = $data['specs'] ?? null;
$warranty = $data['warranty'] ?? null;
$timestamp = date('Y-m-d H:i:s');

// Prepare and execute insert
$stmt = $conn->prepare("
  INSERT INTO user_interactions 
    (user_id, product_id, category, action, size, color, material, brand, specs, warranty, timestamp) 
  VALUES 
    (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
  "iisssssssss", 
  $user_id, $product_id, $category, $action, 
  $size, $color, $material, $brand, $specs, $warranty, $timestamp
);

if ($stmt->execute()) {
  echo json_encode(['success' => true]);
} else {
  http_response_code(500);
  echo json_encode(['error' => 'DB insert failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
