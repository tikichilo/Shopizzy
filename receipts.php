<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>My Receipts - ShopIzzy</title>
  <link rel="stylesheet" href="receipts.css" />
  <link rel="stylesheet" href="main.css" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400&display=swap" rel="stylesheet">
</head>
<body>

<video autoplay muted loop id="bg-video">
  <source src="videos/background vid.mp4" type="video/mp4">
</video>

<header>
  <div class="logo-container">
    <h1><span class="shop">Shop</span><span class="izzy">Izzy</span></h1>
  </div>    
  <nav>
    <ul>
      <li class="dropdown">
        <a href="#">☰</a>
        <div class="dropdown-content">
          <a href="cart.html" class="cart-link">Cart <span class="cart-dot" id="cartDot"></span></a>
          <a href="Home.html">Home</a>
          <a href="logout.php">Logout</a>
        </div>
      </li>
    </ul>
  </nav>
</header>

<div class="content-overlay">
  <h2 class="receipts-title">My Receipts</h2>
  <div class="receipts-container">
    <?php
    include 'config.php';
    session_start();
    $user_id = $_SESSION['user_id'];

    $query = "SELECT * FROM receipts WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $receipt_id = $row['id'];
            echo '<div class="receipt-card">';
            echo '<div class="receipt-header">';
            echo '<h3>' . htmlspecialchars($row['customer_name']) . '</h3>';
            echo '<span class="receipt-date">' . htmlspecialchars($row['date']) . '</span>';
            echo '</div>';
            echo '<div class="receipt-info">';
            echo '<p><strong>Email:</strong> ' . htmlspecialchars($row['email']) . '</p>';
            echo '<p><strong>Phone:</strong> ' . htmlspecialchars($row['phone']) . '</p>';
            echo '<p><strong>Address:</strong> ' . htmlspecialchars($row['address']) . '</p>';
            echo '<p><strong>Payment:</strong> ' . htmlspecialchars($row['payment_method']) . '</p>';
            echo '<p><strong>Details:</strong> ' . htmlspecialchars($row['payment_details']) . '</p>';
            echo '<p><strong>Items:</strong> ' . htmlspecialchars($row['receipt_text']) . '</p>';
            echo '</div>';
            echo '<div class="receipt-footer">';
            echo '<span>Total: <strong>ZMW ' . htmlspecialchars($row['total']) . '</strong></span>';
            echo '<a class="download-btn" href="download_receipt.php?id=' . $receipt_id . '" target="_blank">Download PDF</a>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<p class="no-receipts">You have no receipts yet.</p>';
    }

    $stmt->close();
    $conn->close();
    ?>
  </div>
</div>

<footer>
  <p>&copy; 2025 ShopIzzy. All Rights Reserved.</p>
</footer>

</body>
</html>
