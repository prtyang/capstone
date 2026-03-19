<?php
include "../../config/db.php";

$order_id = $_POST['order_id'] ?? 0;
$message = $_POST['message'] ?? '';

$uploadedFiles = [];
$imageCount = 0;
$videoCount = 0;

if (isset($_FILES['files']) && count($_FILES['files']['name']) > 0) {

  for ($i = 0; $i < count($_FILES['files']['name']); $i++) {

    if ($_FILES['files']['error'][$i] === 0) {

      $tmp = $_FILES['files']['tmp_name'][$i];
      $size = $_FILES['files']['size'][$i];

      // 🔒 LIMIT FILE SIZE (5MB)
      if ($size > 5 * 1024 * 1024) continue;

      $fileType = mime_content_type($tmp);

      // ✅ CHECK IMAGE
      if (strpos($fileType, 'image/') === 0) {

        if ($imageCount >= 4) continue;
        $imageCount++;

      } 
      // ✅ CHECK VIDEO
      elseif (strpos($fileType, 'video/') === 0) {

        if ($videoCount >= 1) continue;
        $videoCount++;

      } 
      else {
        continue; // ❌ skip invalid file
      }

      // 🔐 SAFE FILE NAME
      $ext = pathinfo($_FILES['files']['name'][$i], PATHINFO_EXTENSION);
      $fileName = time() . "_" . uniqid() . "." . $ext;

      $target = "../../uploads/" . $fileName;

      if (move_uploaded_file($tmp, $target)) {
        $uploadedFiles[] = $fileName;
      }
    }
  }
}

$imagesJSON = json_encode($uploadedFiles);

// UPDATE ORDER
$stmt = $conn->prepare("
  UPDATE orders 
  SET refund_message=?, refund_images=?, status='Request Return'
  WHERE id=?
");

$stmt->bind_param("ssi", $message, $imagesJSON, $order_id);

if ($stmt->execute()) {
  echo json_encode(["success" => true, "files" => $uploadedFiles]);
} else {
  echo json_encode(["success" => false]);
}
?>