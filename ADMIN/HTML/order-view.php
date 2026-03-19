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

<main class="main">

<!-- ORDER HEADER -->
<div class="section order-header-flex">
<?php $status = $order['status']; ?>

<div>
  <h2>Order ID <?= $order['order_code'] ?? $order['id'] ?></h2>
    <p><?= date("F d, Y", strtotime($order['created_at'] ?? 'now')) ?></p>
    <p><?= date("h:i A", strtotime($order['created_at'] ?? 'now')) ?></p>
  </div>

<?php
$status = $order['status'];
?>

<?php if ($status === 'Request Return'): ?>

  <button class="approve-btn" onclick="approveRefund(<?= $order['id'] ?>)">
    Approve Refund
  </button>

<?php elseif ($status === 'Waiting to Refund'): ?>

  <button class="refund-btn" onclick="processRefund(<?= $order['id'] ?>)">
    Process Refund
  </button>

<?php elseif ($status === 'Refunded'): ?>

  <button class="disabled-btn" disabled>
    Refunded
  </button>

<?php elseif ($status === 'Completed'): ?>

  <button class="disabled-btn" disabled>
    Completed
  </button>

<?php elseif ($status === 'Shipping' || $status === 'Shipped'): ?>

  <button class="disabled-btn" disabled>
    In Delivery
  </button>

<?php elseif ($status === 'Cancel'): ?>

  <button class="disabled-btn" disabled>
    Cancelled
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
$deliveryFee = isset($order['delivery_fee']) ? $order['delivery_fee'] : 0;
$coupon = isset($order['coupon']) ? $order['coupon'] : 0;
$couponCode = isset($order['coupon_code']) ? $order['coupon_code'] : '';

$totalIncome = $total + $deliveryFee - $coupon;
?>

<div class="summary">

  <p class="total">
    <span>Subtotal</span>
    <span>₱<?= number_format($total, 2) ?></span>
  </p>

  <p>
    <span>Delivery Fee</span>
    <span>₱<?= number_format($deliveryFee, 2) ?></span>
  </p>

  <p>
    <span>Coupon</span>
    <span>
      <?= $couponCode ? $couponCode : 'None' ?>
      (-₱<?= number_format($coupon, 2) ?>)
    </span>
  </p>

  <p class="income">
    <span>Total Payment</span>
    <span>₱<?= number_format($totalIncome, 2) ?></span>
  </p>

</div>

<!-- REFUND SECTION -->
<?php 
$status = strtolower($order['status']);

if (
  $status === 'request return' ||
  $status === 'waiting to refund' ||
  $status === 'refunded' ||
  $status === 'refund'
): 
?>

<div class="refund-section">

  <h4>Refund Status: <?= $status ?></h4>

  <p>Created Return/Refund Date</p>
  <p><?= date("F d, Y", strtotime($order['created_at'])) ?></p>

  <p>Created Return/Refund Time</p>
  <p><?= date("h:i A", strtotime($order['created_at'])) ?></p>

  <h4>Message</h4>
  <textarea class="refund-message" readonly><?= $order['refund_message'] ?? 'No message provided' ?></textarea>

  <h4>Supporting Image / Video</h4>
  <div class="refund-media">
    <?php
    $images = json_decode($order['refund_images'] ?? '[]', true);
    if (!empty($images)):
      foreach ($images as $img):
    ?>
        <div class="media-box">
          <img src="../../uploads/<?= $img ?>" />
        </div>
    <?php endforeach; else: ?>
        <div class="media-box"></div>
        <div class="media-box"></div>
        <div class="media-box"></div>
    <?php endif; ?>
  </div>

  <div class="refund-actions">

    <?php if ($status === 'Request Return'): ?>

      <button onclick="approveRefund(<?= $order['id'] ?>)" class="approve-btn">
        Approve
      </button>

    <?php elseif ($status === 'Waiting to Refund'): ?>
      <button onclick="processRefund(<?= $order['id'] ?>)" class="refund-btn">
        Refund
      </button>

    <?php elseif ($status === 'Refunded'): ?>
      <button class="done-btn" disabled>
        Refunded
      </button>
    <?php endif; ?>

  </div>

</div>

<?php endif; ?>

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