<?php
include(__DIR__ . "/../../config/db.php");

$data = json_decode(file_get_contents("php://input"), true);
$ids = $data['ids'] ?? [];

if (empty($ids)) {
    echo json_encode([]);
    exit;
}

$safeIds = array_map('intval', $ids);
$idString = implode(',', $safeIds);

$sql = "
    SELECT 
        p.id,
        p.name,
        p.brand,
        p.image,
        MIN(v.price) AS min_price,
        MAX(v.price) AS max_price
    FROM products p
    LEFT JOIN product_variations v ON v.product_id = p.id
    WHERE p.id IN ($idString)
    GROUP BY p.id
";


$result = $conn->query($sql);

$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode($products);


