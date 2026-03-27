<?php
include "../../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'];
$status = $data['status'];

$stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
$stmt->bind_param("si", $status, $id);

if($stmt->execute()){
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false]);
}
?>