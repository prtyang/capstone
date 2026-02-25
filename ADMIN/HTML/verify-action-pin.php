<?php
include "../../config/db.php";

$inputPin = trim($_POST['pin'] ?? '');

$query = $conn->query("
  SELECT setting_value 
  FROM site_settings 
  WHERE setting_key = 'pin_action'
");

$data = $query->fetch_assoc();
$correctPin = trim($data['setting_value'] ?? '0000');

if ($inputPin === $correctPin) {
  echo "success";
} else {
  echo "error";
}
?>