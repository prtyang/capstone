<?php
include "../../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$order_id = $data['order_id'] ?? 0;

// UPDATE STATUS
$conn->query("UPDATE orders SET status='Cancel' WHERE id=$order_id");

echo json_encode(["success" => true]);