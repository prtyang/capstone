<?php
include(__DIR__ . "/../../config/db.php");

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['ids']) || empty($data['ids'])) {
  echo json_encode(["success" => false]);
  exit;
}

$ids = array_map('intval', $data['ids']);
$idList = implode(",", $ids);

$sql = "DELETE FROM products WHERE id IN ($idList)";

if ($conn->query($sql)) {
  echo json_encode(["success" => true]);
} else {
  echo json_encode(["success" => false]);
}
