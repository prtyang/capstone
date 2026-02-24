<?php
include "../../config/db.php";

// GET JSON DATA
$data = json_decode(file_get_contents("php://input"), true);
file_put_contents("debug.txt", json_encode($data));

// VALIDATION
if (!$data || !isset($data['items']) || count($data['items']) == 0) {
    echo json_encode([
        "status" => "error",
        "message" => "No items received"
    ]);
    exit;
}

// CUSTOMER INFO
$firstName = $data['firstName'] ?? '';
$lastName  = $data['lastName'] ?? '';
$email     = $data['email'] ?? '';
$address   = $data['address'] ?? '';
$phone     = $data['phone'] ?? '';
$total     = $data['total'] ?? 0;

// INSERT ORDER
$orderQuery = "
INSERT INTO orders 
(first_name, last_name, email, phone, total, status)
VALUES 
('$firstName', '$lastName', '$email', '$phone', '$total', 'To Ship')
";

$result = $conn->query($orderQuery);

if (!$result) {
    echo json_encode([
        "status" => "error",
        "message" => $conn->error
    ]);
    exit;
}

// GET ORDER ID
$order_id = $conn->insert_id;

// 🔥 GENERATE CUSTOM ORDER CODE (MMDDYY + AUTO COUNT)
$month = date("m");
$day   = date("d");
$year  = date("y");

// ✅ USE order_id as global counter (BEST)
$countFormatted = str_pad($order_id, 2, "0", STR_PAD_LEFT);

// FINAL FORMAT: 02242601
$order_code = $month . $day . $year . $countFormatted;

// SAVE TO DATABASE
$conn->query("
    UPDATE orders 
    SET order_code = '$order_code' 
    WHERE id = '$order_id'
");

// INSERT ITEMS
foreach ($data['items'] as $item) {

    $name  = $item['product_name'] ?? '';
    $qty   = $item['qty'] ?? 1;
    $price = $item['price'] ?? 0;
    $color = $item['color'] ?? '';
    $size  = $item['size'] ?? '';
    $image = $item['image'] ?? '';

    // INSERT ORDER ITEM
    $conn->query("
        INSERT INTO order_items 
        (order_id, product_name, price, qty, color, size, image)
        VALUES (
            '$order_id',
            '$name',
            '$price',
            '$qty',
            '$color',
            '$size',
            '$image'
        )
    ");

    // 🔥 DEDUCT STOCK (IMPORTANT)
    $conn->query("
        UPDATE product_variations 
        SET qty = qty - $qty
        WHERE product_id = (
            SELECT id FROM products WHERE name = '$name' LIMIT 1
        )
        AND color = '$color'
        AND size = '$size'
    ");
}

// SUCCESS
echo json_encode([
    "status" => "success",
    "order_id" => $order_id
]);