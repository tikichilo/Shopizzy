<?php
// get_product_details.php - Fetches complete product details
header('Content-Type: application/json');

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

// Validate and sanitize inputs
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$category = isset($_GET['category']) ? $_GET['category'] : '';

// List of allowed tables for security
$allowedCategories = ['phones', 'electronics', 'clothes', 'others'];

if (!$productId || !in_array($category, $allowedCategories)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid product ID or category']);
    exit;
}

// Fetch product data
$sql = "SELECT * FROM `$category` WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($product = $result->fetch_assoc()) {
    // Define default keys expected in interactions
    $expectedKeys = [
        'size' => null,
        'color' => null,
        'material' => null,
        'brand' => null,
        'specs' => null,
        'warranty' => null,
        'description' => null,
        'custom_category' => null
    ];

    // Merge missing keys with null defaults
    foreach ($expectedKeys as $key => $default) {
        if (!array_key_exists($key, $product)) {
            $product[$key] = $default;
        }
    }

    echo json_encode($product);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Product not found']);
}

$stmt->close();
$conn->close();
?>
