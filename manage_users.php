<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "shopizzy";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete user
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $delete_sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('User deleted successfully'); window.location.href='manage_users.php';</script>";
}

// Fetch users
$users = [];

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {
    // Escape the search term to prevent SQL injection
    $search = $conn->real_escape_string($search);
    $sql = "SELECT * FROM users WHERE username LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM users";
}

$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - Admin Panel</title>
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
                    <a href="manage_product.php">Manage Products</a>
                </div>
            </li>
        </ul>
    </nav>
</header>


    <div class="dashboard-container">
        <h2>Manage Users</h2>

        <form method="GET" class="search-form">
    <input type="text" name="search" placeholder="Search by username..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
    <button type="submit">Search</button>
</form>


<table class="dashboard-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($users) > 0): ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                    <a href="?delete=<?= $user['id'] ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" style="text-align: center; padding: 20px; color: #666;">No users found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

    <footer>
        <p>&copy; 2025 ShopIzzy. All rights reserved.</p>
    </footer>
</body>
</html>
