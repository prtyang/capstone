<?php
include "../../config/db.php";

$orders = [];

$query = $conn->query("SELECT * FROM orders ORDER BY id DESC");

while ($order = $query->fetch_assoc()) {

    $order_id = $order['id'];

    // GET ITEMS
    $itemsQuery = $conn->query("
        SELECT * FROM order_items 
        WHERE order_id = '$order_id'
    ");

    $items = [];

    while ($item = $itemsQuery->fetch_assoc()) {
        $items[] = [
            "product_id" => $item['product_id'], 
            "product_name" => $item['product_name'],
            "price" => $item['price'],
            "qty" => $item['qty'],
            "color" => $item['color'],
            "size" => $item['size'],
            "image" => $item['image']
        ];
    }

    // FORMAT DATE
    $date = date("M d, Y", strtotime($order['created_at'] ?? "now"));

    $orders[] = [
        "id" => $order_id,
        "date" => $date,
        "status" => strtolower($order['status'] ?? 'orderplace'),
        "total" => $order['total'],
        "items" => $items,
        "refund_message" => $order['refund_message'] ?? "",
        "refund_images" => json_decode($order['refund_images'] ?? "[]", true)
    ];
}

echo json_encode($orders);