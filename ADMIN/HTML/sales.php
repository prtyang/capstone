<?php
include "../../config/db.php";

// GET WITHDRAW PIN FROM DATABASE
$getPin = $conn->query("SELECT setting_value FROM site_settings WHERE setting_key='pin_withdraw'");
$row = $getPin->fetch_assoc();

$withdrawPIN = $row['setting_value'] ?? '0000';
/* =======================
   DEFAULT MONTH
======================= */
$type = isset($_GET['type']) ? $_GET['type'] : 'monthly';
$selectedMonth = isset($_GET['month']) ? $_GET['month'] : date('m');

/* =======================
   WEEKLY DATA
======================= */
$daysInMonth = date("t", strtotime(date("Y") . "-" . $selectedMonth . "-01"));

$weekLabels = [
  "W1 (1-7)",
  "W2 (8-14)",
  "W3 (15-21)",
  "W4 (22-28)",
  "W5 (29-" . $daysInMonth . ")"
];

$weekData = array_fill(0, 5, 0);

$weekly = $conn->query("
  SELECT 
    CEIL(DAY(created_at)/7) as week,
    SUM(total) as total
  FROM orders
  WHERE status = 'Completed'
  AND MONTH(created_at) = '$selectedMonth'
  AND YEAR(created_at) = YEAR(CURDATE())
  GROUP BY week
");

while ($row = $weekly->fetch_assoc()) {

  $weekIndex = (int)$row['week'] - 1;

  if ($weekIndex >= 0 && $weekIndex < 5) {
    $weekData[$weekIndex] = (float)$row['total'];
  }
}

if ($type === 'weekly') {
  // ensure data is always for selected month
  $daysInMonth = date("t", strtotime(date("Y") . "-" . $selectedMonth . "-01"));

$weekLabels = [
  "W1 (1-7)",
  "W2 (8-14)",
  "W3 (15-21)",
  "W4 (22-28)",
  "W5 (29-" . $daysInMonth . ")"
];
}

// ensure weeks always exist (even if no data)
for ($i = 0; $i < 5; $i++) {
  if (!isset($weekData[$i])) {
    $weekData[$i] = 0;
  }
}

/* =======================
   MONTHLY DATA
======================= */
$monthLabels = ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
$monthData = array_fill(0, 12, 0);

$monthly = $conn->query("
  SELECT MONTH(created_at) as month, SUM(total) as total
  FROM orders
  WHERE status = 'Completed'
  AND YEAR(created_at) = YEAR(CURDATE())
  GROUP BY month
");

while ($row = $monthly->fetch_assoc()) {
  $index = $row['month'] - 1;
  $monthData[$index] = (float)$row['total'];
}

$weeklySales = [];

// initialize weeks
for ($i = 1; $i <= 5; $i++) {
  $weeklySales[$i] = [
    "products" => [],
    "total" => 0
  ];
}

$orders = $conn->query("
  SELECT oi.product_name, o.total, o.created_at
  FROM orders o
  JOIN order_items oi ON o.id = oi.order_id
  WHERE o.status = 'Completed'
  AND MONTH(o.created_at) = '$selectedMonth'
  AND YEAR(o.created_at) = YEAR(CURDATE())
");

while ($row = $orders->fetch_assoc()) {

  $day = date('d', strtotime($row['created_at']));
  $week = ceil($day / 7);

  if ($week >= 1 && $week <= 5) {

    // add product name
    $weeklySales[$week]["products"][] = "Order on " . $row['created_at'];

    // add total
    $weeklySales[$week]["total"] += (float)$row['total'];
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order</title>
  <link rel="stylesheet" href="../CSS/sales.css">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    <a href="order.php">
      <img src="../PICTURE/ORDER LOGO.webp" class="menu-icon">
      Order
    </a>

    <a href="sales.php"class="active">
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
  <div class="analytics">

    <h2>ANALYTICS OVERVIEW</h2>

    <!-- 🔥 FILTER BOX (NEW) -->
<div class="filter-box">

  <button class="filter-btn <?= $type == 'monthly' ? 'active' : '' ?>" data-type="monthly">Monthly</button>
  <button class="filter-btn <?= $type == 'weekly' ? 'active' : '' ?>" data-type="weekly">Weekly</button>

  <!-- 🔥 MONTH FILTER (hidden by default) -->
  <select id="monthFilter" class="month-filter" onchange="changeMonth(this.value)">
    <?php
    for ($m = 1; $m <= 12; $m++):
      $val = str_pad($m, 2, "0", STR_PAD_LEFT);
    ?>
      <option value="<?= $val ?>" <?= $val == $selectedMonth ? 'selected' : '' ?>>
        <?= date("F", mktime(0,0,0,$m,10)) ?>
      </option>
    <?php endfor; ?>
  </select>

</div>

    <!-- TOP GRAPH -->
    <div class="top-section">

     <div class="card big">
  <p class="label">Sales Overview</p>

  <div class="graph-wrapper">
    <canvas id="salesChart"></canvas>
  </div>
</div>


    <!-- SALES MONTHLY (MOVED INSIDE) -->
<?php if (true): ?>

<div class="sales-section">

  <!-- HEADER -->
  <div class="sales-header">
    <h3>Sales Monthly (<?= date("F") ?>)</h3>
    <button class="withdraw" onclick="openPinModal()">Withdraw All</button>
  </div>

  <!-- DIVIDER -->
  <div class="divider"></div>

  <?php 
  $grandTotal = 0;

  for ($w = 1; $w <= 5; $w++): 
    $weekInfo = $weeklySales[$w] ?? ["products"=>[], "total"=>0];
    $grandTotal += $weekInfo["total"];
  ?>

  <div class="sale-item">
    <div class="week-left">
      <strong>WEEK <?= $w ?> Total order</strong>

      <?php if (!empty($weekInfo["products"])): ?>
        <?php foreach ($weekInfo["products"] as $p): ?>
          <p class="product-name"><?= $p ?></p>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="product-name empty">No orders</p>
      <?php endif; ?>
    </div>

    <div class="week-total">
      ₱<?= number_format($weekInfo["total"], 2) ?>
    </div>
  </div>

  <?php endfor; ?>

  <!-- TOTAL -->
  <div class="total-income">
    Total Income <span>₱<?= number_format($grandTotal, 2) ?></span>
  </div>

</div>

<?php endif; ?>

</div>
</main>

<!-- PIN MODAL -->
<div id="pinModal" class="pin-modal">
  <div class="pin-box">

    <h3 class="pin-title"> Secure Withdrawal</h3>
    <p class="pin-sub">Enter your 4-digit PIN</p>

    <div class="pin-input-wrapper">
      <input 
        type="password" 
        id="userPin" 
        maxlength="4"
        placeholder="••••"
      >
    </div>

    <div class="pin-actions">
      <button class="btn confirm" onclick="submitPin()">Confirm</button>
      <button class="btn cancel" onclick="closePinModal()">Cancel</button>
    </div>

    <p id="pinMessage" class="pin-message"></p>

  </div>
</div>
<script>
const currentType = "<?= $type ?>";
const selectedMonth = "<?= $selectedMonth ?>";
</script>

<script>
const dataSets = {
  weekly: {
    labels: <?= json_encode($weekLabels) ?>,
    data: <?= json_encode($weekData) ?>
  },
  monthly: {
    labels: <?= json_encode($monthLabels) ?>,
    data: <?= json_encode($monthData) ?>
  }
};
</script>

<script src="../JS/sales.js"></script>

<div class="chat-float">
  <img src="../PICTURE/message.png" alt="Chat">
  <span class="chat-badge">1</span>
</div>

</body>
</html>