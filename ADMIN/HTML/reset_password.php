<?php
include "../../config/db.php";

$email = trim($_POST['email']);
$otp = trim($_POST['otp']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// GET USER FIRST
$res = $conn->query("SELECT * FROM admins WHERE email='$email'");
$user = $res->fetch_assoc();

if($user){

    // CHECK OTP ONLY (no expiry check here)
    if($user['otp'] == $otp){

        $update = $conn->query("
            UPDATE admins 
            SET password='$password', otp=NULL, otp_expiry=NULL
            WHERE email='$email'
        ");

        if($update){
            echo "Password updated!";
        } else {
            echo "Update failed";
        }

    } else {
        echo "Invalid OTP";
    }

} else {
    echo "User not found";
}
?>