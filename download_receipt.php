<?php
// Enable error reporting for debugging (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure the Composer autoload is included
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Include your database connection
include 'config.php';

// Validate receipt ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    exit("Invalid or missing receipt ID.");
}

$receipt_id = intval($_GET['id']);

// Fetch receipt data
$query = "SELECT * FROM receipts WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $receipt_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    exit("Receipt not found.");
}

$row = $result->fetch_assoc();

// Dompdf configuration
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('defaultFont', 'Helvetica');
$options->set('isRemoteEnabled', true); // if you ever want to use external images
$dompdf = new Dompdf($options);

// HTML content for PDF
$html = '
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body { font-family: Helvetica, sans-serif; }
            h2 { text-align: center; color: #660000; }
            hr { margin: 10px 0; }
            p { font-size: 14px; }
        </style>
    </head>
    <body>
        <h2>ShopIzzy Receipt</h2>
        <hr>
        <p><strong>Customer Name:</strong> ' . htmlspecialchars($row['customer_name']) . '</p>
        <p><strong>Email:</strong> ' . htmlspecialchars($row['email']) . '</p>
        <p><strong>Phone:</strong> ' . htmlspecialchars($row['phone']) . '</p>
        <p><strong>Address:</strong> ' . htmlspecialchars($row['address']) . '</p>
        <p><strong>Payment Method:</strong> ' . htmlspecialchars($row['payment_method']) . '</p>
        <p><strong>Payment Details:</strong> ' . htmlspecialchars($row['payment_details']) . '</p>
        <p><strong>Date:</strong> ' . htmlspecialchars($row['date']) . '</p>
        <p><strong>Items:</strong><br>' . nl2br(htmlspecialchars($row['receipt_text'])) . '</p>
        <h3>Total: ZMW ' . htmlspecialchars($row['total']) . '</h3>
    </body>
    </html>
';

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Send PDF to browser
$dompdf->stream("receipt_" . $receipt_id . ".pdf", ["Attachment" => true]);
?>
