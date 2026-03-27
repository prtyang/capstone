<?php
$conn = new mysqli("localhost", "root", "", "capstone");

if (!isset($_POST['email']) || !isset($_POST['otp'])) {
    echo "fail";
    exit();
}

$email = $_POST['email'];
$otp = $_POST['otp'];

$res = $conn->query("SELECT * FROM users 
WHERE email='$email'");

if($res->num_rows > 0){
    echo "success";
} else {
    echo "fail";
}
?>