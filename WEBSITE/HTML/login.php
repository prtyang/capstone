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
        $check = $conn->query("SELECT * FROM users WHERE email='$email'");
        if($check->num_rows > 0){
            echo "<script>alert('Email already registered');</script>";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);

        $conn->query("INSERT INTO users (name, email, password)
        VALUES ('$username', '$email', '$hashed')");        
            $_SESSION['user'] = $username;

            header("Location: index.php");
            exit();
        }
    }
}

// LOGIN
if(isset($_POST['login'])){
    $username = trim($_POST['login_username']);
    $password = $_POST['login_password'];

    if(empty($username) || empty($password)){
        echo "<script>alert('All fields are required');</script>";
    } else {

        $res = $conn->query("SELECT * FROM users WHERE username='$username'");

        if($res->num_rows > 0){
            $user = $res->fetch_assoc();

            if(password_verify($password, $user['password'])){
                $_SESSION['user'] = $user['username'];
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

    <input type="text" name="login_username" placeholder="Username" required>

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

    <button class="btn" name="login">Login</button>
    <h6><p>Forget Password?</p><h6>
    <p>Don't have an account? <span id="goRegister">Register</span></p>
</div>
</form>
    <!-- PANEL -->
    <div class="panel">
        <div class="panel-content left">
            <h2>WELCOME TO FOREVER!</h2>
            <p>Create your account and step into comfort, confidence, and style.</p>
        </div>

        <div class="panel-content right">
            <h2>WELCOME BACK!</h2>
            <br>At Forever, we bring you the finest collection <br>
            of women's underwear, cozy pajamas, stylishly  <br> designed to make you feel beautiful every day.!</p>
        </div>
    </div>

</div>

<script src="../JS/login.js"></script>
</body>
</html>