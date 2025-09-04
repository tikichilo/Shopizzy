<?php
session_start();
include 'config.php'; // Make sure this sets up $conn properly

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate session
    if (!isset($_SESSION["user_id"]) || !is_numeric($_SESSION["user_id"])) {
        header("Location: checkout.html?error=not_logged_in");
        exit();
    }

    $user_id = intval($_SESSION["user_id"]);

    // Sanitize and fetch form inputs
    $customer_name = trim($_POST['full_name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $payment_method = trim($_POST['payment_method'] ?? '');
    $cart_json = $_POST['cart_data'] ?? '[]';
    $cart = json_decode($cart_json, true);

    // Basic validations
    if (!$customer_name || !$address || !$email || !$phone || !$payment_method) {
        header("Location: checkout.html?error=missing_fields");
        exit();
    }

    if (!is_array($cart) || empty($cart)) {
        header("Location: checkout.html?error=empty_cart");
        exit();
    }

    // Process payment details
    $payment_details = '';
    if ($payment_method === "Bank") {
        $card_holder = trim($_POST['card_holder'] ?? '');
        $card_number = trim($_POST['card_number'] ?? '');
        $expiry_date = trim($_POST['expiry_date'] ?? '');
        $cvv = trim($_POST['cvv'] ?? '');

        if (!$card_holder || !$card_number || !$expiry_date || !$cvv) {
            header("Location: checkout.html?error=bank_fields_missing");
            exit();
        }

        $payment_details = "Card Holder: $card_holder, Card Number: $card_number, Expiry: $expiry_date";
    } elseif ($payment_method === "Mobile Money") {
        $mobile_holder = trim($_POST['mobile_holder'] ?? '');
        $mobile_number = trim($_POST['mobile_number'] ?? '');
        $provider = trim($_POST['provider'] ?? '');

        if (!$mobile_holder || !$mobile_number || !$provider) {
            header("Location: checkout.html?error=mobile_fields_missing");
            exit();
        }

        $payment_details = "Mobile Holder: $mobile_holder, Number: $mobile_number, Provider: $provider";
    } else {
        header("Location: checkout.html?error=invalid_payment");
        exit();
    }

    // Process receipt text
    $total = 0;
    $receipt_lines = [];

    foreach ($cart as $item) {
        $name = htmlspecialchars($item['product_name'] ?? 'Unknown');
        $qty = (int)($item['quantity'] ?? 0);
        $price = (float)($item['price'] ?? 0);
        $line_total = $qty * $price;
        $total += $line_total;
        $receipt_lines[] = "$name x$qty = ZMW " . number_format($line_total, 2);
    }

    $receipt_text = implode("\n", $receipt_lines);
    $date = date('Y-m-d H:i:s');

    // Insert into receipts table
    $stmt = $conn->prepare("INSERT INTO receipts (user_id, customer_name, address, email, phone, payment_method, payment_details, total, date, receipt_text) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        header("Location: checkout.html?error=stmt_failed");
        exit();
    }

    // Also insert into orders table
$order_stmt = $conn->prepare("INSERT INTO orders (user_id, name, address, payment_method, payment_number, total, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
if ($order_stmt) {
    $order_stmt->bind_param("issssds", $user_id, $customer_name, $address, $payment_method, $payment_details, $total, $date);
    $order_stmt->execute();
    $order_stmt->close();
}


    $stmt->bind_param("isssssssss", $user_id, $customer_name, $address, $email, $phone, $payment_method, $payment_details, $total, $date, $receipt_text);
    
    

    if ($stmt->execute()) {
        // Clear user's cart
        $delete_stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $delete_stmt->bind_param("i", $user_id);
        $delete_stmt->execute();
        $delete_stmt->close();

        $stmt->close();
        $conn->close();

        // Redirect with success
        header("Location: home.html?success=1");
        exit();
    } else {
        $stmt->close();
        $conn->close();
        header("Location: checkout.html?error=insert_failed");
        exit();
    }

} else {
    header("Location: checkout.html?error=invalid_request");
    exit();
}
?>
