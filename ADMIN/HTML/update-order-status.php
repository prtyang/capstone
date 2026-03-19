<?php
include "../../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'], $data['status'])) {
  echo json_encode(["success" => false]);
  exit;
}

$id = (int)$data['id'];
$status = $data['status'];

//  UPDATE ORDERS (NOT PRODUCTS)
$sql = "UPDATE orders SET status='$status' WHERE id=$id";

if ($conn->query($sql)) {
  echo json_encode(["success" => true]);
} else {
  echo json_encode([
    "success" => false,
    "error" => $conn->error
  ]);
}