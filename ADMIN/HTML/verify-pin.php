<?php
include "../../config/db.php";

$inputPin = trim($_POST['pin'] ?? '');

// GET PIN FROM DATABASE
$query = $conn->query("
  SELECT setting_value 
  FROM site_settings 
  WHERE setting_key = 'pin_withdraw'
");

$data = $query->fetch_assoc();
$correctPin = trim($data['setting_value'] ?? '0000');

// DEBUG (optional)
// echo "DB: $correctPin | INPUT: $inputPin";

if ($inputPin === $correctPin) {
  echo "success";
} else {
  echo "error";
}
?>