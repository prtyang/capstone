<?php

include "db.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){

$name = $_POST['voucher_name'];
$code = $_POST['voucher_code'];
$limit = $_POST['voucher_limit'] ?? 0;
$less = $_POST['voucher_less'] ?? 0;

$stmt = $conn->prepare("INSERT INTO vouchers 
(voucher_name, voucher_code, voucher_limit, voucher_less)
VALUES (?, ?, ?, ?)");

$stmt->bind_param("ssii", $name, $code, $limit, $less);

if($stmt->execute()){

echo "<script>
alert('Voucher successfully created!');
window.location.href='../HTML/marketing.php';
</script>";

}else{

echo "Database Error: " . $stmt->error;

}

}
?>