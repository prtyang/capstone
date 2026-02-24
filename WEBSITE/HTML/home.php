<?php
$conn = new mysqli("localhost", "root", "", "capstone");
if ($conn->connect_error) {
    die("Database connection failed");
}

$images = [];
$result = $conn->query("SELECT image_key, image_path FROM site_images");

while ($row = $result->fetch_assoc()) {
    $images[$row['image_key']] = $row['image_path'];
}

//FOOTER
$settings = [];
$res = $conn->query("SELECT setting_key, setting_value FROM site_settings");

while ($row = $res->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// FEATURED PRODUCTS 
$featuredProducts = $conn->query("
    SELECT 
        p.id,
        p.name,
        p.brand,
        p.image,
        COALESCE(MIN(v.price), 0) AS min_price,
        COALESCE(MAX(v.price), 0) AS max_price
    FROM products p
    LEFT JOIN product_variations v ON v.product_id = p.id
    WHERE p.status = 'active'
    GROUP BY p.id
    ORDER BY p.id DESC
    LIMIT 4
");

// NORMAL PRODUCTS 
$homeProducts = $conn->query("
    SELECT 
        p.id,
        p.name,
        p.brand,
        p.image,
        COALESCE(MIN(v.price), 0) AS min_price,
        COALESCE(MAX(v.price), 0) AS max_price
    FROM products p
    LEFT JOIN product_variations v ON v.product_id = p.id
    WHERE p.status = 'active'
    GROUP BY p.id
    ORDER BY p.id DESC
    LIMIT 4 OFFSET 4
");

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forever | Shop</title>
  <link rel="stylesheet" href="../CSS/home.css">
</head>

<body>
<div class="page">


  <!-- HEADER -->
  <header class="header">

    <div class="logo-wrapper">
      <a href="index.html">
        <img src="../PICTURE/logo.png" class="logo" alt="Forever Logo">
      </a>
    </div>


    <!-- ICONS + NAV -->
    <div class="header-right">

      <div class="icons">

        <a href="wishlist.php" class="icon">
          <img src="../PICTURE/wishlist.png" class="logo" alt="WISHLIST Logo">
        </a>

        <a href="cart.php" class="icon">
          <img src="../PICTURE/cart.png" class="logo" alt="cart Logo">
        </a>
        
        <a href="login.html" class="icon" id="userIcon">
          <img src="../PICTURE/profile.jpg" class="logo" alt="profile logo">
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

  <!-- BREADCRUMB -->
<div class="breadcrumb">
  <a href="index.php">HOME</a>
  <span>/</span>
  <strong>SHOP</strong>
</div>

  <!--  HERO -->
  <section class="hero">
    <?php if (!empty($images['shop_main'])): ?>
      <img src="/CAPSTONE/<?= $images['shop_main'] ?>" alt="Shop Banner">
    <?php endif; ?>

  </section>


  <!-- CATEGORY -->
<section class="category-wrapper">

  <h2 class="category-title">CATEGORY</h2>

  <div class="category-section">

    <a href="shop.php?category=UNDERGARMENTS" class="category-card">
      <?php if (!empty($images['cat_1'])): ?>
        <img src="/CAPSTONE/<?= $images['cat_1'] ?>" alt="Undergarments">
      <?php endif; ?>
      <span>UNDERGARMENTS</span>
    </a>

    <a href="shop.php?category=INNERWEAR" class="category-card">
      <?php if (!empty($images['cat_2'])): ?>
        <img src="/CAPSTONE/<?= $images['cat_2'] ?>" alt="Innerwear">
      <?php endif; ?>
      <span>INNERWEAR</span>
    </a>

    <a href="shop.php?category=SLEEPWEAR" class="category-card">
      <?php if (!empty($images['cat_3'])): ?>
        <img src="/CAPSTONE/<?= $images['cat_3'] ?>" alt="Sleepwear">
      <?php endif; ?>
      <span>SLEEPWEAR</span>
    </a>

  </div>

</section>

  <!-- FEATURED PRODUCT -->
<section class="product-section">
  <h2 class="section-title">FEATURED PRODUCT</h2>

  <div class="product-grid">

    <?php while ($product = $featuredProducts->fetch_assoc()) { ?>

      <a href="product-view.php?id=<?php echo $product['id']; ?>" class="product-link">
        <div class="product-card" data-id="<?php echo $product['id']; ?>">
    
        <div class="heart" data-id="<?php echo $product['id']; ?>">
          ♡
        </div>

        <div class="image-box">
          <img src="/CAPSTONE/uploads/<?php echo $product['image']; ?>" alt="">
        </div>

        <div class="product-info">
          <h4><?php echo htmlspecialchars($product['brand']); ?></h4>
          <p><?php echo htmlspecialchars($product['name']); ?></p>

          <span class="price">
            <?php
              if ($product['min_price'] == $product['max_price']) {
              echo "₱" . number_format($product['min_price'], 2);
              } else {
              echo "₱" . number_format($product['min_price'], 2) . 
              " - ₱" . number_format($product['max_price'], 2);
              }
            ?>
          </span>    
        </div>
      </div>
      </a>
    <?php } ?>
  </div>
</section>


  <!-- PRODUCT -->
<section class="product-section">
  <h2 class="section-title">PRODUCT</h2>

  <div class="product-grid">

    <?php while ($product = $homeProducts->fetch_assoc()) { ?>

      <div class="product-card" data-id="<?php echo $product['id']; ?>">

        <a href="product-view.php?id=<?php echo $product['id']; ?>" class="product-link">

        <div class="heart" data-id="<?php echo $product['id']; ?>">
          ♥
        </div>

          <div class="image-box">
            <img src="/CAPSTONE/uploads/<?php echo $product['image']; ?>" alt="">
          </div>

          <div class="product-info">
            <h4><?php echo htmlspecialchars($product['brand']); ?></h4>
            <p><?php echo htmlspecialchars($product['name']); ?></p>

            <span class="price">
              <?php
                if ($product['min_price'] == $product['max_price']) {
                echo "₱" . number_format($product['min_price'], 2);
                } else {
                echo "₱" . number_format($product['min_price'], 2) . 
                " - ₱" . number_format($product['max_price'], 2);
                }
              ?>
            </span>
          </div>
        </a>
      </div>
    <?php } ?>
  </div>

  <!-- MORE LINK -->
  <div class="more-wrapper">
    <a href="shop.php" class="more-link">MORE</a>
  </div>

</section>

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

</div>
  <script src="../JS/home.js"></script>
</body>
</html>
