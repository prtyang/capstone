<?php
include("../../config/db.php");

$result = $conn->query("
SELECT 
p.id,
p.image,
p.brand,
p.name,
MIN(v.price) as price
FROM products p
LEFT JOIN product_variations v 
ON v.product_id = p.id
GROUP BY p.id
");

while($row = $result->fetch_assoc()){
?>

<div class="product-select">

<input type="checkbox"
class="productCheck"
data-id="<?php echo $row['id']; ?>"
data-name="<?php echo $row['name']; ?>"
data-price="<?php echo $row['price']; ?>"
data-image="<?php echo $row['image']; ?>"
>

<img src="../../uploads/<?php echo $row['image']; ?>">

<div>
<b><?php echo $row['brand']; ?></b>
<p><?php echo $row['name']; ?></p>
</div>

<span>₱<?php echo number_format($row['price'],2); ?></span>

</div>

<?php } ?>