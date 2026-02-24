<?php
include("../../config/db.php");

$data = json_decode(file_get_contents("php://input"), true);

$id = (int)$data['id'];
$status = $data['status'];

$sql = "UPDATE orders SET status='$status' WHERE id=$id";

if ($conn->query($sql)) {
  echo json_encode(["success" => true]);
} else {
  echo json_encode(["success" => false]);
}
?>