<?php
include "../../config/db.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){

$promotion_name = $_POST['promotion_name'];
$discount_type = $_POST['discount_type'];
$discount_value = $_POST['discount_value'];
$min_purchase = $_POST['min_purchase'];
$max_discount = $_POST['max_discount'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

if(empty($_POST['start_date']) || empty($_POST['end_date'])){
    die("Please select start and end date");
}

if(empty($_POST['products'])){
    echo "Please add at least 1 product";
    exit();
}

/* INSERT PROMOTION */
$stmt = $conn->prepare("
INSERT INTO promotions 
(promotion_name, discount_type, discount_value, min_purchase, max_discount, start_date, end_date)
VALUES (?,?,?,?,?,?,?)
");

$stmt->bind_param("ssdiiss",
$promotion_name,
$discount_type,
$discount_value,
$min_purchase,
$max_discount,
$start_date,
$end_date
);

$stmt->execute();

/* GET LAST INSERTED PROMOTION ID */
$promotion_id = $conn->insert_id;

/* INSERT SELECTED PRODUCTS */
foreach($_POST['products'] as $product_id){

$conn->query("
INSERT INTO promotion_products (promotion_id, product_id)
VALUES ('$promotion_id', '$product_id')
");

}

/* REDIRECT */
header("Location: marketing.php");
exit();
}