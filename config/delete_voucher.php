<?php
include "db.php";

if($_SERVER["REQUEST_METHOD"] === "POST"){

$voucher_id = $_POST['voucher_id'];
$enteredPIN = trim($_POST['pin']);

/* GET PIN */
$query = "SELECT setting_value FROM site_settings WHERE setting_key='pin_action'";
$result = $conn->query($query);

if(!$result){
echo "db_error";
exit;
}

$row = $result->fetch_assoc();
$currentPIN = trim($row['setting_value'] ?? '');

if($enteredPIN != $currentPIN){
echo "wrong_pin";
exit;
}

/* DELETE */
$stmt = $conn->prepare("DELETE FROM vouchers WHERE id=?");
$stmt->bind_param("i",$voucher_id);
$stmt->execute();

echo "success";
}
?>