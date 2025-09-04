<?php
session_start();
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Adjust for production
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "shopizzy";

// Assuming you receive product data via POST
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Database connection (replace with your database credentials)
    $conn = new mysqli("your_host", "your_user", "your_password", "your_database");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert the item into the cart table
    $sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, $quantity)";

    if ($conn->query($sql) === TRUE) {
        echo "Item added to cart"; // Success response
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error; // Database error
    }

    $conn->close();
} else {
    // User is not logged in
    http_response_code(401); // Unauthorized status code
    echo "You must be logged in to add items to cart!";
}
?>