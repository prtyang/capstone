<?php
session_start();
$conn = new mysqli("localhost", "root", "", "capstone");
if ($conn->connect_error) {
    die("Database connection failed");
}

//FOOTER
$settings = [];
$res = $conn->query("SELECT setting_key, setting_value FROM site_settings");
while ($row = $res->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

$homeImage = "";
$result = $conn->query(
    "SELECT image_path FROM site_images WHERE image_key = 'home_main'"
);

if ($result && $row = $result->fetch_assoc()) {
    $homeImage = $row['image_path'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forever | Home</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../CSS/index.css">
  
</head>

<body>
  <div class="page">

  <header class="header">
    <div class="logo-wrapper">
        <img src="../PICTURE/logo.png" class="logo">
    </div>

    <!-- NAV -->
<nav class="nav">
  <a href="index.php">HOME</a>
  <a href="home.php">SHOP</a>
  <a href="try-on.html">TRY-ON</a>

  <?php if(isset($_SESSION['user'])): ?>
      <a href="logout.php">LOGOUT</a>
  <?php else: ?>
      <a href="login.php">LOGIN</a>
  <?php endif; ?>
</nav>

  </header>

  <section class="hero">
    <?php if (!empty($homeImage)): ?>
      <img src="/CAPSTONE/<?= $homeImage ?>" alt="Home Banner">
    <?php endif; ?>
  </section>

  <!-- LIVE STRIP -->
  <section class="live-strip">
    <div class="forever-line">
      FOREVER &nbsp; FOREVER &nbsp; FOREVER &nbsp; FOREVER &nbsp;
      FOREVER &nbsp; FOREVER &nbsp; FOREVER &nbsp; FOREVER
    </div>
  </section>

  <section class="section live-selling-section">

    <div class="left">
      <img src="../PICTURE/live selling.webp" alt="Live Selling">
    </div>

    <div class="right text">
      <div class="text-box">
        <p>
          <strong>FOREVER</strong> is a fashion retail company specializing in
          undergarments and sleepwear for females, from teens to adults.
        </p>
      </div>
    </div>

  </section>

  <!-- SIZE & POLICY  -->
  <section class="size-policy-section">
    <div class="pink-board">

      <img src="../PICTURE/PIN.webp" class="pin-img" alt="Pin">
      <img src="../PICTURE/size.webp" class="size-card-img" alt="Size Reminder">
      <img src="../PICTURE/policy.webp" class="policy-card-img" alt="Policy">

    </div>
  </section>

  <!--  STORY + FREEBIES -->
  <section class="story-freebies">

    <div class="story-left">
      <div class="story-box">
        <p>
          <strong>FOREVER</strong> started out in 1997 as a manufacturer of expertly crafted garments
          and is now one of the major players in the world of intimate apparel.
        </p>
      </div>
    </div>

    <div class="story-right">
      <img src="../PICTURE/freebies.webp" alt="Forever Freebies">
    </div>

  </section>

  <section class="headline-section">
    <p class="headline">
      <strong>FOREVER</strong> will be the fashion solution for every Filipina who wants<br>
      to look and feel her best, inside and out.
    </p>
  </section>

  <!-- PRODUCTS  -->
  <section class="triple-products">

    <div class="product-card">
      <img src="../PICTURE/panty.jpg" alt="Panty">
    </div>

    <div class="product-card center-card">
      <img src="../PICTURE/bra.jpg" alt="Bra">
    </div>

    <div class="product-card">
      <img src="../PICTURE/pajama.jpg" alt="Pajama">
    </div>

  </section>

  <section class="tryon-section">

    <!-- LEFT: AR IMAGE -->
    <div class="tryon-left">
      <div class="ar-frame">
        <img src="../PICTURE/TRYON.jpeg" alt="Try On Model" class="ar-image">

        <div class="ar-title">TRY ON & CREATE AN AVATAR</div>

      </div>
    </div>

    <!-- RIGHT: TEXT -->
    <div class="tryon-text-box">
      <p>
        Experience shopping in a whole new way with Forever Brand’s newest AR feature.
        See how the product looks in real time, explore the details, and choose with
        confidence — no guessing needed.
      </p>


      <button class="try-now" id="tryNowBtn">TRY NOW!</button>
      <a href="try-on.html" class="try-now-link"></a>
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
  <button id="backToTop" aria-label="Back to top">↑</button>

  <script src="../JS/index.js"></script>
</body>
</html>
