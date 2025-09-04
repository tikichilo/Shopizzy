<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "shopizzy"; 
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed."]);
    exit;
}

$searchQuery = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';

if (empty($searchQuery)) {
    echo json_encode(["status" => "error", "message" => "No search query provided."]);
    exit;
}

$products = [];
$tables = ['phones', 'clothes', 'electronics', 'others'];
$searchPattern = "%$searchQuery%";

foreach ($tables as $table) {
    // Adjust columns to search depending on table
    switch ($table) {
        case 'phones':
            $sql = "SELECT id, name, price, image FROM $table WHERE name LIKE ? OR brand LIKE ?";
            break;
        case 'clothes':
            $sql = "SELECT id, name, price, image FROM $table WHERE name LIKE ? OR color LIKE ? OR material LIKE ?";
            break;
        case 'electronics':
            $sql = "SELECT id, name, price, image FROM $table WHERE name LIKE ? OR brand LIKE ?";
            break;
        case 'others':
            $sql = "SELECT id, name, price, image FROM $table WHERE name LIKE ? OR description LIKE ?";
            break;
        default:
            continue;
    }

    $stmt = $conn->prepare($sql);
    
    if ($table === 'clothes') {
        $stmt->bind_param("sss", $searchPattern, $searchPattern, $searchPattern);
    } else {
        $stmt->bind_param("ss", $searchPattern, $searchPattern);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $products[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'price' => '$' . round($row['price'] / 25, 2), // Adjust this rate as needed
            'image' => $row['image'],
            'category' => $table
        ];
    }

    $stmt->close();
}

echo json_encode(["status" => "success", "products" => $products]);
$conn->close();
?>
