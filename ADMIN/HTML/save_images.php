<?php
$conn = new mysqli("localhost", "root", "", "capstone");
if ($conn->connect_error) die("DB Error");

// 🔐 GET CURRENT PIN FROM DATABASE
$res = $conn->query("SELECT setting_value FROM site_settings WHERE setting_key='pin_action'");
$row = $res->fetch_assoc();
$currentPIN = $row['setting_value'] ?? '1234';

// 🔐 GET ENTERED PIN FROM FORM
$enteredPIN = $_POST['confirmPIN'] ?? '';

// ❌ BLOCK UPDATE IF PIN IS WRONG
if ($enteredPIN !== $currentPIN) {
    die("Wrong PIN! Update blocked.");
}

function uploadImage($inputName, $folder, $key, $conn) {
    if (!empty($_FILES[$inputName]['name'])) {

        $fileName = time() . "_" . $_FILES[$inputName]['name'];

        $serverPath = $_SERVER['DOCUMENT_ROOT'] . "/CAPSTONE/uploads/$folder/" . $fileName;

        $dbPath = "uploads/$folder/" . $fileName;

        if (!is_dir($_SERVER['DOCUMENT_ROOT'] . "/CAPSTONE/uploads/$folder")) {
            mkdir($_SERVER['DOCUMENT_ROOT'] . "/CAPSTONE/uploads/$folder", 0777, true);
        }

        if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $serverPath)) {
            $stmt = $conn->prepare(
                "UPDATE site_images SET image_path=? WHERE image_key=?"
            );
            $stmt->bind_param("ss", $dbPath, $key);
            $stmt->execute();
        }
    }
}


uploadImage("home_main", "home", "home_main", $conn);
uploadImage("shop_main", "shop", "shop_main", $conn);
uploadImage("cat_1", "category", "cat_1", $conn);
uploadImage("cat_2", "category", "cat_2", $conn);
uploadImage("cat_3", "category", "cat_3", $conn);

function saveSetting($key, $value, $conn) {
    if ($value !== '') {
        $stmt = $conn->prepare(
            "INSERT INTO site_settings (setting_key, setting_value)
             VALUES (?, ?)
             ON DUPLICATE KEY UPDATE setting_value = ?"
        );
        $stmt->bind_param("sss", $key, $value, $value);
        $stmt->execute();
    }
}

saveSetting('shop_name', $_POST['shop_name'] ?? '', $conn);
saveSetting('footer_phone', $_POST['footer_phone'] ?? '', $conn);
saveSetting('footer_address', $_POST['footer_address'] ?? '', $conn);
saveSetting('footer_email', $_POST['footer_email'] ?? '', $conn);
// 🔄UPDATE PIN IF USER ENTERED NEW ONE
saveSetting('pin_action', $_POST['pin_action'] ?? '', $conn);


header("Location: account.php?success=1");
exit;

