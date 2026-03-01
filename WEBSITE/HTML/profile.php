
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <title>Account Settings</title>
  <link rel="stylesheet" href="../CSS/profile.css">
</head>
<body>
<div class="container">
  <div class="breadcrumb">
    <span class="back-btn" onclick="goBack()">
      <i class="fa-solid fa-arrow-left"></i> Back
    </span>
    <span class="divider">/</span>
    <span class="current">Account Setting</span>
  </div>
  
<div class="container">
  <div class="header">
  <h2>Account Setting</h2>
  <button class="save-btn">Save</button>
</div>

  <!-- Profile Section -->
  <div class="section">
    <h3>Basic info</h3>

<div class="profile">
  
  <!-- hidden file input -->
  <input type="file" id="fileInput" accept="image/*" hidden>

  <!-- clickable circle -->
  <label for="fileInput" class="avatar">
    <img id="profileImg" src="" alt="">
    <span id="placeholder">Camera</span>
  </label>

  <div class="upload">
    <p>Upload New Image </p>
  </div>

</div>

<div class="info">
  <div class="row">
    <span>Name:</span>
    <input type="text" id="name" placeholder="Enter your name">
  </div>

  <div class="row">
    <span>Date of Birth:</span>
    <input type="date" id="dob">
  </div>

  <div class="row">
    <span>Gender:</span>
    <select id="gender">
      <option value="" disabled selected>Select gender</option>
      <option>Female</option>
      <option>Male</option>
    </select>
  </div>

  <div class="row">
    <span>Email:</span>
    <input type="email" id="email" placeholder="Enter your email">
  </div>
</div>

  <!-- Login Info -->
  <div class="section">
    <h3>Basic info</h3>

<div class="info">
  <div class="row">
    <span>Username:</span>
    <input type="text" id="username" placeholder="Enter username">
  </div>

<div class="row password-row">
  <span>Password:</span>

  <div class="password-container">
    <input type="password" id="password" placeholder="Enter password">
    <i class="fa-solid fa-eye" id="togglePassword"></i>
  </div>
</div>

  <!-- Orders -->
  <div class="orders">

<div class="tabs">
  <span class="tab active" data-tab="orderplace">Order Place</span>
  <span class="tab" data-tab="toship">To ship</span>
  <span class="tab" data-tab="shipping">Shipping</span>
  <span class="tab" data-tab="completed">Completed</span>
  <span class="tab" data-tab="cancel">Cancel</span>
  <span class="tab" data-tab="refund">Return/Refund</span>
</div>

<div id="ordersContainer"></div>

</div>
</div>
<script src="../JS/profile.js"></script>

<div id="reviewModal" class="modal">
  <div class="modal-content">

    <h2>Review</h2>

    <p>Ratings</p>
    <div class="stars" id="starRating">
      <i class="fa-solid fa-star" data-value="1"></i>
      <i class="fa-solid fa-star" data-value="2"></i>
      <i class="fa-solid fa-star" data-value="3"></i>
      <i class="fa-solid fa-star" data-value="4"></i>
      <i class="fa-solid fa-star" data-value="5"></i>
  </div>

    <p>Comment</p>
    <textarea id="reviewComment"></textarea>
    
<div class="modal-actions">
<button onclick="submitReview()">
  Submit Review
</button>
  <button onclick="closeModal()">Cancel</button>
</div>

  </div>
</div>

</body>
</html>