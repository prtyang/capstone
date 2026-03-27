<?php
include "../../config/db.php";

$email = $_POST['email'];

// CHECK EMAIL
$check = $conn->query("SELECT * FROM admins WHERE email='$email'");
if($check->num_rows == 0){
    echo "Email not found!";
    exit();
}

// GENERATE OTP
$otp = rand(100000,999999);
$expiry = date("Y-m-d H:i:s", strtotime("+5 minutes"));

// SAVE TO DB
$conn->query("UPDATE admins 
SET otp='$otp', otp_expiry='$expiry' 
WHERE email='$email'");


// ==============================
// SEND EMAIL HERE
// ==============================
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer/src/PHPMailer.php';
require '../../PHPMailer/src/SMTP.php';
require '../../PHPMailer/src/Exception.php';

$mail = new PHPMailer(true);

try {
$mail->isSMTP();
$mail->Host = 'sandbox.smtp.mailtrap.io';
$mail->SMTPAuth = true;
$mail->Port = 2525;

$mail->Username = 'e62a0ad78bc6b3';
$mail->Password = 'b8d0a77f70d96d';

$mail->setFrom('libeemail88@gmail.com', 'Forever Admin');
$mail->addAddress($email);

$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->SMTPAutoTLS = false;
$mail->Port = 587;

// 🔥 ADD THIS BLOCK HERE
$mail->SMTPOptions = [
  'ssl' => [
    'verify_peer' => false,
    'verify_peer_name' => false,
    'allow_self_signed' => true
  ]
];

    $mail->setFrom('libeemail88@gmail.com', 'Forever Admin');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Your OTP Code';
    $mail->Body = "
        <h2>Password Reset OTP</h2>
        <h1>$otp</h1>
        <p>Expires in 5 minutes.</p>
    ";

    $mail->send();

    echo "OTP sent to your email";

} catch (Exception $e) {
    echo "Mailer Error: " . $mail->ErrorInfo;
}
?>