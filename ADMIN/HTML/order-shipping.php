<?php include "../../config/db.php"; 
// COUNT ORDERS
$toShipCount = $conn->query("SELECT COUNT(*) as total FROM orders WHERE status='To Ship'")->fetch_assoc()['total'];
$shippingCount = $conn->query("
  SELECT COUNT(*) as total 
  FROM orders 
  WHERE status='Shipped'
")->fetch_assoc()['total'];$toProcessCount = $conn->query("SELECT COUNT(*) as total FROM orders WHERE status='To Process'")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Dashboard</title>
  <link rel="stylesheet" href="../CSS/order-shipping.css">
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

<!-- Tabs -->
<div class="tabs">
  <a href="order.php">All</a>

  <a href="order-to-ship.php" >
    To Ship <small><?= $toShipCount ?></small>
  </a>

  <a href="order-shipping.php" class="active">
    Shipping <small><?= $shippingCount ?></small>
  </a>

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
<div class="table-header">
  <div>Products</div>
  <div>Qty</div>
  <div>Order Total</div>
  <div>Status</div>
  <div>Action</div>
</div>

<?php
$orders = $conn->query("
  SELECT * FROM orders 
  WHERE status = 'Shipped'
  ORDER BY id DESC
");

while ($order = $orders->fetch_assoc()):
?>

<!-- ORDER CARD -->
<div class="order-card" data-status="<?= $order['status'] ?? 'To Ship' ?>">

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

if ($items && $items->num_rows > 0) {
  while ($item = $items->fetch_assoc()) {
    $rows[] = $item;
    $total += $item['price'] * $item['qty'];
  }
}
?>

<!-- ITEMS LOOP -->
<?php if (count($rows) > 0): ?>

  <?php foreach ($rows as $index => $item): ?>

    <div class="order-row">

    <!-- PRODUCT -->
    <div class="product">
      <a href="order-view.php?id=<?= $order['id'] ?>" class="product-link">
    <div class="img">
      <?php if (!empty($item['image'])): ?>
        <img src="../../uploads/<?= $item['image'] ?>" 
        style="width:100%; height:100%; object-fit:cover;">
      <?php endif; ?>
    </div>

    <div class="product-info">
      <strong><?= $item['product_name'] ?></strong>
      <span>Color: <?= $item['color'] ?? '-' ?></span>
      <span>Size: <?= $item['size'] ?? '-' ?></span>
      <span>₱<?= $item['price'] ?></span>
    </div>

  </a>

</div>
    
    <!-- QTY -->
      <div class="qty center">
        x<?= $item['qty'] ?>
      </div>              
      <?php if ($index === 0): ?>
        <div class="summary-total">₱<?= $total ?></div>

        <div class="summary-status">
          <?= $order['status'] ?? 'To Ship' ?>
        </div>

        <div class="summary-action">
          <a href="order-view.php?id=<?= $order['id'] ?>">
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

  <?php else: ?>

  <div class="order-row">
    <div class="product">
      <strong style="color:red;">No items found for this order</strong>
    </div>
  </div>

  <?php endif; ?>
  </div> 
<?php endwhile; ?>
</main>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="../JS/order-shipping.js"></script>

<script>
flatpickr("#calendarRange", {
  mode: "range",
  dateFormat: "d/m/Y",
});
</script>

</body>
</html>