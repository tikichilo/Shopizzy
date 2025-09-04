<?php
require_once 'config.php'; // DB_HOST, DB_USER, DB_PASS, DB_NAME
header('Content-Type: application/json');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit;
}

// Initialize defaults
$keyword = '';
$category = '';
$minPrice = null;
$maxPrice = null;
$sort = 'newest';
$page = 1;
$limit = 10;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    $json = json_decode(file_get_contents('php://input'), true);
    $keyword = strtolower(trim($json['message'] ?? ''));
    $category = strtolower(trim($json['category'] ?? ''));
    $minPrice = isset($json['min_price']) ? (float)$json['min_price'] : null;
    $maxPrice = isset($json['max_price']) ? (float)$json['max_price'] : null;
    $sort = $json['sort'] ?? 'newest';
    $page = isset($json['page']) ? (int)$json['page'] : 1;
} else {
    $keyword = strtolower(trim($_GET['keyword'] ?? ''));
    $category = strtolower(trim($_GET['category'] ?? ''));
    $minPrice = isset($_GET['min_price']) ? (float)$_GET['min_price'] : null;
    $maxPrice = isset($_GET['max_price']) ? (float)$_GET['max_price'] : null;
    $sort = $_GET['sort'] ?? 'newest';
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
}

$tables = ['phones', 'electronics', 'clothes', 'others'];
if ($category && !in_array($category, $tables)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid category']);
    exit;
}

$offset = ($page - 1) * $limit;
$response = [
    'status' => 'success',
    'products' => [],
    'total_results' => 0
];

$searchTables = $category ? [$category] : $tables;
$allProducts = [];

foreach ($searchTables as $table) {
    $conditions = ["1=1"];
    $params = [];
    $types = "";

    // Keyword filters based on table
    if (!empty($keyword)) {
        switch ($table) {
            case 'phones':
            case 'electronics':
                $conditions[] = "(name LIKE ? OR brand LIKE ?)";
                $params[] = "%$keyword%";
                $params[] = "%$keyword%";
                $types .= "ss";
                break;
            case 'clothes':
                $conditions[] = "(name LIKE ? OR color LIKE ? OR material LIKE ?)";
                $params[] = "%$keyword%";
                $params[] = "%$keyword%";
                $params[] = "%$keyword%";
                $types .= "sss";
                break;
            case 'others':
                $conditions[] = "(name LIKE ? OR description LIKE ?)";
                $params[] = "%$keyword%";
                $params[] = "%$keyword%";
                $types .= "ss";
                break;
        }
    }

    // Price filters
    if (!is_null($minPrice)) {
        $conditions[] = "price >= ?";
        $params[] = $minPrice;
        $types .= "d";
    }
    if (!is_null($maxPrice)) {
        $conditions[] = "price <= ?";
        $params[] = $maxPrice;
        $types .= "d";
    }

    $whereClause = implode(" AND ", $conditions);
    $sql = "SELECT *, '$table' as category FROM $table WHERE $whereClause";

    // Sorting
    switch ($sort) {
        case 'price_asc':
            $sql .= " ORDER BY price ASC";
            break;
        case 'price_desc':
            $sql .= " ORDER BY price DESC";
            break;
        default:
            $sql .= " ORDER BY id DESC";
            break;
    }

    // Run query without limit to paginate later
    $stmt = $conn->prepare($sql);
    if (!$stmt) continue;
    if (!empty($params)) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $product = [
            'id' => (int)$row['id'],
            'name' => htmlspecialchars($row['name']),
            'price' => (float)$row['price'],
            'image' => htmlspecialchars($row['image']),
            'category' => $row['category']
        ];

        // Add fields based on table
        if ($table === 'phones' || $table === 'electronics') {
            $product['brand'] = $row['brand'] ?? null;
            if ($table === 'electronics') $product['warranty'] = $row['warranty'] ?? null;
            if ($table === 'phones') $product['specs'] = $row['specs'] ?? null;
        } elseif ($table === 'clothes') {
            $product['size'] = $row['size'] ?? null;
            $product['color'] = $row['color'] ?? null;
            $product['material'] = $row['material'] ?? null;
        } elseif ($table === 'others') {
            $product['description'] = $row['description'] ?? null;
            $product['custom_category'] = $row['custom_category'] ?? null;
        }

        $allProducts[] = $product;
    }

    $stmt->close();
}

// Total results before pagination
$response['total_results'] = count($allProducts);

// Paginate after merging
$paginated = array_slice($allProducts, $offset, $limit);
$response['products'] = $paginated;

if (empty($response['products'])) {
    $response['status'] = 'error';
    $response['message'] = 'No products found.';
}

echo json_encode($response);
$conn->close();
?>
