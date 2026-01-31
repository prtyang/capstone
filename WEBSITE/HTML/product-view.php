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

/* LOAD SIZES FROM VARIATIONS */
$sizes = [];

$sizeRes = $conn->query("
  SELECT DISTINCT size
  FROM product_variations
  WHERE product_id = $id
  AND size <> ''
  ORDER BY size ASC
");

while ($row = $sizeRes->fetch_assoc()) {
  $sizes[] = $row['size'];
}


/* LOAD REVIEWS */
$reviews = [];
$reviewRes = $conn->query("
  SELECT user_name, rating, comment
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
        <a href="cart.html" class="icon">
          <img src="../PICTURE/cart.png" alt="Cart">
        </a>

        <a href="wishlist.html" class="icon">
          <img src="../PICTURE/wishlist.png" alt="Wishlist">
        </a>

        <a href="login.html" class="icon" id="userIcon">
          <img src="../PICTURE/profile.jpg" alt="Profile">
        </a>
      </div>

      <nav class="nav">
        <a href="index.html">HOME</a>
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

<p class="description">
  <?php echo htmlspecialchars($product['description']); ?>
</p>

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

<!-- STAR RATING -->
<?php foreach ($reviews as $r) { ?>
  <div class="stars">
    <?php for ($i = 1; $i <= 5; $i++) { ?>
      <span class="<?php echo $i <= $r['rating'] ? 'active' : ''; ?>">★</span>
    <?php } ?>
  </div>
<?php } ?>

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
        title="<?php echo htmlspecialchars($c); ?>"
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
      <a href="#" class="btn outline">ADD TO BASKET</a>
      <a href="#" class="btn solid">CHECKOUT</a>
    </div>
  </div>

  </section>

  <hr>

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
  <div class="avatar"></div>

  <div class="review-content">
    <strong><?php echo htmlspecialchars($r['user_name']); ?></strong>
    <p><?php echo htmlspecialchars($r['comment']); ?></p>
  </div>

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

<!-- FOOTER -->
<div class="footer-wrapper">
  <div class="divider-line"></div>

  <footer class="footer">

    <h2>LET’S CONNECT!</h2>

    <div class="socials">
      <a href="https://web.facebook.com/intimateforeverph" target="_blank">
        <img src="../PICTURE/FB.png" alt="Facebook">
      </a>

      <a href="https://www.instagram.com/intimateforeverph/" target="_blank">
        <img src="../PICTURE/IG.png" alt="Instagram">
      </a>

      <a href="https://www.tiktok.com/@intimateforeverph?lang=en" target="_blank">
        <img src="../PICTURE/TIKTOK.png" alt="TikTok">
      </a>

      <a href="https://shopee.ph/forever_ph?categoryId=100017&entryPoint=ShopByPDP&itemId=12634684993&upstream=search" target="_blank">
        <img src="../PICTURE/SHOPEE.png" alt="Shopee">
      </a>

      <a href="https://www.lazada.com.ph/shop/intimateforever?spm=a211g0.store_hp.top.share&dsource=share&laz_share_info=2386099838_0_7900_500672016194_2386101838_null&laz_token=2b79ba534c50bd531f3a3908bdbdd40f&exlaz=e_1utFWoJ%2B51jGip8qo24MCZFmNCmP6gU%2FnnWa525VocjCXvTKdNAtBbVs1%2B5q2wp%2BGS5d%2BCcBXmHn7bOPEPY8UzkCKkVzibKGD3WnASIas1Y%3D&sub_aff_id=social_share&sub_id2=2386099838&sub_id3=500672016194&sub_id6=CPI_EXLAZ"
        target="_blank">
        <img src="../PICTURE/LAZADA.webp" alt="Lazada">
      </a>
    </div>

    <p>
      64 J.P Bautista Caloocan, Caloocan, Philippines<br>
      0939 819 6120<br>
      <a href="mailto:intimateforevergarments@gmail.com">
        intimateforevergarments@gmail.com
      </a>
    </p>

    <div class="footer-links">
      <a href="#">Terms of Service</a>
      <span>|</span>
      <a href="#">Privacy Policy</a>
      <span>|</span>
      <a href="#">Refund Policy</a>

    <p class="footer-copy">
      © 2026 Capstone Project. All rights reserved.
    </p>

  </footer>

  <script src="../JS/product-view.js"></script>

</div>
</div> 
<script>
  const variations = <?php echo json_encode($variations); ?>;
</script>

</body>
</html>
