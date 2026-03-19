<?php
include "db.php";
session_start();

if(!isset($_SESSION['user_id'])){
    echo json_encode(["status"=>"login_required"]);
    exit;
}

$user_id = $_SESSION['user_id'];

$code = $_POST['code'] ?? '';

$query = $conn->prepare("
SELECT id, voucher_less, voucher_limit, used 
FROM vouchers 
WHERE voucher_code=? AND status='Active'
");

$query->bind_param("s",$code);
$query->execute();

$result = $query->get_result();

if($result->num_rows === 0){
echo json_encode(["status"=>"invalid"]);
exit;
}

$row = $result->fetch_assoc();

/* CHECK VOUCHER LIMIT */

$limitCheck = $conn->prepare("
SELECT COUNT(*) AS total 
FROM voucher_usage 
WHERE voucher_id=?
");

$limitCheck->bind_param("i",$row['id']);
$limitCheck->execute();

$countResult = $limitCheck->get_result()->fetch_assoc();

if($countResult['total'] >= $row['voucher_limit']){
echo json_encode(["status"=>"expired"]);
exit;
}


/* CHECK IF USER ALREADY USED */

$check = $conn->prepare("
SELECT id FROM voucher_usage
WHERE voucher_id=? AND user_id=?
");

$check->bind_param("ii",$row['id'],$user_id);
$check->execute();

$usedResult = $check->get_result();

if($usedResult->num_rows > 0){
echo json_encode(["status"=>"already_used"]);
exit;
}

/* VOUCHER IS VALID */

echo json_encode([
"status"=>"valid",
"discount"=>$row['voucher_less'],
"voucher_id"=>$row['id']
]);
