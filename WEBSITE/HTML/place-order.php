<?php
include "../../config/db.php";

// GET JSON DATA
$data = json_decode(file_get_contents("php://input"), true);

// DEBUG (KEEP THIS)
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
$phone     = $data['phone'] ?? '';
$total     = $data['total'] ?? 0;

// ADDRESS
$province = $data['province'] ?? '';
$city = $data['city'] ?? '';
$barangay = $data['barangay'] ?? '';
$postal_code = $data['postal_code'] ?? '';
$full_address = $data['full_address'] ?? '';
$payment = $data['payment_method'] ?? '';
$delivery = $data['delivery_method'] ?? '';

// ==============================
// INSERT ORDER
// ==============================
$orderQuery = "
INSERT INTO orders 
(
  first_name, last_name, email, phone,
  province, city, barangay, postal_code, full_address,
  payment_method, delivery_method,
  total, status
)
VALUES 
(
  '$firstName', '$lastName', '$email', '$phone',
  '$province', '$city', '$barangay', '$postal_code', '$full_address',
  '$payment', '$delivery',
    '$total', 'To Ship'
)";

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
// 🔥 REMOVE ANY OLD ITEMS (SAFETY FIX)
$conn->query("DELETE FROM order_items WHERE order_id = '$order_id'");
// ==============================
// GENERATE ORDER CODE
// ==============================
$month = date("m");
$day   = date("d");
$year  = date("y");
$countFormatted = str_pad($order_id, 2, "0", STR_PAD_LEFT);
$order_code = $month . $day . $year . $countFormatted;

// SAVE ORDER CODE
$conn->query("
    UPDATE orders 
    SET order_code = '$order_code' 
    WHERE id = '$order_id'
");

// ==============================
//  CLEAN ITEMS 
// ==============================
foreach ($data['items'] as $item) {

    $product_id = intval($item['id']); 
    $name  = $conn->real_escape_string($item['product_name']);
    $qty   = intval($item['qty']);
    $price = floatval($item['price']);
    $color = $conn->real_escape_string($item['color']);
    $size  = $conn->real_escape_string($item['size']);
    $image = $conn->real_escape_string($item['image']);

    // INSERT WITH PRODUCT ID
    $insert = $conn->query("
    INSERT INTO order_items 
    (order_id, product_id, product_name, price, qty, color, size, image)
    VALUES (
        '$order_id',
        '$product_id',
        '$name',
        '$price',
        '$qty',
        '$color',
        '$size',
        '$image'
    )
");

    // DEDUCT STOCK (KEEP THIS)
    $conn->query("
        UPDATE product_variations 
        SET qty = qty - $qty
        WHERE product_id = '$product_id'
        AND color = '$color'
        AND size = '$size'
    ");
}


// ==============================
// SUCCESS
// ==============================
echo json_encode([
    "status" => "success",
    "order_id" => $order_id
]);
