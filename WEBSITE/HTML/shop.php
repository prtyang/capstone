<?php
include(__DIR__ . "/../../config/db.php");

/* CATEGORY FILTER */
$category = $_GET['category'] ?? '';
$categorySql = '';

if (!empty($category)) {
  $safeCategory = $conn->real_escape_string($category);
  $categorySql = " AND p.category = '$safeCategory' ";
}


// SEARCH
$search = $_GET['search'] ?? '';

$searchSql = '';

if (!empty($search)) {
  $safeSearch = $conn->real_escape_string($search);
  $searchSql = "
    AND (
      p.name LIKE '%$safeSearch%'
      OR p.brand LIKE '%$safeSearch%'
    )
  ";
}

// PAGINATION
$limit = 24; 
$page = isset($_GET['page']) && is_numeric($_GET['page'])
  ? (int)$_GET['page']
  : 1;

$offset = ($page - 1) * $limit;

$countSql = "
SELECT COUNT(DISTINCT p.id) AS total
FROM products p
WHERE p.status = 'active'
$categorySql
$searchSql
";

$countResult = $conn->query($countSql);
$totalRows = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);


$sql = "
  SELECT 
    p.id,
    p.image,
    p.status,
    p.brand AS brand_name,
    p.name  AS product_name,
    p.description,
    MIN(v.price) AS min_price,
    MAX(v.price) AS max_price
  FROM products p
  LEFT JOIN product_variations v 
    ON v.product_id = p.id
  WHERE p.status = 'active'
  $categorySql
  $searchSql
  GROUP BY p.id
  ORDER BY p.id DESC
  LIMIT $limit OFFSET $offset
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forever | Product</title>
  <link rel="stylesheet" href="../CSS/shop.css">
</head>

<body>
<div class="page">

<header class="header">

  <div class="logo-wrapper">
    <a href="index.html">
      <img src="../PICTURE/logo.png" class="logo">
    </a>
  </div>

  <div class="header-right">
    <div class="icons">
      <a href="cart.html" class="icon"><img src="../PICTURE/cart.png"></a>
      <a href="wishlist.html" class="icon"><img src="../PICTURE/wishlist.png"></a>
      <a href="login.html" class="icon"><img src="../PICTURE/profile.jpg"></a>
    </div>

    <nav class="nav">
      <a href="index.html">HOME</a>
      <a href="home.php">SHOP</a>
      <a href="shop.php">PRODUCT</a>
      <a href="try-on.html">TRY-ON</a>
    </nav>
  </div>

</header>

<!-- BREADCRUMB -->
<div class="breadcrumb">
  <a href="home.php">SHOP</a>
  <span>/</span>
  <strong>PRODUCT</strong>
</div>

<!--  SEARCH & FILTER -->
<section class="top-controls">
  <form method="GET" style="flex:1;">
    <input
      type="text"
      name="search"
      class="search-box"
      placeholder="search..."
      value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>"
    >
  </form>

  <div class="filters">
    <form method="GET" id="categoryForm">
      <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">

      <select name="category" onchange="this.form.submit()">
        <option value="">ALL</option>
        <option value="BRA" <?php if ($category === 'BRA') echo 'selected'; ?>>BRA</option>
        <option value="PANTY" <?php if ($category === 'PANTY') echo 'selected'; ?>>PANTY</option>
        <option value="SLEEPWEAR" <?php if ($category === 'SLEEPWEAR') echo 'selected'; ?>>SLEEPWEAR</option>
      </select>
    </form>
  </div>
</section> 

<!--  PRODUCT GRID  -->
<section class="product-grid">

