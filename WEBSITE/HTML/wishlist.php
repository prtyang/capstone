<?php
include(__DIR__ . "/../../config/db.php");

// FOOTER
$conn = new mysqli("localhost", "root", "", "capstone");

$settings = [];
$res = $conn->query("SELECT setting_key, setting_value FROM site_settings");

while ($row = $res->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Forever | wishlist</title>
    <link rel="stylesheet" href="../CSS/wishlist.css">
</head>
<body>

<!-- HEADER -->
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

<!-- CART FORM -->
<form action="cart.php" method="POST">

  <div class="cart-container">
    <div class="cart-header">
        <div></div>
        <div></div>
        <div class="header-product">Product</div>
        <div class="header-price">Price</div>
        <div class="header-delete">Delete</div>
        <div></div>
    </div>

    <div id="wishlist-container"></div>
  </div>

  <div class="button-area">
    <button type="button" id="deleteSelected" class="del-btn">
      DELETE ALL
    </button>

  </div>
</form>

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
  <script src="../JS/wishlist.js"></script>

</body>
</html>
