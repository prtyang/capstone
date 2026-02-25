<?php include "../../config/db.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Dashboard</title>
  <link rel="stylesheet" href="../CSS/to-ship-completed.css">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>

<div class="container">

<!-- SIDEBAR -->
<aside class="sidebar">
  <div class="logo">
    <img src="../PICTURE/logo.png">
  </div>

  <nav class="menu">
    <a href="dashboard.html">
      <img src="../PICTURE/home logo.png" class="menu-icon">
      Dashboard
    </a>

    <a href="product.php">
      <img src="../PICTURE/product logo.png" class="menu-icon">
      Product
    </a>

    <a href="order.php" class="active">
      <img src="../PICTURE/ORDER LOGO.webp" class="menu-icon">
      Order
    </a>

    <a href="sales.html">
      <img src="../PICTURE/SALES LOGO.png" class="menu-icon">
      Sales
    </a>

    <a href="marketing.html">
      <img src="../PICTURE/MARKETING LOGO.png" class="menu-icon">
      Marketing
    </a>

    <a href="account.php">
      <img src="../PICTURE/ACCOUNT LOGO.png" class="menu-icon">
      Account
    </a>
  </nav>

  <div class="logout-bar">
    <div class="logout-content">
      <span class="logout-text">LOG OUT</span>
    </div>
  </div>
</aside>

<!-- MAIN -->
<main class="main">

<!-- Tabs -->
<div class="tabs">
  <a href="order.php">All</a>
  <a href="order-to-ship.php" class="active">To Ship <small>200</small></a>
  <a href="order-shipping.php">Shipping <small>200</small></a>
  <a href="order-completed.php">Completed</a>
  <a href="order-cancel.php">Cancel</a>
  <a href="order-return/refund.php">Return/Refund</a>
</div>

<!-- Search -->
<div class="top-bar">
  <input type="text" class="search-input" placeholder="Order Id Search...">

  <div class="date-range">
    <span class="label">Calendar</span>
    <input type="text" id="calendarRange" placeholder="Select date range">
  </div>

  <button class="export">EXPORT</button>
</div>

<!-- Table Header -->
<div class="mini-tabs">
  <a href="order-to-ship.php"class="tab">All 2</a>
  <a href="to-ship-process.php"class="tab" >To Process 2</a>
  <a href="to-ship-completed.php" class="tab active">Process 2</a>
</div>

<div class="table-header">
  <div>Product(s)</div>
  <div>Qty</div>
  <div>Order Total</div>
  <div>Status</div>
  <div>Action</div>
</div>

<?php
// ✅ GET ONLY COMPLETED (SHIPPED)
$orders = $conn->query("
  SELECT * FROM orders 
  WHERE status = 'Shipped'
  ORDER BY id DESC
");

while ($order = $orders->fetch_assoc()):
?>

<div class="order-card">

  <!-- TOP -->
  <div class="order-top">
    <div class="buyer">
      <img src="../PICTURE/default-profile.png">
      <span><?= $order['first_name'] ?> <?= $order['last_name'] ?></span>
    </div>

    <div class="order-id">
      Order Id: <?= $order['order_code'] ?? $order['id'] ?>
    </div>
  </div>

<?php
// GET ITEMS
$items = $conn->query("SELECT * FROM order_items WHERE order_id = '".$order['id']."'");

$total = 0;
$rows = [];

while ($item = $items->fetch_assoc()) {
  $rows[] = $item;
  $total += $item['price'] * $item['qty'];
}
?>

<?php foreach ($rows as $index => $item): ?>

<div class="order-row">

  <!-- PRODUCT -->
  <div class="product">
    <div class="img">
      <img src="../../uploads/<?= $item['image'] ?>" 
        style="width:100%; height:100%; object-fit:cover;">
    </div>

    <div>
      <strong><?= $item['product_name'] ?></strong>
      <span>Color: <?= $item['color'] ?></span>
      <span>Size: <?= $item['size'] ?></span>
      <span>₱<?= $item['price'] ?></span>
    </div>
  </div>

  <!-- QTY -->
  <div class="qty center">
    x<?= $item['qty'] ?>
  </div>

  <?php if ($index === 0): ?>

    <div class="summary-total">₱<?= $total ?></div>

    <div class="summary-status">
      Shipped
    </div>

    <div class="summary-action">
      <a href="order-details.php?id=<?= $order['id'] ?>">
        Check Details
      </a>
    </div>

  <?php else: ?>
    <div></div>
    <div></div>
    <div></div>
  <?php endif; ?>

</div>

<?php endforeach; ?>

</div>

<?php endwhile; ?>

</main>
</div>


</body>
</html>