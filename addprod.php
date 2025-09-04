<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

// Database credentials
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "shopizzy";

// Create database connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

$products = [];
$tables = ['phones', 'clothes', 'electronics', 'others'];

foreach ($tables as $table) {
    // Only select fields relevant to the front-end (adjust per table)
    $sql = "SELECT *, '$table' AS category FROM $table";
    $result = $conn->query($sql);

    if (!$result) {
        echo json_encode(["error" => "Query failed for table: $table - " . $conn->error]);
        exit;
    }

    while ($row = $result->fetch_assoc()) {
        // Ensure 'tag' is always set and clean
        $row['tag'] = isset($row['tag']) ? trim($row['tag']) : "";
        
        // Optional: Clean other fields that might have whitespace
        if (isset($row['name'])) {
            $row['name'] = trim($row['name']);
        }
        if (isset($row['description'])) {
            $row['description'] = trim($row['description']);
        }

        $products[] = $row;
    }
}

// Shuffle the combined product list to ensure variety
shuffle($products);

// Return products wrapped in "products" key
echo json_encode([
    "products" => $products,
    "total_count" => count($products)
], JSON_PRETTY_PRINT);

$conn->close();
?>