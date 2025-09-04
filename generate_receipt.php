<?php
require 'vendor/autoload.php';

use Dompdf\Dompdf;

session_start();
if (!isset($_SESSION["user_id"])) {
    die("Not authorized");
}

$user_id = $_SESSION["user_id"];

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "shopizzy";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the latest sale for this user
$sale_sql = "SELECT id, total_price, created_at FROM sales WHERE user_id = ? ORDER BY created_at DESC LIMIT 1";
$stmt = $conn->prepare($sale_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$sale_result = $stmt->get_result();
$sale = $sale_result->fetch_assoc();

if (!$sale) {
    die("No sales found.");
}

$sale_id = $sale['id'];
$total_price = $sale['total_price'];
$date = $sale['created_at'];
$stmt->close();

// Get user's name
$name_sql = "SELECT name FROM users WHERE id = ?";
$name_stmt = $conn->prepare($name_sql);
$name_stmt->bind_param("i", $user_id);
$name_stmt->execute();
$name_result = $name_stmt->get_result();
$name_data = $name_result->fetch_assoc();
$name = $name_data ? $name_data['name'] : "Unknown User";
$name_stmt->close();

// Placeholder item table row (replace with actual order items if needed)
$items_html = "<tr>
    <td>Sample Product</td>
    <td>1</td>
    <td>ZMW 50.00</td>
    <td>ZMW 50.00</td>
</tr>";

// HTML content
$html = "
<!DOCTYPE html>
<html>
<head>
  <style>
    body { font-family: DejaVu Sans, sans-serif; }
    .receipt-container { max-width: 600px; margin: auto; padding: 20px; }
    h2 { text-align: center; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #aaa; padding: 10px; text-align: left; }
    .total { text-align: right; font-weight: bold; }
    .footer { margin-top: 40px; font-size: 0.9em; text-align: center; color: #555; }
  </style>
</head>
<body>
  <div class='receipt-container'>
    <h2>ShopIzzy Receipt</h2>
    <p><strong>Receipt #: </strong> $sale_id</p>
    <p><strong>Name:</strong> $name</p>
    <p><strong>Date:</strong> $date</p>
    <table>
      <tr>
        <th>Product</th>
        <th>Quantity</th>
        <th>Unit Price</th>
        <th>Subtotal</th>
      </tr>
      $items_html
    </table>
    <p class='total'>Grand Total: ZMW " . number_format($total_price, 2) . "</p>
    <div class='footer'>Thank you for shopping with ShopIzzy!</div>
  </div>
</body>
</html>
";

// Generate PDF using Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Stream PDF to browser for download
$dompdf->stream("ShopIzzy_Receipt_$sale_id.pdf", ["Attachment" => 1]);
?>
