<?php
include(__DIR__ . "/../../config/db.php");

//FOOTER
$settings = [];
$res = $conn->query("SELECT setting_key, setting_value FROM site_settings");

while ($row = $res->fetch_assoc()) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forever | Cart</title>
    <link rel="stylesheet" href="../CSS/cart.css">
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
      <a href="Customer-service.php" class="icon"><img src="../PICTURE/Customer-services.png"></a>
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

<form action="checkout.php" method="POST">

<div class="cart-container">

  <!-- HEADER -->
  <div class="cart-header">
      <div></div>
      <div></div>
      <div>Product</div>
      <div>Price</div>
      <div>Qty</div>
      <div>Total</div>
      <div>Delete</div>
    </div>

    <!-- ITEM -->
    <div id="cartItems"></div>
  </div>

</div>

<!-- CART TOTAL -->
<div class="item-count">
    Items: <span id="itemCount">0</span>
</div>

<div class="cart-summary">
    <div class="grand-total">
        TOTAL: ₱<span id="grandTotal"></span>
    </div>

    <button type="submit" class="checkout-btn" id="checkoutBtn">
        Proceed To Checkout
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
<script src="../JS/cart.js"></script>

</body>
</html>
