<?php
session_start();
include "../../config/db.php";
header("Content-Type: application/json");

// ✅ CHECK LOGIN
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Not logged in"]);
    exit();
}

if (isset($_FILES['image'])) {

    $file = $_FILES['image'];

    $filename = time() . "_" . basename($file['name']);
    $target = "../../uploads/profile/" . $filename;

    // ✅ CREATE FOLDER IF NOT EXIST
    if (!file_exists("../../uploads/profile/")) {
        mkdir("../../uploads/profile/", 0777, true);
    }

    if (move_uploaded_file($file['tmp_name'], $target)) {

        $path = "uploads/profile/" . $filename;

        // ✅ USE USER ID (NOT EMAIL)
        $id = $_SESSION['user_id'];

        $stmt = $conn->prepare("UPDATE users SET profile_image=? WHERE id=?");
        $stmt->bind_param("si", $path, $id);

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
        "error" => "No file uploaded"
    ]);
}
?>