<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Create Voucher</title>
<link rel="stylesheet" href="../CSS/voucher.css">
</head>

<body>

<header class="header">

<div class="breadcrumb">
<a href="marketing.php" class="crumb-parent">Marketing</a>
<span class="crumb-separator">/</span>
<span class="crumb-current">Create Voucher</span>
</div>

<div class="logo">
<img src="../PICTURE/logo.png" alt="Forever Logo">
</div>

</header>


<form action="save-voucher.php" method="POST">

<div class="container">

<h1>Create Promotio</h1>

<div class="layout">

<!-- LEFT SIDE -->
<div class="card">

<h3>Basic Info</h3>

<label>Promotion Name</label>
<input type="text" name="promotion_name" required>


<label>Discount Type</label>

<div class="discount-type">
<button type="button" class="active" data-type="percentage">Percentage (%)</button>
<button type="button" data-type="fixed">Fixed Amount (₱)</button>
</div>

<input type="hidden" name="discount_type" id="discountType" value="percentage">

<label>Discount Value</label>
<input type="number" name="discount_value" id="discountValue">

<label>Minimum Purchase</label>
<input type="number" name="min_purchase">

<label>Max Discount Cap</label>
<input type="number" name="max_discount">

<label>Start Date</label>
<input type="date" name="start_date" required>

<label>End Date</label>
<input type="date" name="end_date" required>

</div>


<!-- RIGHT SIDE -->
<div class="card">

<div class="selected-header">
<h3>Selected Products</h3>

<button type="button" id="addProductBtn" class="add-product-btn">
Add Product
</button>

</div>

<table>

<thead>
<tr>
<th>Product</th>
<th>Price</th>
<th>Discount</th>
<th>Final Price</th>
<th></th>
</tr>
</thead>

<tbody id="selectedProducts"></tbody>

</table>

</table>

<div class="buttons">
<button type="button" class="cancel">Cancel</button>
<button type="submit" class="create">Create Voucher</button>
</div>

</div>

</div>

</div>

</form>


</div>
</div>

<script src="../JS/voucher.js"></script>
</header>

<div id="productModal" class="product-modal">

<div class="modal-box">

<div class="modal-header">
<h3>Select Products</h3>

<button class="close-modal" onclick="closeProductModal()">✕</button>
</div>

<input 
type="text" 
id="productSearch" 
class="product-search"
placeholder="Search products..."
>

<div id="productList"></div>

<button onclick="closeProductModal()" class="done-btn">
Done
</button>

</div>

</div>
</div>

</body>
</html>