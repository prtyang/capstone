<?php
include "../../config/db.php";
header("Content-Type: application/json");

// REMOVE WARNINGS
error_reporting(0);

$name = $_POST['name'] ?? '';
$dob = $_POST['dob'] ?? '';
$gender = $_POST['gender'] ?? '';
$email = $_POST['email'] ?? '';

try {

    $stmt = $conn->prepare("UPDATE users SET name=?, dob=?, gender=? WHERE email=?");
    $stmt->bind_param("ssss", $name, $dob, $gender, $email);
    $stmt->execute();

    echo json_encode([
        "success" => true
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}