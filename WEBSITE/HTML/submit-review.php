<?php
include "../../config/db.php";

header("Content-Type: application/json");

// PREVENT ERRORS FROM BREAKING JSON
error_reporting(0);

$product_id = $_POST['product_id'] ?? 0;
$rating = $_POST['rating'] ?? 0;
$comment = $_POST['comment'] ?? '';
$user = "Customer";

$user_name = $_POST['user_name'] ?? 'Customer';
$user_image = $_POST['user_image'] ?? '';

if ($product_id == 0) {
    echo json_encode(["success" => false, "error" => "Invalid product ID"]);
    exit;
}

// IMAGE UPLOAD
$imagePaths = [];

if (!empty($_FILES['images']['name'][0])) {

    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {

        $fileName = time() . "_" . $_FILES['images']['name'][$key];
        $target = "../../uploads/" . $fileName;

        if (move_uploaded_file($tmp_name, $target)) {
            $imagePaths[] = $fileName;
        }
    }
}

$imageString = implode(",", $imagePaths);

// INSERT
$result = $conn->query("
INSERT INTO product_reviews 
(product_id, user_name, user_image, rating, comment, image, created_at)
VALUES 
('$product_id', '$user_name', '$user_image', '$rating', '$comment', '$imageString', NOW())
");

$order_id = $_POST['order_id'] ?? 0;

if ($result) {

    $conn->query("
        UPDATE order_items
        SET reviewed = 1
        WHERE product_id = $product_id 
        AND order_id = $order_id
    ");

    echo json_encode(["success" => true]);

} else {
    echo json_encode([
        "success" => false,
        "error" => $conn->error
    ]);
}