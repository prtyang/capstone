<?php 
include(__DIR__ . "/../../config/db.php");

/* EDIT MODE  */
$isEdit = false;
$product = null;

if (isset($_GET['id'])) {
  $isEdit = true;
  $id = (int)$_GET['id'];

  $res = $conn->query("SELECT * FROM products WHERE id = $id");
  if ($res && $res->num_rows === 1) {
    $product = $res->fetch_assoc();
  } else {
    die("Product not found.");
  }
}
/* LOAD PRODUCT IMAGES (FOR EDIT) */
$productImages = [];

if ($isEdit) {
  $imgRes = $conn->query("
    SELECT image FROM product_images
    WHERE product_id = $id
    ORDER BY id ASC
    LIMIT 6
  ");

  while ($row = $imgRes->fetch_assoc()) {
    $productImages[] = $row['image'];
  }
} 

/* LOAD VARIATIONS (FOR EDIT) */
$variations = [];

if ($isEdit) {
  $vRes = $conn->query("
    SELECT * FROM product_variations
    WHERE product_id = $id
  ");

  while ($row = $vRes->fetch_assoc()) {
    $variations[] = $row;
  }
}

/*  FORM SUBMIT */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

  $name  = $_POST['product_name'];
  $brand = $_POST['brand'];
  $category = $_POST['category'] ?? '';
  $desc  = $_POST['description'];
  $price = $_POST['price'] ?? null;
  $size  = $_POST['size'] ?? null;
  $color = $_POST['color'] ?? null;
  $qty   = $_POST['qty'] ?? null;
  $sku   = $_POST['sku'] ?? null;

/* CALCULATE PRICE & TOTAL QTY*/
$totalQty = 0;
$firstPrice = null;
$lastPrice  = null;

if (isset($_POST['variation_price'], $_POST['variation_qty'])) {

  $prices = $_POST['variation_price'];

  // GET FIRST NON-EMPTY PRICE
  foreach ($prices as $p) {
    if ($p !== '' && is_numeric($p)) {
      $firstPrice = (float)$p;
      break;
    }
  }

  // GET LAST NON-EMPTY PRICE
  for ($i = count($prices) - 1; $i >= 0; $i--) {
    if ($prices[$i] !== '' && is_numeric($prices[$i])) {
      $lastPrice = (float)$prices[$i];
      break;
    }
  }

  // TOTAL QTY
  foreach ($_POST['variation_qty'] as $q) {
    if (is_numeric($q)) {
      $totalQty += (int)$q;
    }
  }
}

/* FINAL PRODUCT VALUES */
$price = $firstPrice;
$qty = $totalQty;

/* SIZE VALUES */
$length = $_POST['length'] ?? null;
$width  = $_POST['width'] ?? null;
$height = $_POST['height'] ?? null;
$weight = $_POST['weight'] ?? null;

/* IMAGE SQL */
$imageSql = "";


/* MAIN IMAGE */
if (!empty($_FILES['main_image']['name'])) {
  $imageName = time() . "_" . basename($_FILES['main_image']['name']);
  move_uploaded_file($_FILES['main_image']['tmp_name'], __DIR__ . "/../../uploads/" . $imageName);
  $imageSql .= ", image='$imageName'";
}

/* IMAGE 2 */
if (!empty($_FILES['image_2']['name'])) {
  $image2 = time() . "_2_" . basename($_FILES['image_2']['name']);
  move_uploaded_file($_FILES['image_2']['tmp_name'], __DIR__ . "/../../uploads/" . $image2);
  $imageSql .= ", image_2='$image2'";
}

/* SIZE CHART IMAGE */
if (!empty($_FILES['size_chart']['name'])) {
  $sizeChart = time() . "_sc_" . basename($_FILES['size_chart']['name']);
  move_uploaded_file($_FILES['size_chart']['tmp_name'], __DIR__ . "/../../uploads/" . $sizeChart);
  $imageSql .= ", size_chart='$sizeChart'";
}

  if (isset($_POST['product_id'])) {

    $id = (int)$_POST['product_id'];

    $sql = "UPDATE products SET
      name='$name',
      brand='$brand',
      category='$category',
      description='$desc',
      price='$price',
      size='$size',
      color='$color',
      qty='$qty',
      sku='$sku',
      length='$length',
      width='$width',
      height='$height',
      weight='$weight'
      $imageSql
      WHERE id=$id";

  } else {

   $sql = "INSERT INTO products
  (name, brand, category, description, price, size, color, qty, sku,
   length, width, height, weight, image, image_2, size_chart)
  VALUES
  ('$name','$brand','$category','$desc','$price','$size','$color','$qty','$sku',
   '$length','$width','$height','$weight',
   '$imageName','$image2','$sizeChart')";

  }

  if ($conn->query($sql)) {

  /* GET PRODUCT ID */
  if (!isset($id)) {
    $id = $conn->insert_id;
  }

  /* SAVE PRODUCT IMAGES  */

$files = $_FILES['product_images'] ?? [];

$existingImages = [];

if ($isEdit) {
  $res = $conn->query("
    SELECT id, image 
    FROM product_images 
    WHERE product_id = $id 
    ORDER BY id ASC
  ");

  while ($row = $res->fetch_assoc()) {
    $existingImages[] = $row;
  }
}

for ($i = 0; $i < 6; $i++) {

  if (isset($files['error'][$i]) && $files['error'][$i] === 0) {

    $imgName = time() . "_p_" . $i . "_" . basename($files['name'][$i]);

    move_uploaded_file(
      $files['tmp_name'][$i],
      __DIR__ . "/../../uploads/" . $imgName
    );

    // UPDATE EXISTING IMAGE
    if (isset($existingImages[$i])) {

      // DELETE OLD FILE
      $oldPath = __DIR__ . "/../../uploads/" . $existingImages[$i]['image'];
      if (file_exists($oldPath)) unlink($oldPath);

      $stmt = $conn->prepare("
        UPDATE product_images SET image=? WHERE id=?
      ");
      $stmt->bind_param("si", $imgName, $existingImages[$i]['id']);
      $stmt->execute();

    } else {
      // INSERT NEW SLOT
      $stmt = $conn->prepare("
        INSERT INTO product_images (product_id, image)
        VALUES (?, ?)
      ");
      $stmt->bind_param("is", $id, $imgName);
      $stmt->execute();
    }

    //  FIRST IMAGE = MAIN PRODUCT IMAGE
    if ($i === 0) {
      $conn->query("UPDATE products SET image='$imgName' WHERE id=$id");
    }
  }
}

/* SAVE VARIATIONS */
if (isset($_POST['variation_price'])) {

  $prices = $_POST['variation_price'];
  $sizes  = $_POST['variation_size'];
  $colors = $_POST['variation_color'];
  $qtys   = $_POST['variation_qty'];
  $skus   = $_POST['variation_sku'];
  $varIds = $_POST['variation_id'] ?? [];
  $images = $_FILES['variation_image'] ?? [];


  $usedIds = [];


  for ($i = 0; $i < count($prices); $i++) {

    // skip empty rows
    if (
      empty($prices[$i]) &&
      empty($sizes[$i]) &&
      empty($colors[$i])
    ) {
      continue;
    }

    /* IMAGE */
    $varImage = null;

    // keep old image in edit mode
    if (!empty($varIds[$i])) {
      $res = $conn->query("SELECT image FROM product_variations WHERE id=".$varIds[$i]);
      $old = $res->fetch_assoc();
      $varImage = $old['image'] ?? null;
    }

    // new image uploaded
    $colorKey = $colors[$i];

    if (!empty($images['name'][$colorKey])) {

    $varImage = time() . "_var_" . $colorKey . "_" . basename($images['name'][$colorKey]);

      move_uploaded_file(
    $images['tmp_name'][$colorKey],
    __DIR__ . "/../../uploads/" . $varImage
  );
}

  /* UPDATE */
if (!empty($varIds[$i])) {

  $stmt = $conn->prepare("
    UPDATE product_variations
    SET image=?, price=?, size=?, color=?, qty=?, sku=?
    WHERE id=?
  ");

  $stmt->bind_param(
    "sdssisi",
    $varImage,
    $prices[$i],
    $sizes[$i],
    $colors[$i],
    $qtys[$i],
    $skus[$i],
    $varIds[$i]
  );

  $stmt->execute();
  $usedIds[] = (int)$varIds[$i];

}
/* INSERT */
else {

  $stmt = $conn->prepare("
    INSERT INTO product_variations
    (product_id, image, price, size, color, qty, sku)
    VALUES (?, ?, ?, ?, ?, ?, ?)
  ");

  $stmt->bind_param(
    "isdssis",
    $id,
    $varImage,
    $prices[$i],
    $sizes[$i],
    $colors[$i],
    $qtys[$i],
    $skus[$i]
  );

  $stmt->execute();
  $usedIds[] = (int)$stmt->insert_id;
  }
}
// DELETE ONLY REMOVED VARIATIONS
  if ($isEdit && !empty($usedIds)) {
    $ids = implode(",", array_map("intval", $usedIds));
    $conn->query("
      DELETE FROM product_variations
      WHERE product_id = $id
      AND id NOT IN ($ids)
    ");
  }
}


// UPDATE EXISTING VARIATION

  header("Location: product.php");
  exit;
}   

}   

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Product</title>
  <link rel="stylesheet" href="../CSS/add product.css">
</head>

<body>

<!-- TOP HEADER -->
<div class="top-header">
  <div class="breadcrumb">
    <a href="product.php">Product</a> / <span>Add Product</span>
  </div>

  <div class="header-row">
    <h1>Add Product</h1>
    <img src="../PICTURE/logo.png" class="header-logo">
  </div>
</div>

<form id="addProductForm" method="POST" enctype="multipart/form-data" onsubmit="return validateProduct()">

<?php if (isset($product['id'])) { ?>
  <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
<?php } ?>

<input type="hidden" id="isEdit" value="<?php echo isset($product['id']) ? '1' : '0'; ?>">
<input type="hidden" id="hasExistingImages" value="<?php echo !empty($productImages) ? '1' : '0'; ?>">


<div class="page">

  <!-- PRODUCT IMAGE -->
  <div class="card">
    <div class="card-header">
      <h3>Product Setting</h3>
      <button type="submit" class="upload-btn">Upload</button>
    </div>

    <p class="section-label">Product Image</p>

<div class="product-image-grid">

  <!-- BIG IMAGE -->
  <label class="upload-box big"
    <?php if (!empty($productImages[0])) { ?>
      style="background-image:url('../../uploads/<?php echo $productImages[0]; ?>');"
    <?php } ?>>

    <input type="file" name="product_images[]" accept="image/*" onchange="previewImage(this)">

    <span <?php if (!empty($productImages[0])) echo 'style="display:none"'; ?>>
    Upload Main Image
    </span>
  </label>

  <div class="right-grid">

    <div class="top-row">

      <!-- MEDIUM IMAGE 1 -->
      <label class="upload-box medium"
        <?php if (!empty($productImages[1])) { ?>
          style="background-image:url('../../uploads/<?php echo $productImages[1]; ?>');"
        <?php } ?>>

        <input type="file" name="product_images[]" accept="image/*" onchange="previewImage(this)">

        <span <?php if (!empty($productImages[1])) echo 'style="display:none"'; ?>>
          Upload Image 2
        </span>
      </label>

      <!-- MEDIUM IMAGE 2 -->
      <label class="upload-box medium"
        <?php if (!empty($productImages[2])) { ?>
          style="background-image:url('../../uploads/<?php echo $productImages[2]; ?>');"
        <?php } ?>>

        <input type="file" name="product_images[]" accept="image/*" onchange="previewImage(this)">

        <span <?php if (!empty($productImages[2])) echo 'style="display:none"'; ?>>
          Upload Image 3
        </span>
      </label>

    </div>

    <div class="bottom-row">

      <!-- SMALL IMAGE 1 -->
      <label class="upload-box small"
        <?php if (!empty($productImages[3])) { ?>
          style="background-image:url('../../uploads/<?php echo $productImages[3]; ?>');"
        <?php } ?>>

        <input type="file" name="product_images[]" accept="image/*" onchange="previewImage(this)">

        <span <?php if (!empty($productImages[3])) echo 'style="display:none"'; ?>>
          Upload Image 4
        </span>
      </label>

      <!-- SMALL IMAGE 2 -->
      <label class="upload-box small"
        <?php if (!empty($productImages[4])) { ?>
          style="background-image:url('../../uploads/<?php echo $productImages[4]; ?>');"
        <?php } ?>>

        <input type="file" name="product_images[]" accept="image/*" onchange="previewImage(this)">

        <span <?php if (!empty($productImages[4])) echo 'style="display:none"'; ?>>
          Upload Image 5
        </span>
      </label>

      <!-- SMALL IMAGE 3 -->
      <label class="upload-box small"
        <?php if (!empty($productImages[5])) { ?>
          style="background-image:url('../../uploads/<?php echo $productImages[5]; ?>');"
        <?php } ?>>

        <input type="file" name="product_images[]" accept="image/*" onchange="previewImage(this)">

        <span <?php if (!empty($productImages[5])) echo 'style="display:none"'; ?>>
          Upload Image 6
        </span>
      </label>
    </div>
  </div>
</div>


  <!-- PRODUCT INFO -->
<div class="card">
    <h3>Product Info</h3>

    <div class="form-group">
      <label>Brand Name</label>
      <input class="input required" name="brand" value="<?php echo $product['brand'] ?? ''; ?>">
    </div>

    <div class="form-group">
      <label>Product Name</label>
      <input class="input required" name="product_name" value="<?php echo $product['name'] ?? ''; ?>">
    </div>

    <div class="form-group">
      <label>Category</label>
      <select class="input required" name="category">
        <option value="">Select Category</option>

        <option value="BRA" <?php if (($product['category'] ?? '') === 'BRA') echo 'selected'; ?>>BRA</option>
        <option value="PANTY" <?php if (($product['category'] ?? '') === 'PANTY') echo 'selected'; ?>>PANTY</option>
        <option value="PANTYLET" <?php if (($product['category'] ?? '') === 'PANTYLET') echo 'selected'; ?>>PANTYLET</option>
        <option value="PANTY SHORT" <?php if (($product['category'] ?? '') === 'PANTY SHORT') echo 'selected'; ?>>PANTY SHORT</option>
        <option value="SANDO" <?php if (($product['category'] ?? '') === 'SANDO') echo 'selected'; ?>>SANDO</option>
        <option value="SLEEPWEAR" <?php if (($product['category'] ?? '') === 'SLEEPWEAR') echo 'selected'; ?>>SLEEPWEAR</option>
      </select>
    </div>


    <div class="form-group">
      <label>Description</label>
      <textarea
        class="textarea required auto-expand"
        name="description"
        ><?php echo $product['description'] ?? ''; ?></textarea>
    </div>
</div>

<!-- VARIATION -->
<div class="card">
  <h3>Variation</h3>

  <label class="field-label">COLOR</label>
  <input id="variationColors" class="pill-input" placeholder="BLUE, RED...">

  <label class="field-label">SIZE</label>
  <input id="variationSizes" class="pill-input" placeholder="SMALL, MEDIUM...">

  <button type="button" id="addVariationBtn" class="add-btn">ADD</button>

  <!-- GENERATED VARIATIONS -->
  <div id="generatedVariations" style="display:none;margin-top:20px;"></div>
</div>

<!-- SIZE CHART -->
<div class="card">
  <p class="section-title">Size chart</p>

  <label class="size-chart-upload"
    <?php if (!empty($product['size_chart'])) { ?>
      style="
        background-image:url('../../uploads/<?php echo $product['size_chart']; ?>');
        background-size:contain;
        background-repeat:no-repeat;
        background-position:center;
      "
    <?php
    } 
    ?>>
      <input
        type="file"
        name="size_chart"
        accept="image/*"
        onchange="previewSizeChart(this)"
      >
    <span <?php if (!empty($product['size_chart'])) echo 'style="display:none"'; ?>>
      Upload photo
    </span>
  </label>
</div>

<!-- SIZE -->
<div class="card">
  <p class="section-title">Size(cm)</p>

  <div class="size-row">
    <div class="size-item">
      <label>Length</label>
      <input type="number" name="length" value="<?php echo $product['length'] ?? ''; ?>">
    </div>

    <div class="size-item">
      <label>Width</label>
      <input type="number" name="width" value="<?php echo $product['width'] ?? ''; ?>">
    </div>

    <div class="size-item">
      <label>Height</label>
      <input type="number" name="height" value="<?php echo $product['height'] ?? ''; ?>">
    </div>

    <div class="size-item weight">
      <label>Weight (g)</label>
      <input type="number" name="weight" value="<?php echo $product['weight'] ?? ''; ?>">
    </div>
  </div>
</div>

</form>
<script src="../JS/add product.js"></script>

<?php if ($isEdit && !empty($variations)) { ?>
<script>
  window.existingVariations = <?php echo json_encode($variations); ?>;
</script>
<?php } ?>

</body>
</html>
