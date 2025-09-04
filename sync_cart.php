<?php
session_start();
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "shopizzy";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

if (!isset($_SESSION["user_id"])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION["user_id"];

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data["cart"]) && is_array($data["cart"])) {
    // Clear existing cart items for the user
    $clear_sql = "DELETE FROM cart WHERE user_id = ?";
    $clear_stmt = $conn->prepare($clear_sql);
    $clear_stmt->bind_param("i", $user_id);

    if (!$clear_stmt->execute()) {
        echo json_encode(["status" => "error", "message" => "Failed to clear existing cart: " . $clear_stmt->error]);
        $conn->close();
        exit;
    }
    $clear_stmt->close();

    // Insert new cart items
    foreach ($data["cart"] as $item) {
        $product_name = $item["product_name"];
        $price = $item["price"];
        $quantity = $item["quantity"];

        $insert_sql = "INSERT INTO cart (user_id, product_name, price, quantity) VALUES (?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("isdi", $user_id, $product_name, $price, $quantity);

        if (!$insert_stmt->execute()) {
            echo json_encode(["status" => "error", "message" => "Failed to insert cart item: " . $insert_stmt->error]);
            $conn->close();
            exit;
        }

        $insert_stmt->close();
    }

    echo json_encode(["status" => "success", "message" => "Cart synchronized successfully"]);
} else {
    //if the cart is empty, simply clear the cart.
    $clear_sql = "DELETE FROM cart WHERE user_id = ?";
    $clear_stmt = $conn->prepare($clear_sql);
    $clear_stmt->bind_param("i", $user_id);

    if (!$clear_stmt->execute()) {
        echo json_encode(["status" => "error", "message" => "Failed to clear existing cart: " . $clear_stmt->error]);
        $conn->close();
        exit;
    }
    $clear_stmt->close();
    echo json_encode(["status" => "success", "message" => "Cart synchronized successfully"]);

}

$conn->close();
?>