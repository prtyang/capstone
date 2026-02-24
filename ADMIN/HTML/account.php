<?php
$conn = new mysqli("localhost", "root", "", "capstone");
if ($conn->connect_error) die("DB Error");

$images = [];
$result = $conn->query("SELECT image_key, image_path FROM site_images");

while ($row = $result->fetch_assoc()) {
  $images[$row['image_key']] = $row['image_path'];
}

$settings = [];
$res = $conn->query("SELECT setting_key, setting_value FROM site_settings");

while ($row = $res->fetch_assoc()) {
  $settings[$row['setting_key']] = $row['setting_value'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
    <title>Forever Admin</title>
    <link rel="stylesheet" href="..//CSS/account.css" />
</head>
<body>

    <div class="layout">

<!-- SIDEBAR -->

    <aside class="sidebar">
        <div class="logo">
            <img src="../PICTURE/logo.png">
        </div>

    <nav class="menu">
    <a href="dashboard.html" onclick="goPage('dashboard.html')">
        <img src="../PICTURE/home logo.png" class="menu-icon">
        Dashboard
    </a>

    <a href="product.php" onclick="goPage('product.php')">
        <img src="../PICTURE/product logo.png" class="menu-icon">
        Product
    </a>

    <a href="order.php" onclick="goPage('order.php')">
        <img src="../PICTURE/ORDER LOGO.webp" class="menu-icon">
        Order
    </a>

    <a href="sales.html" onclick="goPage('sales.html')">
        <img src="../PICTURE/SALES LOGO.png" class="menu-icon">
        Sales
    </a>

    <a href="marketing.html" onclick="goPage('marketing.html')">
        <img src="../PICTURE/MARKETING LOGO.png" class="menu-icon">
        Marketing
    </a>

    <a href="account.php" class="active" onclick="goPage('account.php')">
        <img src="../PICTURE/ACCOUNT LOGO.png" class="menu-icon">
        Account
</a>

</nav>

    <div class="logout-bar">
    <div class="logout-content">
        <span class="logout-text">LOG OUT</span>
    </div>
    </div>
    
    </aside>

<!-- Main -->
<main class="content">
    <form action="save_images.php" method="POST" enctype="multipart/form-data">

    <h2>Main Image (HOME)</h2>
    <div class="upload-box" id="homeBox" data-current="<?= $images['home_main'] ?? '' ?>">
        <?php if (!empty($images['home_main'])): ?>
            <img src="/CAPSTONE/<?= $images['home_main'] ?>">
        <?php else: ?>
            Upload Main Image Home
        <?php endif; ?>
    </div>
        <input type="file" id="homeInput" name="home_main" hidden>

    <h2>Main Image (SHOP)</h2>
    <div class="upload-box" id="shopBox" data-current="<?= $images['shop_main'] ?? '' ?>">
            <?php if (!empty($images['shop_main'])): ?>
                <img src="/CAPSTONE/<?= $images['shop_main'] ?>">
            <?php else: ?>
                Upload Main Image Shop
            <?php endif; ?>
    </div>
        <input type="file" id="shopInput" name="shop_main" hidden>


    <div class="upload-section">
        <h2>Category (Undergarments, Innerwear, Sleepwear)</h2>

    <div class="category-grid">
        <div class="upload-box small" id="cat1" data-current="<?= $images['cat_1'] ?? '' ?>">
            <?php if (!empty($images['cat_1'])): ?>
                <img src="/CAPSTONE/<?= $images['cat_1'] ?>">
            <?php else: ?>
                Upload Photo
            <?php endif; ?>
        </div>
            <input type="file" id="catInput1" name="cat_1" hidden>

        <div class="upload-box small" id="cat2" data-current="<?= $images['cat_2'] ?? '' ?>">
            <?php if (!empty($images['cat_2'])): ?>
                <img src="/CAPSTONE/<?= $images['cat_2'] ?>">
            <?php else: ?>
                Upload Photo
            <?php endif; ?>
        </div>
                <input type="file" id="catInput2" name="cat_2" hidden>

            <div class="upload-box small" id="cat3" data-current="<?= $images['cat_3'] ?? '' ?>">
                <?php if (!empty($images['cat_3'])): ?>
                    <img src="/CAPSTONE/<?= $images['cat_3'] ?>">
                <?php else: ?>
                    Upload Photo
                <?php endif; ?>
            </div>
                <input type="file" id="catInput3" name="cat_3" hidden>

        </div>
    </div>

    <h2>Shop Name</h2>
        <input
            type="text"
            value="Intimate Forever"
            readonly
            class="readonly-input"
        />

    <h2>Footer Contact</h2>
        <input
            type="text"
            name="footer_phone"
            placeholder="Contact #"
            value="<?= htmlspecialchars($settings['footer_phone'] ?? '') ?>"
        >

        <input
            type="text"
            name="footer_address"
            placeholder="Address"
            value="<?= htmlspecialchars($settings['footer_address'] ?? '') ?>"
        >

        <input
            type="email"
            name="footer_email"
            placeholder="Email"
            value="<?= htmlspecialchars($settings['footer_email'] ?? '') ?>"
        >

      <h2>Email (Username)</h2>
      <input type="email" />

      <h2>Password</h2>
      <input type="password" />

    <button class="update" type="submit">Update</button>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div id="successToast" class="toast success">
    Updated successfully
    </div>
    <?php endif; ?>

    </form>
</main>
</div>


<script src="../JS/account.js"></script>

</body>
</html>
