<?php
include "../../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);
$order_id = intval($data['id'] ?? 0);

if ($order_id <= 0) {
  echo json_encode(["success" => false, "message" => "Invalid ID"]);
  exit;
}

// ✅ CHANGE STATUS HERE
$conn->query("
  UPDATE orders 
  SET status = 'Deleted by Seller'
  WHERE id = $order_id
");

echo json_encode(["success" => true]);
?>