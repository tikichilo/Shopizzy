<?php
require 'db_config.php';

// Query total sales grouped by year using your orders table's created_at column
$sql = "SELECT YEAR(created_at) AS year, SUM(total) AS total_revenue
        FROM orders
        GROUP BY YEAR(created_at)
        ORDER BY year";

$result = mysqli_query($conn, $sql);

$salesByYear = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $salesByYear[] = [
            'year' => $row['year'],
            'total' => (float)$row['total_revenue']
        ];
    }
}

echo json_encode($salesByYear);

mysqli_close($conn);
?>
