<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Account Settings</title>
  <link rel="stylesheet" href="../CSS/profile.css">
</head>
<body>

<div class="container">

  <h2>Account Setting</h2>

  <!-- Profile Section -->
  <div class="section">
    <h3>Basic info</h3>

    <div class="profile">
      <div class="avatar">Camera</div>
      <div class="upload">
        <p>Upload new picture</p>
        <span class="remove">Remove</span>
      </div>
    </div>

    <div class="info">
      <div class="row"><span>Name:</span> Bea R. Son</div>
      <div class="row"><span>Date of Birth:</span> October 29, 2005</div>
      <div class="row"><span>Gender:</span> Female</div>
      <div class="row"><span>Email:</span> Bea.@gmail.com</div>
    </div>
  </div>

  <!-- Login Info -->
  <div class="section">
    <h3>Basic info</h3>

    <div class="info">
      <div class="row"><span>Username:</span> Bea Son</div>
      <div class="row"><span>Password:</span> Beason11</div>
    </div>
  </div>

  <!-- Orders -->
  <div class="orders">

   <div class="tabs">
  <span class="tab active">Order Place</span>
  <span class="tab">To ship</span>
  <span class="tab">Shipping</span>
  <span class="tab">Completed</span>
  <span class="tab">Cancel</span>
  <span class="tab">Return/Refund</span>
</div>


<div class="orders">

  <!-- BOX 1: PENDING -->
  <div class="order-card">
    <div class="order-header">
      <div class="tag">Expecting to ship within 24hrs</div>
      <div class="status pending">Pending</div>
    </div>

    <div class="product-row">
      <div class="img"></div>
      <div class="details">
        <p>Product description</p>
        <small>color</small><br>
        <small>size</small>
      </div>
      <div class="qty">x1</div>
      <div class="price">₱1</div>
    </div>

    <div class="product-row">
      <div class="img"></div>
      <div class="details">
        <p>Product description</p>
        <small>color</small><br>
        <small>size</small>
      </div>
      <div class="qty">x2</div>
      <div class="price">₱1</div>
    </div>

    <div class="total-row">
      <span>Total</span>
      <span>₱2</span>
    </div>
  </div>

  <!-- 🟢 BOX 2: COMPLETED -->
  <div class="order-card">
    <div class="order-header">
      <div class="tag">Delivered on March 02, 2026</div>
      <div class="status completed">Completed</div>
    </div>

    <div class="product-row">
      <div class="img"></div>
      <div class="details">
        <p>Product description</p>
        <small>color</small><br>
        <small>size</small>
      </div>
      <div class="qty">x1</div>
      <div class="price">₱1</div>
    </div>

    <div class="product-row">
      <div class="img"></div>
      <div class="details">
        <p>Product description</p>
        <small>color</small><br>
        <small>size</small>
      </div>
      <div class="qty">x2</div>
      <div class="price">₱1</div>
    </div>

    <div class="total-row">
      <span>Total</span>
      <span>₱2</span>
    </div>
  </div>

</div>

</div>
</div>
<script src="../JS/profile.js"></script>
</body>
</html>