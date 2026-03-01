<?php
header('Content-Type: application/json');
include "../../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$id = intval($data['id'] ?? 0);

// CHECK CURRENT STATUS
$order = $conn->query("SELECT status FROM orders WHERE id = $id")->fetch_assoc();

if (!$order) {
  echo json_encode(["success" => false, "error" => "Order not found"]);
  exit;
}

// BLOCK ONLY COMPLETED
if ($order['status'] === 'Completed') {
  echo json_encode([
    "success" => false,
    "error" => "Cannot cancel completed order"
  ]);
  exit;
}

// 🔥 RUN UPDATE + CHECK
$result = $conn->query("
  UPDATE orders 
  SET status = 'Cancel', action = 'Deleted by Seller' 
  WHERE id = $id
");

if ($result) {
  echo json_encode(["success" => true]);
} else {
  echo json_encode([
    "success" => false,
    "error" => $conn->error // 👈 VERY IMPORTANT
  ]);
}