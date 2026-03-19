<?php
include "db.php";

$promoID = $_GET['promo_id'];

$sql = "
SELECT products.name, products.price
FROM promotion_products
JOIN products 
ON promotion_products.product_id = products.id
WHERE promotion_products.promotion_id = '$promoID'
";

$result = $conn->query($sql);

if($result->num_rows == 0){
    echo "<div class='no-product'>No products found</div>";
    exit();
}

while($row = $result->fetch_assoc()){
?>

<div class="promo-product">

    <div class="promo-left">
        <h4><?php echo $row['name']; ?></h4>
        <p class="promo-desc">
            Product included in this promotion
        </p>
    </div>

    <div class="promo-price">
        ₱<?php echo number_format($row['price'],2); ?>
    </div>

</div>

<?php } ?>