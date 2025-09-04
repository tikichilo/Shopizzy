<?php
require 'db_config.php'; // Your database connection

// Step 1: Get top 5 product IDs based on number of interactions
$sql = "
    SELECT product_id, COUNT(*) as interaction_count
    FROM user_interactions
    GROUP BY product_id
    ORDER BY interaction_count DESC
    LIMIT 5
";
$result = $conn->query($sql);

$topProducts = [];

// ✅ Include 'others' as one of the valid product tables
$tables = ['phones', 'clothes', 'electronics', 'others'];

while ($row = $result->fetch_assoc()) {
    $productId = $row['product_id'];
    $interactionCount = $row['interaction_count'];

    foreach ($tables as $table) {
        $stmt = $conn->prepare("SELECT id, name, price, image FROM `$table` WHERE id = ?");
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $productResult = $stmt->get_result();

        if ($productResult->num_rows > 0) {
            $product = $productResult->fetch_assoc();
            $product['category'] = ucfirst($table); // e.g., "Others"
            $product['interactions'] = $interactionCount;
            $topProducts[] = $product;
            break; // Stop checking once product is found
        }

        $stmt->close();
    }
}

echo json_encode([
    'status' => 'success',
    'products' => $topProducts
]);
?>
