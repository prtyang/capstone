<?php
header('Content-Type: application/json');
include "../../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['id'])) {
  echo json_encode(["success" => false]);
  exit;
}

$id = intval($data['id']);

$conn->query("
  UPDATE orders 
  SET status = 'Cancel', action = 'Deleted by Seller' 
  WHERE id = $id
");

echo json_encode(["success" => true]);