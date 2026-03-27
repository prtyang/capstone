<?php
session_start();
$conn = new mysqli("localhost", "root", "", "capstone");

if ($conn->connect_error) {
    die("Connection failed");
}

// REGISTER
if(isset($_POST['register'])){
    $username = trim($_POST['reg_username']);
    $email = trim($_POST['reg_email']);
    $password = $_POST['reg_password'];

    if(empty($username) || empty($email) || empty($password)){
        echo "<script>alert('All fields are required');</script>";
    } else {

    // CHECK IF EMAIL EXISTS 
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $check = $stmt->get_result();

    if($check->num_rows > 0){
        echo "<script>alert('Email already registered');</script>";
    } else {

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    // INSERT USER (SECURE)
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed);
    $stmt->execute();        
            $_SESSION['user'] = $username;

            header("Location: index.php");
            exit();
        }
    }
}

// LOGIN
if(isset($_POST['login'])){
    $email = trim($_POST['login_email']);
    $password = $_POST['login_password'];

    if(empty($email) || empty($password)){
        echo "<script>alert('All fields are required');</script>";
    } else {

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if($res->num_rows > 0){
            $user = $res->fetch_assoc();

            // ✅ CORRECT PASSWORD CHECK
            if(password_verify($password, $user['password'])){
                $_SESSION['user'] = $user['name'];
                $_SESSION['user_id'] = $user['id'];

                header("Location: index.php");
                exit();
            } else {
                echo "<script>alert('Wrong password');</script>";
            }

        } else {
            echo "<script>alert('User not found');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Forever Auth</title>
<link rel="stylesheet" href="../CSS/login.css">
</head>
<body>

<div class="background-wave"></div>
    <img src="../PICTURE/LOGO.png" class="logo">
<div class="container" id="container">

<!-- REGISTER -->
<form method="POST" action="">
<div class="form-container register">
    <h2>REGISTER</h2>

    <input type="text" name="reg_username" placeholder="Username" required>
    <input type="email" name="reg_email" placeholder="Email" required>

    <div class="password-box">
        <input type="password" name="reg_password" placeholder="Password" id="regPassword" required>

        <span class="eye togglePassword" data-target="regPassword">
            <!-- KEEP YOUR SVG -->
            <svg class="eye-open" viewBox="0 0 24 24">
                <path d="M2 12C5 7 9 5 12 5s7 2 10 7c-3 5-7 7-10 7s-7-2-10-7z"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>

            <svg class="eye-closed" viewBox="0 0 24 24">
                <path d="M3 3l18 18"/>
                <path d="M2 12C5 7 9 5 12 5c1.5 0 3 .5 4.5 1.5M22 12c-3 5-7 7-10 7-1.5 0-3-.5-4.5-1.5"/>
            </svg>
        </span>
    </div>

    <button class="btn" name="register">Register</button>
    <p>Already have an account? <span id="goLogin">Login</span></p>   
</div>
</form>

<!-- LOGIN -->
<form method="POST" action="">
<div class="form-container login">
    <h2>LOGIN</h2>

    <input type="email" name="login_email" placeholder="Email" required>

    <div class="password-box">
        <input type="password" name="login_password" placeholder="Password" id="loginPassword" required>

        <span class="eye togglePassword" data-target="loginPassword">
            <!-- KEEP SVG -->
            <svg class="eye-open" viewBox="0 0 24 24">
                <path d="M2 12C5 7 9 5 12 5s7 2 10 7c-3 5-7 7-10 7s-7-2-10-7z"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>

            <svg class="eye-closed" viewBox="0 0 24 24">
                <path d="M3 3l18 18"/>
                <path d="M2 12C5 7 9 5 12 5c1.5 0 3 .5 4.5 1.5M22 12c-3 5-7 7-10 7-1.5 0-3-.5-4.5-1.5"/>
            </svg>
        </span>
    </div>

    <button type="submit" class="btn" name="login">Login</button>
    <p class="forgot" id="forgotBtn">Forgot password?</p>
    <p class="signup-text">Don't have an account? <span id="goRegister">Signup</span></p>
</div>
</form>
    <div class="panel">
<div class="panel-content right">

  <!-- DEFAULT PANEL -->
  <div id="defaultPanel">
            <h2>WELCOME!</h2>
            <br>At Forever, we bring you the finest collection <br>
            of women's underwear, cozy pajamas, stylishly  <br> 
            designed to make you feel beautiful every day.!</p>
  </div>

 <div id="forgotPanel" style="display:none;">
  <h2>Reset Password</h2>

  <!-- EMAIL -->
  <input type="email" id="resetEmail" placeholder="Enter your email">

  <button onclick="sendOTP()">Send OTP</button>

  <!-- OTP INPUT -->
  <div id="otpBox" style="display:none;">
    <input type="text" id="otp" placeholder="Enter OTP">
    <button onclick="verifyOTP()">Verify OTP</button>
  </div>

  <!-- NEW PASSWORD -->
  <div id="newPassBox" style="display:none;">
    <input type="password" id="newPassword" placeholder="New Password">
    <button onclick="resetPassword()">Update Password</button>
  </div>

  <p onclick="backToLogin()" style="cursor:pointer;">← Back</p>
</div>

<script src="../JS/login.js"></script>
</body>
</html>