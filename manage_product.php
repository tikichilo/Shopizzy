<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "shopizzy";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$tables = ['phones', 'clothes', 'electronics', 'others'];

// Handle delete
if (isset($_GET['delete']) && isset($_GET['category'])) {
    $id = intval($_GET['delete']);
    $category = $_GET['category'];

    if (in_array($category, $tables)) {
        $stmt = $conn->prepare("DELETE FROM `$category` WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                echo "<script>alert('Product deleted successfully.'); window.location.href='manage_product.php';</script>";
                exit;
            } else {
                echo "<script>alert('Failed to delete product.'); window.location.href='manage_product.php';</script>";
            }
            $stmt->close();
        }
    } else {
        echo "<script>alert('Invalid product category.'); window.location.href='manage_product.php';</script>";
    }
}

// Fetch products
$products = [];
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

foreach ($tables as $table) {
    if ($search !== '') {
        $stmt = $conn->prepare("SELECT *, '$table' AS category FROM `$table` WHERE name LIKE ?");
        $likeSearch = "%$search%";
        $stmt->bind_param("s", $likeSearch);
    } else {
        $stmt = $conn->prepare("SELECT *, '$table' AS category FROM `$table`");
    }

    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products - Admin Panel</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<header>
    <div class="logo-container">
        <h1><span class="shop">Shop</span><span class="izzy">Izzy</span></h1>
    </div> 
    <nav>
        <ul>
            <li class="dropdown">
                <a href="#">☰</a>
                <div class="dropdown-content">
                    <a href="admin.php"> Dashboard</a>
                    <a href="add_product.php">Add Product</a>
                    <a href="manage_users.php">Manage Users</a>
                </div>
            </li>
        </ul>
    </nav>
</header>

<h1 class="manage-products-title">Manage Products</h1>

<form method="GET" class="search-form">
    <input type="text" name="search" placeholder="Search by product name..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Search</button>
</form>

<table class="manage-products-table">
    <tr>
        <th>Image</th>
        <th>Name</th>
        <th>Price</th>
        <th>Category</th>
        <th>Tag</th>
        <th>Actions</th>
    </tr>
    <?php if (count($products) > 0): ?>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><img src="<?= htmlspecialchars($product['image']) ?>" alt="Product" width="60"></td>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td>ZMW <?= number_format($product['price'], 2) ?></td>
                <td><?= htmlspecialchars($product['category']) ?></td>
                <td>
                    <form method="POST" action="update_tag.php" class="tag-form">
                        <input type="hidden" name="id" value="<?= $product['id'] ?>">
                        <input type="hidden" name="table" value="<?= $product['category'] ?>">
                        <input type="text" name="tag" value="<?= htmlspecialchars($product['tag'] ?? '') ?>" placeholder="e.g. Only 2 left!">
                        <button type="submit" name="save">Save</button>
                        <button type="submit" name="clear" value="1" style="background-color:#ccc;">Clear</button>
                    </form>
                </td>
                <td>
                    <a href="?delete=<?= $product['id'] ?>&category=<?= $product['category'] ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="6">No products found.</td></tr>
    <?php endif; ?>
</table>

<footer>
    <p>&copy; 2025 ShopIzzy. All rights reserved.</p>
</footer>

</body>
</html>
