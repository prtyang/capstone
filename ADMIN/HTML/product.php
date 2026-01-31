<?php
include(__DIR__ . "/../../config/db.php");
$result = $conn->query("
  SELECT 
    p.id,
    p.image,
    p.status,
    p.brand AS brand_name,
    p.name  AS product_name,
    MIN(v.price) AS min_price,
    MAX(v.price) AS max_price,
    SUM(v.qty)   AS total_qty
  FROM products p
  LEFT JOIN product_variations v 
    ON v.product_id = p.id
  GROUP BY p.id
  ORDER BY p.id DESC
");


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Products</title>
  <link rel="stylesheet" href="../CSS/product.css">
  <script src="../JS/product.js" defer></script>
</head>

<body>
<div class="container">

  <!-- SIDEBAR -->

   <aside class="sidebar">
    <div class="logo">
      <img src="../PICTURE/logo.png">
    </div>
   <nav class="menu">

  <a href="dashboard.html" onclick="goPage('dashboard.html')">
  <img src="../PICTURE/home logo.png" class="menu-icon">
  Dashboard
</a>

  <a href="product.php" onclick="goPage('product.html')">
    <img src="../PICTURE/product logo.png" class="menu-icon">
    Product
  </a>

  <a href="order.html" onclick="goPage('order.html')">
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

  <a href="account.html" onclick="goPage('account.html')">
    <img src="../PICTURE/ACCOUNT LOGO.png" class="menu-icon">
    Account
  </a>
</nav>
    </aside>

  <!--FILTER -->
  <main class="main">

    <div class="top-bar">
      <input type="text" id="searchInput" placeholder="Search...">


      <select id="statusFilter">
        <option value="all">All</option>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
      </select>

      <div class="top-actions">
        <button class="delete-all-btn" id="deleteSelectedBtn">
          Delete Selected
        </button>

        <a href="add-product.php">
          <button class="add-btn">Add New Product</button>
        </a>
      </div>
    </div>

    <div class="product-box">
      <div class="product-header">
        <span></span>
        <span>Product Info</span>
        <span>Price</span>
        <span>Qty</span>
        <span>Status</span>
        <span>Delete</span>
      </div>

      <?php while ($row = $result->fetch_assoc()) { ?>
      <div class="product-row">

    <input type="checkbox"
      class="row-checkbox"
      value="<?php echo $row['id']; ?>">

    <div class="product-info">
      <div class="product-img"
        style="background-image:url('../../uploads/<?php echo $row['image']; ?>');">
      </div>
      <div>
        <h4><?php echo $row['brand_name']; ?></h4>
        <p><?php echo $row['product_name']; ?></p>


        <!-- EDIT-->
        <div class="edit-action">
          <a href="add-product.php?id=<?php echo $row['id']; ?>">EDIT</a>
        </div>
      </div>
    </div>

    <div>
  ₱
  <?php
  if ($row['min_price'] !== null) {
    echo ($row['min_price'] == $row['max_price'])
      ? number_format($row['min_price'], 2)
      : number_format($row['min_price'], 2) . ' - ' . number_format($row['max_price'], 2);
  } else {
    echo number_format($row['price'], 2);
  }
  ?>
</div>

    <div><?php echo $row['total_qty'] ?? 0; ?></div>


    <!--STATUS-->
    <div class="status">
  <span 
    class="status-text <?php echo $row['status']; ?>"
    data-id="<?php echo $row['id']; ?>"
    data-status="<?php echo $row['status']; ?>"
  >
    <?php echo ucfirst($row['status']); ?>
  </span>
    </div>

    <!--DELETE-->
    <button class="delete-btn single-delete"
      data-id="<?php echo $row['id']; ?>">
      DELETE
    </button>

  </div>
<?php } ?>

    </div>
  </main>
</div>
</body>
</html>
