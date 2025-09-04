<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Invalid request method.";
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "User not logged in.";
    exit;
}

// Get data from form
$user_id = $_SESSION['user_id'];
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : null;
$category = $_POST['category'] ?? null;
$action = $_POST['action'] ?? null;
$size = $_POST['size'] ?? null;  // New
$color = $_POST['color'] ?? null;  // New
$material = $_POST['material'] ?? null;  // New
$brand = $_POST['brand'] ?? null;  // New
$specs = $_POST['specs'] ?? null;  // New
$warranty = $_POST['warranty'] ?? null;  // New
$timestamp = date('Y-m-d H:i:s');

// Validate required fields
if (!$category || !$action) {
    echo "Missing required data.";
    exit;
}

// Database connection
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'shopizzy';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    echo "Database connection failed: " . $conn->connect_error;
    exit;
}

// Insert interaction
$stmt = $conn->prepare("INSERT INTO user_interactions (user_id, product_id, category, action, size, color, material, brand, specs, warranty, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iisssssssss", $user_id, $product_id, $category, $action, $size, $color, $material, $brand, $specs, $warranty, $timestamp);

if ($stmt->execute()) {
    echo "OK";
} else {
    echo "DB insert failed: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
