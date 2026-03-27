<?php
session_start();
include "../../config/db.php";

/* ONLY RUN LOGIN IF POST */
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $data = json_decode(file_get_contents("php://input"), true);

    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM admins WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($user = $result->fetch_assoc()){

    //  DEBUG START
    error_log("Input: " . $password);
    error_log("DB: " . $user['password']);
    error_log("Verify: " . (password_verify($password, $user['password']) ? 'true' : 'false'));
    //  DEBUG END

    if(password_verify($password, $user['password'])){
        $_SESSION['admin'] = $user['id'];
        echo json_encode(["status"=>"success"]);
    } else {
        echo json_encode(["status"=>"wrong_password"]);
    }
}

    exit; 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../CSS/login.css">
</head>
<body>

<div class="login-wrapper">

    <img src="../PICTURE/logo.png" class="logo">

    <div class="login-box">
        <img src="../PICTURE/logo.png" class="logo-center">

    <div id="loginForm">

        <h2>Sign in</h2>
        <p>Sign in and start managing your shop!</p>

    <div class="input-box">
        <input type="text" id="email" required>
        <label>Email</label>
        <span class="line"></span>
    </div>

    <div class="input-box password-box">
        <input type="password" id="password" required>
        <label>Password</label>
    <span class="line"></span>

<!-- 👁 TOGGLE -->
<span class="toggle-eye" onclick="togglePassword()">

    <!-- OPEN EYE -->
    <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
        fill="none" stroke="#c94a7c" stroke-width="2" viewBox="0 0 24 24">
        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
        <circle cx="12" cy="12" r="3"/>
    </svg>

    <!-- CLOSED EYE -->
    <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
        fill="none" stroke="#c94a7c" stroke-width="2" viewBox="0 0 24 24"
        style="display:none;">
        <path d="M17.94 17.94A10.94 10.94 0 0 1 12 19C5 19 1 12 1 12a21.77 21.77 0 0 1 5.06-6.94"/>
        <path d="M1 1l22 22"/>
    </svg>

    </span>
</div>
<button id="loginBtn">Login</button>

<p class="forgot-link" onclick="showForgot()">Forgot password?</p>

</div> 

<!-- RESET PANEL -->
<div id="forgotBox" style="display:none; margin-top:20px;">

    <h2 style="color:#c94a7c;">Reset Password</h2>

    <div class="input-box">
        <input type="email" id="resetEmail" required>
        <label>Enter your email</label>
        <span class="line"></span>
    </div>

    <button onclick="sendOTP(event)">Send OTP</button>

    <div id="otpBox" style="display:none; margin-top:15px;">

    <div class="input-box">
        <input type="text" id="otp">
        <label>Enter OTP</label>
        <span class="line"></span>
    </div>
        <button onclick="verifyOTP()">Verify OTP</button>
    </div>

    <div id="newPassBox" style="display:none; margin-top:15px;">

    <div class="input-box password-box">
        <input type="password" id="newPassword">
        <label>New Password</label>
        <span class="line"></span>

    <!-- 👁 TOGGLE -->
    <span class="toggle-eye" onclick="toggleNewPassword()">

    <!-- OPEN EYE -->
    <svg id="newEyeOpen" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
        fill="none" stroke="#c94a7c" stroke-width="2" viewBox="0 0 24 24">
        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
        <circle cx="12" cy="12" r="3"/>
    </svg>

    <!-- CLOSED EYE -->
    <svg id="newEyeClosed" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
        fill="none" stroke="#c94a7c" stroke-width="2" viewBox="0 0 24 24"
        style="display:none;">
        <path d="M17.94 17.94A10.94 10.94 0 0 1 12 19C5 19 1 12 1 12a21.77 21.77 0 0 1 5.06-6.94"/>
        <path d="M1 1l22 22"/>
    </svg>

    </span>
</div>

    <button onclick="resetPassword()">Update Password</button>
    </div>

<p onclick="backToLogin()" style="cursor:pointer; color:#c94a7c;">← Back</p>

</div>
</div>

</div>
<script src="../JS/login.js"></script>

</body>
</html>