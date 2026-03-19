<?php
session_start(); 
$conn = new mysqli("localhost", "root", "", "capstone");
if ($conn->connect_error) die("DB Error");

$admin = $conn->query("SELECT * FROM admins LIMIT 1")->fetch_assoc();

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

$currentPIN = $settings['pin_action'] ?? '0000';

$oldWithdrawPin = $_SESSION['oldWithdrawPin'] ?? '';
$oldPinInput = $_SESSION['oldPinInput'] ?? '';

// CHECK LOCK (24 HOURS)
if (isset($_SESSION['lock_time'])) {
    $diff = time() - $_SESSION['lock_time'];

    if ($diff < 60) { // 60 seconds = 1 minute
        $_SESSION['error'] = "Too many wrong attempts. Try again after 24 hours.";
    } else {
        unset($_SESSION['lock_time']);
        $_SESSION['attempts'] = 0;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $enteredPIN = $_POST['confirmPIN'] ?? '';

    // WRONG PIN
    if ($enteredPIN !== $currentPIN) {

        $_SESSION['error'] = "Wrong PIN!";
        $_SESSION['attempts'] = ($_SESSION['attempts'] ?? 0) + 1;

        if ($_SESSION['attempts'] >= 5) {
            $_SESSION['lock_time'] = time();
        }

    } else {

// CORRECT PIN
$_SESSION['attempts'] = 0;

// UPDATE ADMIN EMAIL + PASSWORD
$newEmail = $_POST['admin_email'] ?? '';
$newPassword = $_POST['admin_password'] ?? '';

if ($newEmail !== '') {

    if ($newPassword !== '') {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE admins SET email=?, password=? LIMIT 1");
        $stmt->bind_param("ss", $newEmail, $hashed);
        $stmt->execute();

    } else {
        $stmt = $conn->prepare("UPDATE admins SET email=? LIMIT 1");
        $stmt->bind_param("s", $newEmail);
        $stmt->execute();
    }
}

 // SAVE PIN TO DATABASE 
$pinWithdraw = $_POST['pin_withdraw'] ?? '';
$pinAction   = $_POST['pin_action'] ?? '';

// SAVE WITHDRAW PIN
if ($pinWithdraw !== '') {
    $stmt1 = $conn->prepare("
        INSERT INTO site_settings (setting_key, setting_value)
        VALUES ('pin_withdraw', ?)
        ON DUPLICATE KEY UPDATE setting_value = ?
    ");
    $stmt1->bind_param("ss", $pinWithdraw, $pinWithdraw);
    $stmt1->execute();
}

// SAVE ACTION PIN
if ($pinAction !== '') {
    $stmt2 = $conn->prepare("
        INSERT INTO site_settings (setting_key, setting_value)
        VALUES ('pin_action', ?)
        ON DUPLICATE KEY UPDATE setting_value = ?
    ");
    $stmt2->bind_param("ss", $pinAction, $pinAction);
    $stmt2->execute();
}

// UPDATE LOCAL VALUES (IMPORTANT)
$settings['pin_withdraw'] = $pinWithdraw;
$settings['pin_action']   = $pinAction;

// IMPORTANT: update current settings immediately
$settings['pin_withdraw'] = $pinWithdraw;

// KEEP SESSION (for display only)
$_SESSION['oldPinInput'] = $_POST['pin_action'] ?? '';
$_SESSION['oldWithdrawPin'] = $pinWithdraw;

$_SESSION['success'] = true;

//  KEEP PASSWORD IN SESSION ALWAYS
if (!empty($_POST['admin_password'])) {
    $_SESSION['temp_password'] = $_POST['admin_password'];
}
}
}

//LOGOUT
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
    <title>Forever Admin Account</title>
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
    <a href="dashboard.php">
        <img src="../PICTURE/home logo.png" class="menu-icon">
        Dashboard
    </a>

    <a href="product.php">
        <img src="../PICTURE/product logo.png" class="menu-icon">
        Product
    </a>

    <a href="order.php">
        <img src="../PICTURE/ORDER LOGO.webp" class="menu-icon">
        Order
    </a>

    <a href="sales.php" >
        <img src="../PICTURE/SALES LOGO.png" class="menu-icon">
        Sales
    </a>

    <a href="marketing.php" >
        <img src="../PICTURE/MARKETING LOGO.png" class="menu-icon">
        Marketing
    </a>

    <a href="account.php" class="active" >
        <img src="../PICTURE/ACCOUNT LOGO.png" class="menu-icon">
        Account
</a>

</nav>

    <a href="logout.php" class="logout-bar">
        <div class="logout-content">
            <span class="logout-text">LOG OUT</span>
        </div>
    </a>
    
    </aside>

<!-- Main -->
<main class="content">
    <form id="accountForm" action="account.php" method="POST" enctype="multipart/form-data">

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
        <input 
            type="email" 
            name="admin_email"
            value="<?= htmlspecialchars($admin['email'] ?? '') ?>"
            required
        >

        <div class="pin-wrapper">
        <h2>Password</h2>

        <input 
            type="password" 
            id="adminPassword"
            name="admin_password"
            placeholder="Enter new password"
        >
        <span class="toggle-eye" onclick="toggleInput('adminPassword','eyeOpenAdmin','eyeClosedAdmin')">

        <!-- OPEN EYE -->
        <svg id="eyeOpenAdmin" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="#c94a7c" stroke-width="2" viewBox="0 0 24 24">
            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
            <circle cx="12" cy="12" r="3"/>
        </svg>

        <!-- CLOSED EYE -->
        <svg id="eyeClosedAdmin" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="#c94a7c" stroke-width="2" viewBox="0 0 24 24" style="display:none;">
            <path d="M17.94 17.94A10.94 10.94 0 0 1 12 19C5 19 1 12 1 12a21.77 21.77 0 0 1 5.06-6.94"/>
            <path d="M1 1l22 22"/>
        </svg>

        </span>
    </div>
    
    <h2>PIN For Withdrawal</h2>
        <div class="pin-wrapper">
            <input 
                type="password" 
                id="pinWithdraw" 
                name="pin_withdraw"
                value="<?= htmlspecialchars($settings['pin_withdraw'] ?? '') ?>"
                maxlength="4"
                placeholder="PIN for Withdrawal"
            >
        <span class="toggle-eye" onclick="toggleInput('pinWithdraw','eyeOpenWithdraw','eyeClosedWithdraw')">

        <!--  OPEN EYE -->
        <svg id="eyeOpenWithdraw" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="#c94a7c" stroke-width="2" viewBox="0 0 24 24">
            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
            <circle cx="12" cy="12" r="3"/>
        </svg>

        <!-- CLOSED EYE -->
        <svg id="eyeClosedWithdraw" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="#c94a7c" stroke-width="2" viewBox="0 0 24 24" style="display:none;">
            <path d="M17.94 17.94A10.94 10.94 0 0 1 12 19C5 19 1 12 1 12a21.77 21.77 0 0 1 5.06-6.94"/>
            <path d="M1 1l22 22"/>
        </svg>

        </span>
    </div>

    <h2>PIN For Action(Cancel Order, Delete Product, Update)</h2>
    <div class="pin-wrapper">
        <input 
            type="password" 
            id="pinAction" 
            name="pin_action"
            value="<?= htmlspecialchars($settings['pin_action'] ?? '') ?>"
            maxlength="4"
            placeholder="Enter PIN"
        >
    <span class="toggle-eye" onclick="toggleInput('pinAction','eyeOpen','eyeClosed')">

    <!-- OPEN EYE -->
    <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="#c94a7c" stroke-width="2" viewBox="0 0 24 24">
        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
        <circle cx="12" cy="12" r="3"/>
    </svg>

    <!-- CLOSED EYE -->
    <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="#c94a7c" stroke-width="2" viewBox="0 0 24 24" style="display:none;">
        <path d="M17.94 17.94A10.94 10.94 0 0 1 12 19C5 19 1 12 1 12a21.77 21.77 0 0 1 5.06-6.94"/>
        <path d="M1 1l22 22"/>
    </svg>
    </span>
</div>
        
<button class="update" type="button" onclick="showPIN()">Update</button>

<!-- HIDDEN PIN SECTION -->
<div id="pinSection" style="display:none; margin-top:15px;">
    <input type="password" id="confirmPIN" name="confirmPIN" maxlength="4" placeholder="Enter Current PIN">
    <button type="button" onclick="submitWithPIN()">Confirm PIN</button>
    <p id="pinMessage"></p>

</div>

<?php if (isset($_SESSION['success'])): ?>
<div id="successToast" class="toast success">
    Updated successfully
</div>
<?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
<div class="toast" style="background:#ffd6d6;color:red;">
    <?= $_SESSION['error'] ?>
</div>
<?php unset($_SESSION['error']); ?>
<?php endif; ?>

    </form>
</main>
</div>

<script src="../JS/account.js"></script>

</body>
</html>
