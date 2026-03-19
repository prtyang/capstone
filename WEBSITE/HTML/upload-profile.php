<?php
include "../../config/db.php";
header("Content-Type: application/json");

// REMOVE ALL WARNINGS FROM OUTPUT
error_reporting(0);

if (isset($_FILES['image']) && isset($_POST['email'])) {

    $file = $_FILES['image'];
    $email = $_POST['email'];

    $filename = time() . "_" . basename($file['name']);
    $targetFolder = "../UPLOADS/profile/";

    if (!is_dir($targetFolder)) {
        mkdir($targetFolder, 0777, true);
    }

    $targetPath = $targetFolder . $filename;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {

        // SAVE TO DATABASE
        $conn->query("UPDATE users SET profile_img='$filename' WHERE email='$email'");

        echo json_encode([
            "success" => true,
            "path" => "UPLOADS/profile/" . $filename
        ]);

    } else {
        echo json_encode([
            "success" => false,
            "error" => "Upload failed"
        ]);
    }

} else {
    echo json_encode([
        "success" => false,
        "error" => "Missing file or email"
    ]);
}