<?php
include "../../config/db.php";

$start = $_GET['start'] ?? '';
$end   = $_GET['end'] ?? '';

if(!$start || !$end){
    die("Invalid date");
}

// EXCEL DOWNLOAD
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=orders_$start-to-$end.xls");

// GET ORDERS
$status = $_GET['status'] ?? '';

// BASE QUERY
$sql = "SELECT * FROM orders 
        WHERE DATE(created_at) BETWEEN '$start' AND '$end'";

// ADD FILTER IF COMING FROM PAGE (like Shipping, To Ship, etc.)
if(!empty($status)){
    $statuses = explode(",", $status);

    $statusList = array_map(function($s){
        return "'".trim($s)."'";
    }, $statuses);

    $statusString = implode(",", $statusList);

    $sql .= " AND status IN ($statusString)";
}

$sql .= " ORDER BY id DESC";

$orders = $conn->query($sql);

echo "<table border='1'>";

// HEADER
echo "<tr>
        <th>Order ID</th>
        <th>Customer</th>
        <th>Email</th>
        <th>Product</th>
        <th>Color</th>
        <th>Size</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Total</th>
        <th>Status</th>
        <th>Date</th>
    </tr>";

while($order = $orders->fetch_assoc()){

    // GET ITEMS PER ORDER
    $items = $conn->query("
        SELECT * FROM order_items 
        WHERE order_id = '".$order['id']."'
    ");

    if($items->num_rows > 0){
        while($item = $items->fetch_assoc()){

            echo "<tr>
                <td>{$order['order_code']}</td>
                <td>{$order['first_name']} {$order['last_name']}</td>
                <td>{$order['email']}</td>
                <td>{$item['product_name']}</td>
                <td>{$item['color']}</td>
                <td>{$item['size']}</td>
                <td>{$item['qty']}</td>
                <td>{$item['price']}</td>
                <td>{$order['total']}</td>
                <td>{$order['status']}</td>
                <td>{$order['created_at']}</td>
            </tr>";
        }
    } else {
        // if no items (fallback)
        echo "<tr>
            <td>{$order['order_code']}</td>
            <td>{$order['first_name']} {$order['last_name']}</td>
            <td>{$order['email']}</td>
            <td colspan='5'>No items</td>
            <td>{$order['total']}</td>
            <td>{$order['status']}</td>
            <td>{$order['created_at']}</td>
        </tr>";
    }
}

echo "</table>";