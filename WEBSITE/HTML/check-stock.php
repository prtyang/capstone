<?php
include(__DIR__ . "/../../config/db.php");

$data = json_decode(file_get_contents("php://input"), true);

$productId = (int)$data['id'];
$color = $conn->real_escape_string($data['color']);
$size = $conn->real_escape_string($data['size']);

$q = $conn->query("
SELECT qty 
FROM product_variations 
WHERE product_id=$productId 
AND color='$color'
AND size='$size'
");

$row = $q->fetch_assoc();

echo json_encode([
    "stock" => $row ? (int)$row['qty'] : 0
]);
