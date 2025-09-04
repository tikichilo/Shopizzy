<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product - ShopIzzy</title>
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
                    <a href="admin.php">Dashboard</a>
                    <a href="manage_product.php">Manage Products</a>
                    <a href="manage_users.php">Manage Users</a>
                </div>
            </li>
        </ul>
    </nav>
</header>

<div class="add-product-section">
    <h2>Add New Product</h2>
    <form id="productForm" action="insert_product.php" method="POST" enctype="multipart/form-data" class="add-product-form">
        <label for="category">Category:</label>
        <select name="category" id="category" required onchange="showFields()">
            <option value="">Select Category</option>
            <option value="phones">Phones</option>
            <option value="clothes">Clothes</option>
            <option value="electronics">Electronics</option>
            <option value="others">Others</option>
        </select>

        <div id="commonFields">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="price">Price:</label>
            <input type="number" name="price" id="price" step="0.01" required>

            <label for="imageLink">Image Link (URL):</label>
            <input type="text" name="imageLink" id="imageLink" placeholder="Enter the image URL" required>
        </div>

        <div id="phonesFields" class="category-fields" style="display:none;">
            <label for="brand_phone">Phone Brand:</label>
            <input type="text" name="brand_phone" id="brand_phone">

            <label for="specs">Phone Specs:</label>
            <textarea name="specs" id="specs"></textarea>
        </div>

        <div id="clothesFields" class="category-fields" style="display:none;">
            <label for="size">Size:</label>
            <input type="text" name="size" id="size">

            <label for="color">Color:</label>
            <input type="text" name="color" id="color">

            <label for="material">Material:</label>
            <input type="text" name="material" id="material">
        </div>

        <div id="electronicsFields" class="category-fields" style="display:none;">
            <label for="brand_elec">Electronics Brand:</label>
            <input type="text" name="brand_elec" id="brand_elec">

            <label for="warranty">Warranty:</label>
            <input type="text" name="warranty" id="warranty">
        </div>

        <!-- New "Others" Category Fields -->
        <div id="othersFields" class="category-fields" style="display:none;">
            <label for="description">Description:</label>
            <textarea name="description" id="description" placeholder="Product description..."></textarea>

            <label for="custom_category">Custom Category:</label>
            <input type="text" name="custom_category" id="custom_category" placeholder="e.g., Handmade, Decor">
        </div>

        <button type="submit">Add Product</button>
    </form>
</div>

<footer>
    <p>&copy; 2025 ShopIzzy. All rights reserved.</p>
</footer>

<script>
    function showFields() {
        const selected = document.getElementById("category").value;
        const categories = ["phones", "clothes", "electronics", "others"];
        categories.forEach(cat => {
            document.getElementById(cat + "Fields").style.display = (selected === cat) ? "block" : "none";
        });
    }
</script>
</body>
</html>
