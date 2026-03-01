<?php 
include "../../config/db.php";

$order_id = $_GET['id'] ?? 0;

// GET ORDER INFO
$order = $conn->query("SELECT * FROM orders WHERE id = $order_id")->fetch_assoc();

// GET ORDER ITEMS
$items = $conn->query("SELECT * FROM order_items WHERE order_id = $order_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Dashboard</title>
  <link rel="stylesheet" href="../CSS/order-view.css">

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

  <div class="logout-bar">
    <div class="logout-content">
      <span class="logout-text">LOG OUT</span>
    </div>
  </div>
</aside>

<!-- MAIN -->
<main class="main">

<!-- Search -->
<div class="top-bar">
  <input type="text" class="search-input" placeholder="Order Id Search...">

  <div class="date-range">
    <span class="label">Calendar</span>
    
    <input type="text" id="calendarRange" placeholder="Select date range">
  </div>

  <button class="export">EXPORT</button>
</div>
<button onclick="goBack()" class="back-btn"> Back</button>

<div class="order-details">

<!-- ORDER HEADER -->
<div class="section order-header-flex">
  <div>
    <h2>Order ID <?= $order['order_code'] ?? $order['id'] ?></h2>
    <p><?= date("F d, Y", strtotime($order['created_at'] ?? 'now')) ?></p>
    <p><?= date("h:i A", strtotime($order['created_at'] ?? 'now')) ?></p>
  </div>

<?php if (
  $order['status'] === 'Cancel' || 
  $order['status'] === 'Shipping' || 
  $order['status'] === 'Completed'
): ?>

  <button class="cancel-btn" disabled 
    style="background:gray; cursor:not-allowed;">
    Completed
  </button>

<?php else: ?>

  <button class="cancel-btn" onclick="cancelOrder(<?= $order['id'] ?>)">
    Cancel Order
  </button>

<?php endif; ?>
</div>

<!-- BUYER INFO -->
<div class="section">
  <h3><?= $order['first_name'] ?> <?= $order['last_name'] ?></h3>
  <p><?= $order['phone'] ?></p>

  <p>
  <?= $order['province'] ?? '' ?>,
  <?= $order['city'] ?? '' ?>,
  <?= $order['barangay'] ?? '' ?>,
  <?= $order['postal_code'] ?? '' ?>
</p>

<p>
  <?= $order['full_address'] ?? '' ?>
</p>

  <p><?= $order['email'] ?></p>

  <br>

  <p><strong>Payment Method:</strong> <?= $order['payment_method'] ?? '' ?></p>
  <p><strong>Delivery Method:</strong> <?= $order['delivery_method'] ?? '' ?></p>
</div>

  <!-- ORDER ITEMS -->
  <div class="section">
  <div class="order-header">
    <span>Orders</span>
    <span>Qty</span>
    <span>Price</span>
    <span>Total</span>
  </div>

<?php 
$total = 0;

while ($item = $items->fetch_assoc()):
  $itemTotal = $item['price'] * $item['qty'];
  $total += $itemTotal;
?>

<div class="order-item">

  <div class="product">
    <div class="img">
      <img src="../../uploads/<?= $item['image'] ?>" 
      style="width:100%; height:100%; object-fit:cover;">
    </div>

    <div>
      <p><?= $item['product_name'] ?></p>
      <small>Color: <?= $item['color'] ?></small>
      <small>Size: <?= $item['size'] ?></small>
    </div>
  </div>

  <div class="qty">x<?= $item['qty'] ?></div>
  <div class="price">₱<?= $item['price'] ?></div>
  <div class="total">₱<?= $itemTotal ?></div>

</div>

<?php endwhile; ?>
<?php
$deliveryFee = !empty($order['delivery_fee']) ? $order['delivery_fee'] : 0;
$coupon = !empty($order['coupon']) ? $order['coupon'] : 0;

$totalIncome = $total + $deliveryFee - $coupon;
?>
<div class="summary">

  <p class="total">
  <span>Total</span>
  <span>₱<?= number_format($total, 2) ?></span>

  <p>
    <span>Delivery Fee</span> 
    <span>₱<?= number_format($deliveryFee, 2) ?></span>
  </p>

  <p>
    <span>Coupon Code</span> 
    <span>₱<?= number_format($coupon, 2) ?></span>
  </p>

  <p class="income">
    <span>Total Income</span>
    <span>₱<?= number_format($totalIncome, 2) ?></span>
  </p>
</div>

  </div>

</div>

<div id="pinModal" class="pin-modal">
  <div class="pin-box">

    <h3 class="pin-title">Confirm Cancel</h3>
    <p class="pin-sub">Enter Action PIN</p>

    <input type="password" id="actionPinInput" maxlength="4" placeholder="••••">

    <div class="pin-actions">
      <button onclick="confirmCancelWithPin()" class="btn confirm">Confirm</button>
      <button onclick="closePinModal()" class="btn cancel">Cancel</button>
    </div>

    <p id="pinError" class="pin-message"></p>

  </div>
</div>

<script src="../JS/order-view.js"></script>
</body>
</html>