<?php
session_start();
include "../../config/db.php";

// PROTECT PAGE
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

// COUNT ORDERS
$toShipCount = $conn->query("SELECT COUNT(*) as total FROM orders WHERE status='To Ship'")->fetch_assoc()['total'];
$shippingCount = $conn->query("SELECT COUNT(*) as total FROM orders WHERE status='Shipping'")->fetch_assoc()['total'];
$toProcessCount = $conn->query("SELECT COUNT(*) as total FROM orders WHERE status='To Process'")->fetch_assoc()['total'];
?>
<?php
$toShipCount = $conn->query("
  SELECT COUNT(*) as total FROM orders WHERE status='To Ship'
")->fetch_assoc()['total'];

$shippingCount = $conn->query("
  SELECT COUNT(*) as total FROM orders WHERE status='Shipped'
")->fetch_assoc()['total'];

$processCount = $conn->query("
  SELECT COUNT(*) as total FROM orders WHERE status='Shipping'
")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Dashboard</title>
  <link rel="stylesheet" href="../CSS/to-ship-process.css">
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
    <a href="dashboard.php">
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

    <a href="sales.php">
      <img src="../PICTURE/SALES LOGO.png" class="menu-icon">
      Sales
    </a>

    <a href="marketing.php">
      <img src="../PICTURE/MARKETING LOGO.png" class="menu-icon">
      Marketing
    </a>

    <a href="account.php">
      <img src="../PICTURE/ACCOUNT LOGO.png" class="menu-icon">
      Account
    </a>
  </nav>

    <a href="logout.php" class="logout-bar">
        <div class="logout-content">
            <span class="logout-text">LOG OUT</span>
        </div>
    </a>
    
</aside>

<!-- MAIN -->
<main class="main">


<!-- Tabs -->
<div class="tabs">
  <a href="order.php">All</a>

  <a href="order-to-ship.php" class="active" >
    To Ship <?= $processCount + $toShipCount ?></small>
  </a>

  <a href="order-shipping.php" >
    Shipping <small><?= $shippingCount ?></small>
  </a>

  <a href="order-completed.php">Completed</a>
  <a href="order-cancel.php">Cancel</a>
  <a href="return-refund.php">Return/Refund</a>
</div>

<div class="top-bar"></div>

<!-- Table Header -->
<div class="mini-tabs">
  <a href="order-to-ship.php" class="tab">
    All <?= $processCount + $toShipCount ?>
  </a>

  <a href="to-ship-process.php" class="tab">
    To process <?= $toShipCount ?>
  </a>

  <a href="to-ship-completed.php" class="tab active">
    Process <?= $processCount ?>
  </a>
</div>

<div class="table-header">
  <div>Product(s)</div>
  <div>Qty</div>
  <div>Order Total</div>
  <div>Status</div>
  <div>Action</div>
</div>

<?php
$orders = $conn->query("
  SELECT * FROM orders 
  WHERE status = 'Shipping'
  ORDER BY id DESC
");

while ($order = $orders->fetch_assoc()):
?>

<div class="order-card">

  <!-- TOP -->
  <div class="order-top">
    <div class="buyer">
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

if ($items && $items->num_rows > 0) {
  while ($item = $items->fetch_assoc()) {
    $rows[] = $item;
    $total += $item['price'] * $item['qty'];
  }
}
?>

<?php if (count($rows) > 0): ?>
  <?php foreach ($rows as $index => $item): ?>

  <div class="order-row">

    <!-- PRODUCT -->
    <div class="product">
      <div class="img">
        <?php if (!empty($item['image'])): ?>
          <img src="../../uploads/<?= $item['image'] ?>" 
              style="width:100%; height:100%; object-fit:cover;">
        <?php endif; ?>
      </div>

      <div>
        <strong><?= $item['product_name'] ?></strong>
        <span>Color: <?= $item['color'] ?? '-' ?></span>
        <span>Size: <?= $item['size'] ?? '-' ?></span>
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
        <?= $order['status'] ?>
      </div>

      <div class="summary-action">
        <a href="#" onclick="pickupOrder(<?= $order['id'] ?>)">
          Pick-up / Drop
        </a>
      </div>

    <?php else: ?>
      <div></div>
      <div></div>
      <div></div>
    <?php endif; ?>

  </div>

  <?php endforeach; ?>
<?php endif; ?>

</div>

<?php endwhile; ?>

</main>
</div>

<script src="../JS/to-ship-process.js"></script>

</body>
</html>