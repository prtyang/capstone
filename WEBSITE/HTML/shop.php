<?php
include(__DIR__ . "/../../config/db.php");

/* CATEGORY FILTER */
$category = $_GET['category'] ?? '';
$categorySql = '';

if (!empty($category)) {

  $cat = strtoupper(trim($category));

  // UNDERWEAR (catch ANY variation)
  if (strpos($cat, 'UNDER') !== false) {
    $categorySql = " 
      AND (
        TRIM(UPPER(p.category)) = 'BRA' 
        OR TRIM(UPPER(p.category)) = 'PANTY'
      )
    ";
  }

  // INNERWEAR
  elseif (strpos($cat, 'INNER') !== false) {
    $categorySql = " 
      AND TRIM(UPPER(p.category)) IN ('SANDO','PANTYLET','PANTYSHORT')
    ";
  }

  // SLEEPWEAR
  elseif (strpos($cat, 'SLEEP') !== false) {
    $categorySql = " 
      AND TRIM(UPPER(p.category)) = 'SLEEPWEAR'
    ";
  }

  // DIRECT CATEGORY
  else {
    $categorySql = " 
      AND TRIM(UPPER(p.category)) = '$cat'
    ";
  }
}

// SEARCH
$search = $_GET['search'] ?? '';

$searchSql = '';

if (!empty($search)) {
  $safeSearch = $conn->real_escape_string($search);
  $searchSql = "
    AND (
      p.name LIKE '%$safeSearch%'
      OR p.brand LIKE '%$safeSearch%'
    )
  ";
}

// PAGINATION
$limit = 24; 
$page = isset($_GET['page']) && is_numeric($_GET['page'])
  ? (int)$_GET['page']
  : 1;

$offset = ($page - 1) * $limit;

$countSql = "
SELECT COUNT(DISTINCT p.id) AS total
FROM products p
WHERE p.status = 'active'
$categorySql
$searchSql
";

$countResult = $conn->query($countSql);
$totalRows = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

$sql = "
SELECT 
p.id,
p.image,
p.status,
p.brand AS brand_name,
p.name AS product_name,
p.description,

MIN(v.price) AS min_price,
MAX(v.price) AS max_price,

pr.discount_type,
pr.discount_value

FROM products p

LEFT JOIN product_variations v 
ON v.product_id = p.id

LEFT JOIN promotion_products pp
ON pp.product_id = p.id

LEFT JOIN promotions pr
ON pr.id = pp.promotion_id
AND pr.status = 'active'
AND CURDATE() BETWEEN pr.start_date AND pr.end_date

WHERE p.status = 'active'
$categorySql
$searchSql

GROUP BY p.id
ORDER BY p.id DESC
LIMIT $limit OFFSET $offset
";

$result = $conn->query($sql);

//FOOTER
$settings = [];
$res = $conn->query("SELECT setting_key, setting_value FROM site_settings");