<?php while ($row = $result->fetch_assoc()) { ?>

<div class="product-card" data-id="<?php echo $row['id']; ?>">

  <a href="product-view.php?id=<?php echo $row['id']; ?>" class="card-link">

    <div class="image-box"
      style="background-image:url('../../uploads/<?php echo $row['image']; ?>');">
    </div>

<div class="product-info">
<h4 class="brand">
  <?php echo htmlspecialchars($row['brand_name'] ?? ''); ?>
</h4>

<p class="product-name">
  <?php echo htmlspecialchars($row['product_name'] ?? ''); ?>
</p>


      <span class="price">
  ₱
  <?php
    if ($row['min_price'] !== null) {
      echo ($row['min_price'] == $row['max_price'])
        ? number_format($row['min_price'], 2)
        : number_format($row['min_price'], 2) . ' - ' . number_format($row['max_price'], 2);
    } else {
      echo number_format($row['price'], 2);
    }
  ?>
</span>

    </div>

  </a>

  <!--WHISHLIST -->
  <span class="heart"
  onclick="event.stopPropagation(); event.preventDefault(); addToWishlist(this);">
  ♡
</span>


</div>

<?php } ?>

</section>

<!--PAGINATION-->
<?php if ($totalPages > 1) { ?>
<div class="pagination">

<?php if ($page > 1) { ?>
  <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>">
    Prev
  </a>
<?php } ?>

<?php for ($i = 1; $i <= $totalPages; $i++) { ?>
  <a
    href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>"
    class="<?php echo $i == $page ? 'active' : ''; ?>"
  >
    <?php echo $i; ?>
  </a>
<?php } ?>

<?php if ($page < $totalPages) { ?>
  <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>&category=<?php echo urlencode($category); ?>">
    Next
  </a>
<?php } ?>


</div>
<?php } ?>


<div class="divider-line"></div>

<!--  FOOTER  -->
  <footer class="footer">
    <h2>LET’S CONNECT!</h2>

    <div class="socials">
      <a href="https://web.facebook.com/intimateforeverph" target="_blank">
        <img src="../PICTURE/FB.png" alt="Facebook">
      </a>

      <a href="https://www.instagram.com/intimateforeverph/" target="_blank">
        <img src="../PICTURE/IG.png" alt="Instagram">
      </a>

      <a href="https://www.tiktok.com/@intimateforeverph?lang=en" target="_blank">
        <img src="../PICTURE/TIKTOK.png" alt="TikTok">
      </a>

      <a href="https://shopee.ph/forever_ph?categoryId=100017&entryPoint=ShopByPDP&itemId=12634684993&upstream=search" target="_blank">
        <img src="../PICTURE/SHOPEE.png" alt="Shopee">
      </a>

      <a href="https://www.lazada.com.ph/shop/intimateforever?spm=a211g0.store_hp.top.share&dsource=share&laz_share_info=2386099838_0_7900_500672016194_2386101838_null&laz_token=2b79ba534c50bd531f3a3908bdbdd40f&exlaz=e_1utFWoJ%2B51jGip8qo24MCZFmNCmP6gU%2FnnWa525VocjCXvTKdNAtBbVs1%2B5q2wp%2BGS5d%2BCcBXmHn7bOPEPY8UzkCKkVzibKGD3WnASIas1Y%3D&sub_aff_id=social_share&sub_id2=2386099838&sub_id3=500672016194&sub_id6=CPI_EXLAZ"
        target="_blank">
        <img src="../PICTURE/LAZADA.webp" alt="Lazada">
      </a>
    </div>

    <p>
      64 J.P Bautista Caloocan, Caloocan, Philippines<br>
      0939 819 6120<br>
      <a href="mailto:intimateforevergarments@gmail.com">
        intimateforevergarments@gmail.com
      </a>
    </p>

    <div class="footer-links">
      <a href="#">Terms of Service</a>
      <span>|</span>
      <a href="#">Privacy Policy</a>
      <span>|</span>
      <a href="#">Refund Policy</a>

    <p class="footer-copy">
      © 2026 Capstone Project. All rights reserved.
    </p>


</footer>

<script src="../JS/shop.js"></script>
</div>
</body>
</html>
