<?php
include(__DIR__ . "/../../config/db.php");

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['ids']) || empty($data['ids'])) {
  echo json_encode([
    "success" => false,
    "blocked" => []
  ]);
  exit;
}

$ids = array_map('intval', $data['ids']);

$deleted = [];
$blocked = [];

foreach ($ids as $id) {

  // CHECK IF PRODUCT HAS ORDERS
// GET PRODUCT NAME FIRST
$getName = $conn->query("SELECT name FROM products WHERE id = $id");
$product = $getName->fetch_assoc();
$productName = $conn->real_escape_string($product['name']);

// CHECK USING EXACT NAME
$check = $conn->query("
  SELECT COUNT(*) as total 
  FROM order_items 
  WHERE product_name = '$productName'
");

  $row = $check->fetch_assoc();

  if ($row['total'] > 0) {
    $blocked[] = $id; //  has orders
  } else {

    // safe to delete
    $conn->query("DELETE FROM products WHERE id = $id");
    $deleted[] = $id;
  }
}

echo json_encode([
  "success" => true,
  "deleted" => $deleted,
  "blocked" => $blocked
]);