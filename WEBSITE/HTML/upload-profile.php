<?php
include "../../config/db.php";
header("Content-Type: application/json");

if (isset($_FILES['image']) && isset($_POST['email'])) {

    $file = $_FILES['image'];
    $email = $_POST['email'];

    $filename = time() . "_" . basename($file['name']);
    $target = "../../uploads/profile/" . $filename;

    if (move_uploaded_file($file['tmp_name'], $target)) {

        // 🔥 IMPORTANT: SAVE PATH TO DATABASE
        $path = "uploads/profile/" . $filename;

        $stmt = $conn->prepare("UPDATE users SET profile_image=? WHERE email=?");
        $stmt->bind_param("ss", $path, $email);

        if ($stmt->execute()) {
            echo json_encode([
                "success" => true,
                "path" => $path
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "error" => "DB update failed"
            ]);
        }

    } else {
        echo json_encode([
            "success" => false,
            "error" => "Upload failed"
        ]);
    }

} else {
    echo json_encode([
        "success" => false,
        "error" => "No file or email"
    ]);
}