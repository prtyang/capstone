<?php
$conn = new mysqli("localhost", "root", "", "capstone");

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if(empty($email) || empty($password)){
    echo "Missing data";
    exit();
}

$hashed = password_hash($password, PASSWORD_DEFAULT);

$res = $conn->query("SELECT * FROM users WHERE email='$email'");

if($res->num_rows > 0){
    $conn->query("UPDATE users 
        SET password='$hashed', otp=NULL, otp_expiry=NULL 
        WHERE email='$email'");
    echo "Password updated!";
} else {
    echo "User not found";
}
?>