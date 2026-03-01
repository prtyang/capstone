<?php
include(__DIR__ . "/../../config/db.php");

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = "SELECT * FROM products WHERE id = $id AND status = 'active'";
$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
  die("Product not found.");
}

$product = $result->fetch_assoc();

/* GALLERY IMAGES */
$images = [];

$imgRes = $conn->query("
  SELECT image
  FROM product_images
  WHERE product_id = $id
  ORDER BY id ASC
");

while ($row = $imgRes->fetch_assoc()) {
  $images[] = $row['image'];
}

/* PRICE RANGE FROM VARIATIONS */
$minPrice = null;
$maxPrice = null;

$priceRes = $conn->query("
  SELECT MIN(price) AS min_price, MAX(price) AS max_price
  FROM product_variations
  WHERE product_id = $id
");

/* LOAD COLORS FROM VARIATIONS */
$colors = [];

$colorRes = $conn->query("
  SELECT DISTINCT color
  FROM product_variations
  WHERE product_id = $id
  AND color <> ''
");

while ($row = $colorRes->fetch_assoc()) {
  $colors[] = $row['color'];
}

if ($priceRes && $priceRes->num_rows > 0) {
  $p = $priceRes->fetch_assoc();
  $minPrice = $p['min_price'];
  $maxPrice = $p['max_price'];
}

/* SIZES VARIATIONS */
$sizes = [];

$sizeRes = $conn->query("
  SELECT DISTINCT size
  FROM product_variations
  WHERE product_id = $id
  AND size <> ''
  ORDER BY
    /* TEXT SIZES */
    CASE UPPER(size)
      WHEN 'XS' THEN 1
      WHEN 'S' THEN 2
      WHEN 'SMALL' THEN 2
      WHEN 'M' THEN 3
      WHEN 'MEDIUM' THEN 3
      WHEN 'L' THEN 4
      WHEN 'LARGE' THEN 4
      WHEN 'XL' THEN 5
      WHEN 'XXL' THEN 6
      WHEN '2XL' THEN 6
      WHEN '3XL' THEN 7
      ELSE 100
    END,

    /* PURE NUMBERS (32,34,36) */
    CASE 
      WHEN size REGEXP '^[0-9]+$' THEN CAST(size AS UNSIGNED)
      ELSE 999
    END,

    /* LETTER + NUMBER (A32, B34) */
    CASE
      WHEN size REGEXP '^[A-Za-z]+[0-9]+$'
      THEN CAST(REGEXP_SUBSTR(size,'[0-9]+') AS UNSIGNED)
      ELSE 9999
    END,

    /* FINAL FALLBACK */
    size ASC
");

while ($row = $sizeRes->fetch_assoc()) {
  $sizes[] = $row['size'];
}

/* LOAD REVIEWS */
$reviews = [];
$reviewRes = $conn->query("
SELECT user_name, user_image, rating, comment, image
FROM product_reviews
  WHERE product_id = $id
  ORDER BY created_at DESC
");

while ($row = $reviewRes->fetch_assoc()) {
  $reviews[] = $row;
}

$totalReviews = count($reviews);

/* RATING CALCULATION  */
$ratingCount = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
$averageRating = 0;

if ($totalReviews > 0) {
  $sum = 0;
  foreach ($reviews as $r) {
    $ratingCount[$r['rating']]++;
    $sum += $r['rating'];
  }
  $averageRating = round($sum / $totalReviews, 1);
}

/* LOAD ALL VARIATIONS */
$variations = [];

$varRes = $conn->query("
  SELECT price, size, color, qty, image
  FROM product_variations
  WHERE product_id = $id
");

while ($row = $varRes->fetch_assoc()) {
  $variations[] = $row;
}

$cartIndex = isset($_GET['cartIndex']) ? (int)$_GET['cartIndex'] : -1;

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
  <title>Product View | Forever</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../CSS/product-view.css">
</head>
<body>

<div class="page">

    <!-- LOGO -->
    <div class="logotitapper">
      <a href="index.html">
        <img src="../PICTURE/logo.png" class="logo" alt="Forever Logo">
      </a>
    </div>

    <!-- NAV + ICONS -->
    <div class="header-right">

      <div class="icons">

        <a href="wishlist.php" class="icon">
          <img src="../PICTURE/wishlist.png" alt="Wishlist">
        </a>

        <a href="cart.php" class="icon">
          <img src="../PICTURE/cart.png" alt="Cart">
        </a>

        <a href="Customer-service.php" class="icon">
          <img src="../PICTURE/Customer-services.png">
        </a>
        

        <a href="profile.php" class="icon" id="userIcon">
          <img src="../PICTURE/profile.jpg" alt="Profile">
        </a>
      </div>

      <nav class="nav">
        <a href="index.php">HOME</a>
        <a href="home.php">SHOP</a>
        <a href="shop.php">PRODUCT</a>
        <a href="try-on.html">TRY-ON</a>
      </nav>

    </div>
  </header>

<div class="breadcrumb">
  <a href="shop.php" class="breadcrumb-link">PRODUCT</a>
  <span class="breadcrumb-separator">/</span>
  <span class="breadcrumb-current">VIEW PRODUCT</span>
</div>

  <!-- PRODUCT SECTION  -->
  <section class="product-section">
    
    <!-- IMAGE AREA -->
<div class="image-area">
  <button class="image-arrow prev">‹</button>

  <img
    src="../../uploads/<?php echo $product['image']; ?>"
    class="main-image"
    alt="<?php echo htmlspecialchars($product['name']); ?>"
  >

  <button class="image-arrow next">›</button>

  <div class="thumbnails">
    <?php foreach ($images as $img) { ?>
      <img
        src="../../uploads/<?php echo $img; ?>"
        alt=""
      >
    <?php } ?>
  </div>
</div> 

<!-- INFO AREA -->
<div class="info-area">

<div class="product-title">
  <span class="brand-name">
    <?php echo htmlspecialchars($product['brand']); ?>
  </span><br>

  <span class="product-name">
    <?php echo htmlspecialchars($product['name']); ?>
  </span>
</div>
  <p class="price">
  ₱
  <?php
    if ($minPrice !== null && $maxPrice !== null) {
      echo ($minPrice == $maxPrice)
        ? number_format($minPrice, 2)
        : number_format($minPrice, 2) . ' - ' . number_format($maxPrice, 2);
    }
  ?>
</p>

<!-- COLOR -->
<div class="option">
  <label>COLOR</label>

  <div class="colors">
    <?php if (empty($colors)) { ?>
      <small>No colors available</small>
    <?php } ?>

    <?php foreach ($colors as $c) { ?>
    
      <span
        class="color"
        data-color="<?php echo htmlspecialchars($c); ?>"
        style="background: <?php echo htmlspecialchars($c); ?>;">
      </span>
      
    <?php } ?>
  </div>
</div>

<!-- SIZE -->
<div class="option">
  <label>SIZE</label>

  <div class="sizes">
    <?php if (empty($sizes)) { ?>
      <small>No sizes available</small>
    <?php } ?>

    <?php foreach ($sizes as $s) { ?>
      <button
        type="button"
        class="size-btn"
        data-size="<?php echo htmlspecialchars($s); ?>">
        <?php echo htmlspecialchars($s); ?>
      </button>
    <?php } ?>
  </div>
</div>

<!-- QTY -->
<div class="option">
  <label>QTY</label>
  <input
    type="number"
    id="qtyInput"
    min="1"
    value="1"
    class="qty-input"
  >
  <small id="availableQty" style="color:#666;"></small>
</div>

<!-- BUTTONS -->
  <div class="actions">

      <button type="button" class="btn outline" id="addToCartBtn">
        ADD TO BASKET
    </button>

      <button type="button" class="btn solid" id="checkoutBtn">
        CHECKOUT
      </button>

    </div>
  </div>

  </section>

  <hr>

  <!-- PRODUCT DETAILS -->
  <section class="product-details">

  <div class="details-text">
    <p>
      <strong><?php echo htmlspecialchars($product['name']); ?></strong>
    </p>

    <p>
      Category:
      <?php echo htmlspecialchars($product['category']); ?>
    </p>

    <p>
      <?php echo nl2br(htmlspecialchars($product['description'])); ?>
    </p>
  </div>

  <div class="size-chart">
    <p class="size-title">Size Chart</p>

    <?php if (!empty($product['size_chart'])) { ?>
      <img
        src="../../uploads/<?php echo $product['size_chart']; ?>"
        alt="Size Chart"
        class="size-chart-img"
      >
    <?php } else { ?>
      <p style="font-size:14px;color:#888;">No size chart available</p>
    <?php } ?>
  </div>

  <div class="details-divider"></div>

</section>

<!-- RATINGS -->
<section class="ratings-section">
  <h3>RATINGS</h3>

  <div class="ratings-layout">

    <div class="rating-bars">
<?php for ($i = 5; $i >= 1; $i--): 
  $percent = ($totalReviews > 0)
    ? ($ratingCount[$i] / $totalReviews) * 100
    : 0;
?>
  <div class="rating-row">
    <span><?php echo $i; ?></span>
    <div class="bar">
      <div class="fill" style="width: <?php echo $percent; ?>%"></div>
    </div>
  </div>
<?php endfor; ?>
</div>

<!-- RATING SUMMARY -->
<div class="summary">
  <h2><?php echo $totalReviews > 0 ? $averageRating : '—'; ?></h2>

  <div class="stars">
    <?php
      if ($totalReviews > 0) {
        $rounded = floor($averageRating);
        for ($i = 1; $i <= 5; $i++) {
          echo $i <= $rounded ? '★' : '☆';
        }
      } else {
        echo '☆☆☆☆☆';
      }
    ?>
  </div>

  <p>
    <?php echo $totalReviews > 0 ? $totalReviews . ' Ratings' : 'No ratings yet'; ?>
  </p>
</div>

<!-- FEEDBACK -->
<section class="feedback-section">
  <h3>RECENT FEEDBACKS</h3>

  <?php if (empty($reviews)) { ?>
    <p style="color:#888; font-size:14px;">
      No reviews yet. Be the first to leave feedback.
    </p>
  <?php } ?>
  <?php foreach ($reviews as $r) { ?>

<div class="feedback">

  <div class="avatar">
    <?php if (!empty($r['user_image'])) { ?>
      <img src="<?php echo $r['user_image']; ?>" alt="User">
    <?php } else { ?>
      <img src="../PICTURE/profile.jpg" alt="Default">
    <?php } ?>
  </div>

  <div class="review-body">

    <div class="name-row">
      <strong><?php echo htmlspecialchars($r['user_name']); ?></strong>

      <div class="review-stars">
        <?php for ($i = 1; $i <= 5; $i++) { ?>
          <span class="<?php echo $i <= $r['rating'] ? 'active' : ''; ?>">★</span>
        <?php } ?>
      </div>
    </div>

    <p><?php echo htmlspecialchars($r['comment']); ?></p>

  </div>
</div>

  <?php } ?>
</section>
</div> 
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

<script>
const productData = {
  id: <?= $product['id']; ?>,
  brand: <?= json_encode($product['brand']); ?>,
  name: <?= json_encode($product['name']); ?>,
  image: <?= json_encode($product['image']); ?>
};

const variations = <?= json_encode($variations); ?>;
const cartEditIndex = <?= $cartIndex ?>;
</script>


  <script src="../JS/product-view.js"></script>

</body>
</html>
