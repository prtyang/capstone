<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Forever | Checkout</title>
<link rel="stylesheet" href="../CSS/checkout.css">
</head>

<body>

<div class="page">

<!-- HEADER -->
<header class="header">
  <div class="logo-wrapper">
    <a href="index.html">
      <img src="../PICTURE/logo.png" class="logo">
    </a>
  </div>

  <div class="header-right">
    <div class="icons">
      <a href="wishlist.php" class="icon">
        <img src="../PICTURE/wishlist.png">
      </a>

      <a href="cart.php" class="icon">
        <img src="../PICTURE/cart.png">
      </a>

      <a href="Customer-service.php" class="icon">
        <img src="../PICTURE/Customer-services.png">
      </a>
      
      <a href="login.html" class="icon">
        <img src="../PICTURE/profile.jpg">
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

<div class="checkout-wrapper">
<div class="checkout-container">
<div class="checkout">

<!-- CONTACT -->
<div class="box section">
<h2>Contact Information</h2>

<div class="row">
  <input placeholder="First Name">
  <input placeholder="Last Name">
</div>

<input type="email" placeholder="Email Address">
<input type="tel" id="phoneInput" placeholder="Phone Number">

<label class="checkbox">
  <input type="checkbox"> Save all information
</label>
</div>

<!-- SHIPPING -->
<div class="box section">
<h2>Shipping Information</h2>

<div class="custom-select">
  <input id="provinceInput" name="province" placeholder="Select Province">
  <div id="provinceList" class="dropdown"></div>
</div>

<div class="custom-select">
  <input id="cityInput" name="city" placeholder="Select City">
  <div id="cityList" class="dropdown"></div>
</div>

<div class="custom-select">
  <input id="brgyInput" name="barangay" placeholder="Select Barangay">
  <div id="brgyList" class="dropdown"></div>
</div>

<input id="postalInput" name="postal" placeholder="Postal Code">
<input id="fullAddressInput" name="full_address" placeholder="Full address">

<label class="checkbox">
  <input type="checkbox"> Save all information
</label>
</div>

<!-- PAYMENT -->
<div class="box section">
<h2>Payment Method</h2>

<div class="radio-group">
  <label class="radio-option">
    <span>COD</span>
    <input type="radio" name="payment">
  </label>

  <label class="radio-option">
    <span>GCash</span>
    <input type="radio" name="payment">
  </label>

  <label class="radio-option">
    <span>Card</span>
    <input type="radio" name="payment">
  </label>
</div>

<div class="card-fields">
  <input placeholder="Card Number">
  <input placeholder="Cardholder Name">
  <div class="row">
    <input placeholder="Expiry Date">
    <input placeholder="CVV">
  </div>
</div>
</div>

<!-- DELIVERY -->
<div class="box section">
<h2>Delivery Method</h2>

<div class="radio-group">
  <label class="radio-option">
    <span>Standard Delivery  (4-7days)</span>
    <input type="radio" name="delivery">
  </label>

  <label class="radio-option">
    <span>Express Delivery (2 - 3days)</span>
    <input type="radio" name="delivery">
  </label>
</div>
</div>

<!-- SUMMARY -->
<div class="box">
<h2>Order Summary</h2>

<div id="orderItem"></div>

<input id="couponInput" placeholder="Coupon code">

<div class="summary-line">
  <span>Items</span>
  <span id="itemCount"></span>
</div>

<div class="summary-line">
  <span>Subtotal</span>
  <span id="subtotal"></span>
</div>

<div class="summary-line">
  <span>Delivery</span>
  <span id="delivery"></span>
</div>

<div class="summary-line">
  <strong>Total</strong>
  <strong id="total"></strong>
</div>

<button id="payBtn" class="checkout-btn">Checkout</button>

</div>

<script src="../JS/checkout.js"></script>
</body>
</html>
