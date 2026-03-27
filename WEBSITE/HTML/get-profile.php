<?php
session_start();
$conn = new mysqli("localhost", "root", "", "capstone");

header("Content-Type: application/json");

// DEBUG
if(!isset($_SESSION['user_id'])){
    echo json_encode(["error" => "SESSION_EMPTY"]);
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT name, email, dob, gender, profile_image FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows === 0){
    echo json_encode(["error" => "USER_NOT_FOUND"]);
    exit();
}

$user = $result->fetch_assoc();

echo json_encode($user);