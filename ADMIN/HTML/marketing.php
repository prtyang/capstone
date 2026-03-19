<?php
session_start();
include "../../config/db.php";

// PROTECT PAGE
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

/* AUTO UPDATE PROMOTION STATUS */

$conn->query("
UPDATE promotions 
SET status='active'
WHERE start_date <= CURDATE() 
AND end_date >= CURDATE()
");

$conn->query("
UPDATE promotions 
SET status='inactive'
WHERE end_date < CURDATE()
");

// delete related products first
$conn->query("
DELETE pp FROM promotion_products pp
JOIN promotions p ON pp.promotion_id = p.id
WHERE p.end_date <= DATE_SUB(CURDATE(), INTERVAL 3 DAY)
");

// then delete promotions
$conn->query("
DELETE FROM promotions
WHERE end_date <= DATE_SUB(CURDATE(), INTERVAL 3 DAY)
");

/* AUTO UPDATE PROMOTION STATUS */

$conn->query("
UPDATE promotions 
SET status='active'
WHERE start_date <= CURDATE() 
AND end_date >= CURDATE()
");

$conn->query("
UPDATE promotions 
SET status='inactive'
WHERE end_date < CURDATE()
");


if($_SERVER["REQUEST_METHOD"] == "POST"){

$name = $_POST['voucher_name'];
$code = $_POST['voucher_code'];
$less = $_POST['voucher_less'];

$stmt = $conn->prepare("INSERT INTO vouchers 
(voucher_name,voucher_code,voucher_less)
VALUES (?,?,?)");

$stmt->bind_param("ssi",$name,$code,$less);
$stmt->execute();


header("Location: marketing.php");
exit();
}

/* ACTIVE VOUCHERS */
$activeQuery = "SELECT COUNT(*) AS total FROM vouchers WHERE status='Active'";
$activeResult = $conn->query($activeQuery);
$activeRow = $activeResult->fetch_assoc();
$activeVouchers = $activeRow['total'];

/* CLAIMED VOUCHERS */
$claimedQuery = "SELECT COUNT(id) AS total FROM vouchers";
$claimedResult = $conn->query($claimedQuery);
$claimedRow = $claimedResult->fetch_assoc();
$claimed = $claimedRow['total'];

/* ACTIVE PROMOTIONS */
$activePromoQuery = "SELECT COUNT(*) AS total FROM promotions WHERE status='active'";

$activePromoResult = $conn->query($activePromoQuery);
$activePromoRow = $activePromoResult->fetch_assoc();
$activePromotions = $activePromoRow['total'] ?? 0;

/* TOTAL DISCOUNT PROMOTIONS */
$promoCountQuery = "SELECT COUNT(id) AS total FROM promotions";
$promoCountResult = $conn->query($promoCountQuery);
$promoCountRow = $promoCountResult->fetch_assoc();
$promotionCount = $promoCountRow['total'];

/* TOTAL VOUCHERS */
$totalVoucherQuery = "SELECT COUNT(*) AS total FROM vouchers";
$totalVoucherResult = $conn->query($totalVoucherQuery);
$totalVoucherRow = $totalVoucherResult->fetch_assoc();
$totalVouchers = $totalVoucherRow['total'];

/* ACTIVE VOUCHERS */
$activeVoucherQuery = "SELECT COUNT(*) AS total FROM vouchers WHERE status='Active'";
$activeVoucherResult = $conn->query($activeVoucherQuery);
$activeVoucherRow = $activeVoucherResult->fetch_assoc();
$activeVouchers = $activeVoucherRow['total'];

/* TOTAL LESS AMOUNT */
$totalLessQuery = "SELECT SUM(voucher_less) AS total FROM vouchers";
$totalLessResult = $conn->query($totalLessQuery);
$totalLessRow = $totalLessResult->fetch_assoc();
$totalLess = $totalLessRow['total'] ?? 0;

//LOGOUT
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Marketing Voucher Dashboard</title>
<link rel="stylesheet" href="../CSS/marketing.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

<div class="container">

    <!-- SIDEBAR -->

    <aside class="sidebar">
        <div class="logo">
            <img src="../PICTURE/logo.png">
        </div>

    <nav class="menu">
    <a href="dashboard.php" >
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

    <a href="sales.php">
        <img src="../PICTURE/SALES LOGO.png" class="menu-icon">
        Sales
    </a>

    <a href="marketing.php"  class="active">
        <img src="../PICTURE/MARKETING LOGO.png" class="menu-icon">
        Marketing
    </a>

    <a href="account.php" >
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

    <main class="main">

<!-- Top Cards -->
 <div class="top-wrapper">

    <div class="top-header">

<button class="create-discount" onclick="window.location.href='voucher.php'">
    <span class="plus">+</span>
    Create Discount
</button>

</div>

<!-- Cards Row -->
<div class="promo-container">

    <div class="promo-card">
        <div class="card-top">

            <div class="icon-box">
                <i class="fa-solid fa-bullhorn"></i>
            </div>

            <h3>Discount Promotion</h3>

        </div>

        <div class="card-bottom">
            <span class="number"><?php echo $activePromotions; ?></span>
            <span class="label">Active</span>
        </div>
    </div>


    <div class="promo-card">
        <div class="card-top">

            <div class="icon-box">
                <i class="fa-solid fa-ticket"></i>
            </div>

            <h3>Voucher Codes</h3>

        </div>

        <div class="card-bottom">
            <span class="number"><?php echo $totalVouchers; ?></span>
            <span class="label">Active</span>
        </div>
    </div>


    <div class="promo-card">
        <div class="card-top">

            <div class="icon-box">
                <i class="fa-solid fa-tags"></i>
            </div>

            <h3>Total Less Voucher</h3>

        </div>

        <div class="card-bottom">
            <span class="number">₱<?php echo $totalLess; ?></span>
        </div>
    </div>

</div>
<div class="voucher-section">

<!-- Create Voucher -->
<div class="create-voucher">
    <h2>Create Voucher</h2>

    <form action="" method="POST">

        <input class="full-input" type="text" name="voucher_name" placeholder="Voucher Name" required>
        <input class="full-input" type="text" name="voucher_code" placeholder="Voucher Code" required>

    <div class="peso-input">
        <span>₱</span>
        <input type="number" name="voucher_less" placeholder="Less">
    </div>

    <button class="create-btn" type="submit">
    CREATE
    </button>

</form>
</div>

<!-- Voucher Usage -->
<div class="voucher-usage">
    <h2>Voucher Usage</h2>
    <div class="usage-line">Active Voucher: <?php echo $activeVouchers; ?></div>
    <div class="usage-line">Voucher Count: <?php echo $claimed; ?></div>
    <div class="usage-line">Discount Promotion Count: <?php echo $promotionCount; ?></div>
</div>

</div>

<div class="voucher-management">

    <h2>Voucher Management</h2>

    <table>

        <thead>
            <tr>
                <th></th>
                <th>Voucher Name</th>
                <th>Voucher Code</th>
                <th>Less</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>

<tbody>

<?php

$sql = "SELECT * FROM vouchers";
$result = $conn->query($sql);

$count = 1;

while($row = $result->fetch_assoc()){
?>
<tr>
    <td><?php echo $count++; ?>.</td>
    <td><?php echo $row['voucher_name']; ?></td>
    <td><?php echo $row['voucher_code']; ?></td>
    <td>
    <?= $row['voucher_less'] == 'percentage' 
        ? $row['voucher_less'].'%' 
        : '₱'.$row['voucher_less']; ?>
    </td>
    <td><?php echo $row['status']; ?></td>

    <td>
        <button class="delete-btn" onclick="openDeleteModal(<?= $row['id'] ?>)">
        DELETE
        </button>
    </td>
</tr>
<?php } ?>
</tbody>

    </table>

</div>

<div class="voucher-management">
<h2>Discount Promotion Management</h2>

    <table>

        <thead>
            <tr>
                <th></th>
                <th>Promotion Name</th>
                <th>Discount Type</th>
                <th>Value</th>
                <th>Product Item</th>
                <th>Min Purchase</th>
                <th>Promotion Start</th>
                <th>Promotion End</th>
            </tr>
        </thead>

<tbody>

<?php

$sql = "
SELECT p.*, COUNT(pp.product_id) AS product_count
FROM promotions p
LEFT JOIN promotion_products pp
ON p.id = pp.promotion_id
GROUP BY p.id
";

$result = $conn->query($sql);

$count = 1;

$today = date('Y-m-d');

while($row = $result->fetch_assoc()){

$isExpired = $row['end_date'] < $today;
$isEndingToday = $row['end_date'] == $today;
?>
<tr 
class="promo-row <?= $isExpired ? 'expired-row' : '' ?>" 
data-ending="<?= $isEndingToday ? '1' : '0' ?>"
onclick="viewPromotionProducts(<?= $row['id'] ?>, this)"
>
    <td><?php echo $count++; ?>.</td>
    <td><?php echo $row['promotion_name']; ?></td>
    <td><?php echo $row['discount_type']; ?></td>

    <td>
        <?= $row['discount_type'] == 'percentage' 
        ? $row['discount_value'].'%' 
        : '₱'.$row['discount_value']; ?>
    </td>

    <td><?php echo $row['product_count']; ?></td>
    <td><?php echo $row['min_purchase']; ?></td>
    <td><?php echo $row['start_date']; ?></td>
    <td><?php echo $row['end_date']; ?></td>
</tr>
<?php } ?>

</tbody>

   </table>
</div> 

</main> 

<!-- PROMOTION MODAL -->
<div id="promotionModal" class="modal-overlay">

  <div class="modal-card">

    <h2>Promotion Products</h2>

    <div id="promotionProducts"></div> 

    <button onclick="closePromotionModal()" class="cancel-btn">
      Close
    </button>

  </div>

</div>

<script src="../JS/marketing.js"></script>

</body>
</html>