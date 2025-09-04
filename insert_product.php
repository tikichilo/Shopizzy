<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "shopizzy";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get common fields
$category = $_POST['category'];
$name = $_POST['name'];
$price = $_POST['price'];
$imageLink = $_POST['imageLink'];

switch ($category) {
    case "phones":
        $brand = $_POST['brand_phone'];
        $specs = $_POST['specs'];
        $sql = "INSERT INTO phones (name, price, image, brand, specs) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdsss", $name, $price, $imageLink, $brand, $specs);
        break;

    case "clothes":
        $size = $_POST['size'];
        $color = $_POST['color'];
        $material = $_POST['material'];
        $sql = "INSERT INTO clothes (name, price, image, size, color, material) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdssss", $name, $price, $imageLink, $size, $color, $material);
        break;

    case "electronics":
        $brand = $_POST['brand_elec'];
        $warranty = $_POST['warranty'];
        $sql = "INSERT INTO electronics (name, price, image, brand, warranty) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdsss", $name, $price, $imageLink, $brand, $warranty);
        break;

    case "others":
        $description = $_POST['description'] ?? '';
        $custom_category = $_POST['custom_category'] ?? '';
        $sql = "INSERT INTO others (name, price, image, description, category) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdsss", $name, $price, $imageLink, $description, $custom_category);
        break;

    default:
        die("Invalid category selected.");
}

if ($stmt->execute()) {
    echo "<script>alert('Product added successfully!'); window.location.href='add_product.php';</script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
