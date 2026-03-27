<?php
include "../../config/db.php";

$email = trim($_POST['email']);
$otp = trim($_POST['otp']);

// GET USER FIRST
$res = $conn->query("SELECT * FROM admins WHERE email='$email'");
$user = $res->fetch_assoc();

if($user){

    // DEBUG (optional)
    // echo "DB OTP: ".$user['otp'];

    if($user['otp'] == $otp){

        // CHECK EXPIRY
        if($user['otp_expiry'] >= date("Y-m-d H:i:s")){
            echo "success";
        } else {
            echo "expired";
        }

    } else {
        echo "wrong";
    }

} else {
    echo "no_user";
}
?>