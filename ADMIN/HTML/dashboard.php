<?php
include "../../config/db.php";

/*TOTAL REVENUE TODAY */
$todayRevenue = 0;

$result = $conn->query("
SELECT SUM(total) AS revenue
FROM orders
WHERE status='Completed'
AND DATE(created_at) = CURDATE()
");

$row = $result->fetch_assoc();

$todayRevenue = $row['revenue'] ?? 0;


/* TOTAL ORDERS LAST 30 DAYS */
$totalOrders = 0;

$result = $conn->query("
SELECT COUNT(*) AS total_orders
FROM orders
WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
");

$row = $result->fetch_assoc();

$totalOrders = $row['total_orders'] ?? 0;

/*TOTAL CUSTOMERS LAST 30 DAYS */
$result = $conn->query("
SELECT COUNT(DISTINCT email) AS total_customers
FROM orders
WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
");

$row = $result->fetch_assoc();
$totalCustomers = $row['total_customers'] ?? 0;

/* DAILY SALES THIS MONTH*/
$currentMonth = date('m');
$currentYear = date('Y');
$daysInMonth = date('t');

$labels = [];
$data = [];

/* create all days of month */
for($d=1; $d <= $daysInMonth; $d++){
    $labels[] = $d;
    $data[$d] = 0;
}

$query = $conn->query("
SELECT DAY(created_at) as day, SUM(total) as total
FROM orders
WHERE status='Completed'
AND MONTH(created_at)='$currentMonth'
AND YEAR(created_at)='$currentYear'
GROUP BY DAY(created_at)
");

while($row = $query->fetch_assoc()){
    $data[$row['day']] = (float)$row['total'];
}

$data = array_values($data);

/* TOP 3 SELLING PRODUCTS */
$topProducts = $conn->query("
SELECT 
    product_name,
    SUM(qty) as total_sold,
    MAX(image) as image
FROM order_items
GROUP BY product_name
ORDER BY total_sold DESC
LIMIT 3
");

?>

<?php
session_start();

if(!isset($_SESSION['admin'])){
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../CSS/dashboard.css">
</head>
<body>

<div class="container">

<!-- SIDEBAR -->

  <aside class="sidebar">
    <div class="logo">
      <img src="../PICTURE/logo.png">
    </div>
  <nav class="menu">

  <a href="dashboard.php" class="active" onclick="goPage('dashboard.php')">
  <img src="../PICTURE/home logo.png" class="menu-icon">
  Dashboard
</a>

  <a href="product.php" onclick="goPage('product.php')">
    <img src="../PICTURE/product logo.png" class="menu-icon">
    Product
  </a>

  <a href="order.php" onclick="goPage('order.php')">
    <img src="../PICTURE/ORDER LOGO.webp" class="menu-icon">
    Order
  </a>

<a href="sales.php" onclick="goPage('sales.html')">
    <img src="../PICTURE/SALES LOGO.png" class="menu-icon">
    Sales
  </a>

  <a href="marketing.php" onclick="goPage('marketing.html')">
    <img src="../PICTURE/MARKETING LOGO.png" class="menu-icon">
    Marketing
  </a>

  <a href="account.php" onclick="goPage('account.php')">
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

  <!-- MAIN CONTENT -->
  <main class="main">

    <h2 class="page-title">Overview</h2>

    <!-- STATS -->
    <section class="stats">
      <div class="stat-card">
        <p>Total Revenue</p>
        <span>Today</span>
        <h3>₱ <?= number_format($todayRevenue,2) ?></h3>
      </div>

      <div class="stat-card">
        <p>Total Order</p>
        <span>Last 30days</span>
        <h3><?= $totalOrders ?></h3>
      </div>

      <div class="stat-card">
        <p>Total Customer</p>
        <span>Last 30days</span>
        <h3><?= $totalCustomers ?></h3>
      </div>
    </section>

    <!-- SALES ANALYTICS -->
    <section class="box">
      <h4>MONTHLY SALES ANALYTICS</h4>
      <div class="chart-box">
        <canvas id="salesChart"></canvas>
      </div>
    </section>

    <!-- TOP SELLING PRODUCTS -->
    <section class="box">
      <h4>TOP SELLING PRODUCTS</h4>

      <div class="products">
      <?php while($product = $topProducts->fetch_assoc()): ?>

      <div class="product-card">

      <div class="product-img">
        <img src="../../uploads/<?= $product['image'] ?>">
      </div>

  <div class="product-info">
    <h5><?= $product['product_name'] ?></h5>

    <p class="product-desc">
      Sold: <?= $product['total_sold'] ?>
    </p>
  </div>

</div>
<?php endwhile; ?>
</div>
  </section>

  </main>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const labels = <?= json_encode($labels) ?>;
const sales = <?= json_encode($data) ?>;

const ctx = document.getElementById('salesChart').getContext('2d');

new Chart(ctx, {
type: 'line',

data: {
labels: labels,
datasets: [{
label: "Sales",
data: sales,
borderColor: '#d81b60',
borderWidth: 3,
tension: 0.4,
fill: true,
backgroundColor: 'rgba(216,27,96,0.15)',
pointRadius: 4
}]
},

options: {
responsive: true,

plugins: {
legend: { display: false }
},

scales: {

x: {
title: {
display: true,
text: 'Days of Month'
}
},

y:{
min:0,
max:6500,

afterBuildTicks:(axis)=>{
axis.ticks = [
{value:0},
{value:300},
{value:600},
{value:1000},
{value:1500},
{value:2000},
{value:2500},
{value:3000},
{value:3500},
{value:4000},
{value:4500},
{value:5000},
{value:5500},
{value:6000}
];
},

ticks:{
callback:(value)=>"₱"+value
}

}
}

}

});

</script>

</body>
</html>
