<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin - All Receipts | ShopIzzy</title>
  <link rel="stylesheet" href="admin_receipts.css" />
  <link rel="stylesheet" href="admin.css" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400&display=swap" rel="stylesheet">
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
          <a href="manage_users.php">Manage Users</a>
          <a href="logout.php">Logout</a>
        </div>
      </li>
    </ul>
  </nav>
</header>

<div class="admin-content">
  <h2 class="receipts-title">All Receipts</h2>

  <form method="GET" class="search-form">
    <input type="text" name="search" placeholder="Search by name or email..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
    <button type="submit">Search</button>
  </form>

  <div class="receipts-container">
    <?php
    include 'config.php';

    $search = $_GET['search'] ?? '';
    $searchTerm = "%$search%";

    if (!empty($search)) {
        $query = "SELECT * FROM receipts WHERE customer_name LIKE ? OR email LIKE ? ORDER BY date DESC";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
    } else {
        $query = "SELECT * FROM receipts ORDER BY date DESC";
        $stmt = $conn->prepare($query);
    }

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
        echo '<p class="no-receipts">No receipts found.</p>';
    }

    $stmt->close();
    $conn->close();
    ?>
  </div>
</div>

<footer>
  <p>&copy; 2025 ShopIzzy Admin. All Rights Reserved.</p>
</footer>

</body>
</html>
