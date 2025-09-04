<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - ShopIzzy</title>
    <link rel="stylesheet" href="admin.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Your custom JS (defer to ensure DOM is ready before it runs) -->
    <script src="admincharts.js" defer></script>
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
                    <a href="add_product.php">Add Product</a>
                    <a href="manage_product.php">Manage Products</a>
                    <a href="manage_users.php">Manage Users</a>
                    <a href="admin_receipts.php">Receipts</a>
                    <a href="admin logout.php">Logout</a>
                </div>
            </li>
        </ul>
    </nav>
</header>

<section class="admin-section">
    <h2>Top 5 AI Recommended Products</h2>
    <div class="product-cards-container">
        <!-- Dynamic product cards will go here -->
        <!-- Example card -->
        <!--
        <div class="product-card">
            <img src="images/product1.jpg" alt="Product Image">
            <h4>Smartphone X</h4>
            <p>Category: Phones</p>
            <p>Interactions: 243</p>
        </div>
        -->
    </div>
</section>

<section class="chart-container">
  <h2>Dashboard Overview</h2>
  <div class="chart-row">
    <div class="chart-wrapper">
      <h3 class="chart-title">Total Sales by Year</h3>
      <canvas id="totalSalesChart"></canvas>
    </div>
    <div class="chart-wrapper">
      <h3 class="chart-title">Total Users</h3>
      <canvas id="totalUsersChart"></canvas>
    </div>
    <div class="chart-wrapper">
      <h3 class="chart-title">Abandoned Carts</h3>
      <canvas id="abandonedCartChart"></canvas>
    </div>
  </div>
</section>




<footer>
    <p>&copy; 2025 ShopIzzy Admin Panel</p>
</footer>

</body>
</html>