while ($row = $res->fetch_assoc()) {
  $settings[$row['setting_key']] = $row['setting_value'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forever | Product</title>
  <link rel="stylesheet" href="../CSS/shop.css">
</head>

<body>
<div class="page">

<header class="header">

  <div class="logo-wrapper">
    <a href="index.html">
      <img src="../PICTURE/logo.png" class="logo">
    </a>
  </div>

  <div class="header-right">
    <div class="icons">
      <a href="wishlist.php" class="icon"><img src="../PICTURE/wishlist.png"></a>
      <a href="cart.php" class="icon"><img src="../PICTURE/cart.png"></a>
      <a href="profile.php" class="icon"><img src="../PICTURE/profile.jpg"></a>
    </div>

    <nav class="nav">
      <a href="index.php">HOME</a>
      <a href="home.php">SHOP</a>
      <a href="shop.php">PRODUCT</a>
      <a href="try-on.html">TRY-ON</a>
    </nav>
  </div>

</header>

<!-- BREADCRUMB -->
<div class="breadcrumb">
  <a href="home.php">SHOP</a>
  <span>/</span>
  <strong>PRODUCT</strong>
</div>

<!--  SEARCH & FILTER -->
<section class="top-controls">
  <form method="GET" style="flex:1;">
    <input
      type="text"
      name="search"
      class="search-box"
      placeholder="search..."
      value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
    >
  </form>

  <div class="filters">
    <form method="GET" id="categoryForm">
      <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">

<select name="category" onchange="this.form.submit()">
  <option value="">ALL</option>

  <optgroup label="UNDERWEAR">
    <option value="UNDERWEAR" <?php if ($category === 'UNDERWEAR') echo 'selected'; ?>>ALL UNDERWEAR</option>
    <option value="BRA" <?php if ($category === 'BRA') echo 'selected'; ?>>BRA</option>
    <option value="PANTY" <?php if ($category === 'PANTY') echo 'selected'; ?>>PANTY</option>
  </optgroup>

  <optgroup label="INNERWEAR">
    <option value="INNERWEAR" <?php if ($category === 'INNERWEAR') echo 'selected'; ?>>ALL INNERWEAR</option>
    <option value="SANDO" <?php if ($category === 'SANDO') echo 'selected'; ?>>SANDO</option>
    <option value="PANTYLET" <?php if ($category === 'PANTYLET') echo 'selected'; ?>>PANTYLET</option>
    <option value="PANTYSHORT" <?php if ($category === 'PANTYSHORT') echo 'selected'; ?>>PANTYSHORT</option>
  </optgroup>

  <optgroup label="SLEEPWEAR">
    <option value="SLEEPWEAR" <?php if ($category === 'SLEEPWEAR') echo 'selected'; ?>>SLEEPWEAR</option>
  </optgroup>
</select>
    </form>
  </div>
</section> 

<!--  PRODUCT GRID  -->
<section class="product-grid">

<?php while ($row = $result->fetch_assoc()) { ?>
<?php

$price = $row['min_price'] ?? 0;
$final_price = $price;
$discount_percent = 0;

if(!empty($row['discount_value']) && $price > 0){

    if($row['discount_type'] == "percentage"){
        $discount_percent = (int)$row['discount_value'];
        $final_price = $price - ($price * $row['discount_value'] / 100);
    }

    elseif($row['discount_type'] == "fixed"){
        $discount_percent = round(($row['discount_value'] / $price) * 100);
        $final_price = $price - $row['discount_value'];
    }

}

?>

<div class="product-card" data-id="<?php echo $row['id']; ?>">

  <a href="product-view.php?id=<?php echo $row['id']; ?>" class="card-link">

<div class="image-box">

  <?php if($final_price < $price){ ?>

  <div class="sale-badge">
  -<?= $discount_percent ?>%
  </div>

<?php } ?>

<img src="../../uploads/<?php echo $row['image']; ?>" alt="">

</div>


<div class="product-info">
<h4 class="brand">
  <?php echo htmlspecialchars($row['brand_name'] ?? ''); ?>
</h4>

<p class="product-name">
  <?php echo htmlspecialchars($row['product_name'] ?? ''); ?>
</p>

<div class="price-box">

<?php if($final_price < $price){ ?>

<span class="old-price">
₱<?= number_format($price,2); ?>
</span>

<span class="sale-price">
₱<?= number_format($final_price,2); ?>
</span>

<?php } else { ?>

<span class="normal-price">
₱<?= number_format($price,2); ?>
</span>

<?php } ?>

</div>


    </div>

  </a>

  <!--WHISHLIST -->
  <span class="heart"
  onclick="event.stopPropagation(); event.preventDefault(); addToWishlist(this);">
  ♡
  </span>
    </div>
<?php } ?>
</section>

<!--PAGINATION-->
<?php if ($totalPages > 1) { ?>
  <div class="pagination">

    <?php if ($page > 1) { ?>
      <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>">
        Prev
      </a>
    <?php } ?>

    <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
      <a
        href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>"
        class="<?php echo $i == $page ? 'active' : ''; ?>"
      >
        <?php echo $i; ?>
      </a>
        <?php } ?>

    <?php if ($page < $totalPages) { ?>
      <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>">
        Next
      </a>
    <?php } ?>
  </div>
<?php } ?>

<!-- FOOTER  -->
<footer class="footer">

  <div class="footer-container">

    <!-- LEFT -->
    <div class="footer-col brand">
      <img src="../PICTURE/logo.png" alt="Forever Logo" class="footer-logo">

      <div class="footer-socials">
        <a href="https://web.facebook.com/intimateforeverph" target="_blank">
          <img src="../PICTURE/FB.png">
        </a>
        <a href="https://www.instagram.com/intimateforeverph/" target="_blank">
          <img src="../PICTURE/IG.png">
        </a>
        <a href="https://www.tiktok.com/@intimateforeverph" target="_blank">
          <img src="../PICTURE/TIKTOK.png">
        </a>
        <a href="https://shopee.ph/forever_ph" target="_blank">
          <img src="../PICTURE/SHOPEE.png">
        </a>
        <a href="https://www.lazada.com.ph/shop/intimateforever" target="_blank">
          <img src="../PICTURE/LAZADA.webp">
        </a>
      </div>
    </div>

    <!-- SUPPORT -->
    <div class="footer-col">
      <h4>SUPPORT</h4>
      <a href="#">Terms of Service</a>
      <a href="#">Privacy Policy</a>
      <a href="#">Refund Policy</a>
      <a href="#">About</a>
    </div>

    <!-- EXTRA LINK -->
    <div class="footer-col">
      <h4>EXTRA LINK</h4>
      <a href="home.php">Product</a>
      <a href="try-on.html">Try-on</a>
      <a href="home.php">Shop</a>
    </div>

    <!-- CONTACT -->
    <div class="footer-col">
      <h4>CONTACT</h4>

      <p><?= htmlspecialchars($settings['footer_phone'] ?? '') ?></p>

      <p><?= nl2br(htmlspecialchars($settings['footer_address'] ?? '')) ?></p>

      <a href="mailto:<?= htmlspecialchars($settings['footer_email'] ?? '') ?>">
          <?= htmlspecialchars($settings['footer_email'] ?? '') ?>
      </a>
    </div>


  </div>

  <div class="footer-bottom">
    © 2026 Capstone Project. All rights reserved.
  </div>

</footer>

<script src="../JS/shop.js"></script>

</body>
</html>
