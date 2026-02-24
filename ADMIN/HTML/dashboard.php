<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
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

<a href="sales.html" onclick="goPage('sales.html')">
    <img src="../PICTURE/SALES LOGO.png" class="menu-icon">
    Sales
  </a>

  <a href="marketing.html" onclick="goPage('marketing.html')">
    <img src="../PICTURE/MARKETING LOGO.png" class="menu-icon">
    Marketing
  </a>

  <a href="account.php" onclick="goPage('account.php')">
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

  <!-- MAIN CONTENT -->
  <main class="main">

    <h2 class="page-title">Overview</h2>

    <!-- STATS -->
    <section class="stats">
      <div class="stat-card">
        <p>Total Revenue</p>
        <span>Last 30days</span>
        <h3>₱ 0</h3>
      </div>

      <div class="stat-card">
        <p>Total Order</p>
        <span>Last 30days</span>
        <h3>1</h3>
      </div>

      <div class="stat-card">
        <p>Total Customer</p>
        <span>Last 30days</span>
        <h3>1</h3>
      </div>
    </section>

    <!-- SALES ANALYTICS -->
    <section class="box">
      <h4>SALES ANALYTICS</h4>
      <div class="analytics-placeholder">
        Chart will appear here
      </div>
    </section>

    <!-- TOP SELLING PRODUCTS -->
    <section class="box">
      <h4>TOP SELLING PRODUCTS</h4>

      <div class="products">
        <div class="product-card"></div>
        <div class="product-card"></div>
        <div class="product-card"></div>
        <div class="product-card"></div>
      </div>
    </section>

  </main>

</div>

</body>
</html>
