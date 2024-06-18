<?php
session_start();
require 'db.php';
try {
$users_id = $_SESSION['user_id'];
$transaction_date = date('Y-m-d H:i:s');

// Decode the received data

$bankDetails = json_decode($_POST['bank_details'], true);
$deliveryDetails = json_decode($_POST['delivery_details'], true);
$cartItems = json_decode($_POST['cart_items'], true);
$is_delivered = FALSE;
$is_completed  = FALSE;
// Update or insert bank details
$bankDetailsQuery = $pdo->prepare("REPLACE INTO bank_details (users_id, name_on_card, card_number, expiry_date_year, expiry_date_month) VALUES (?, ?, ?, ?, ?)");
$bankDetailsQuery->execute([
    $users_id,
    $bankDetails['name_on_card'],
    $bankDetails['card_number'],
    $bankDetails['expiry_date_year'],
    $bankDetails['expiry_date_month']
]);

// Update or insert delivery details
$deliveryDetailsQuery = $pdo->prepare("UPDATE users SET mobile_number = ?, street_address = ?, suburb = ?, city = ?, province = ?, postal_code = ? WHERE user_id = ?");
$deliveryDetailsQuery->execute([
    $deliveryDetails['mobile_number'],
    $deliveryDetails['street_address'],
    $deliveryDetails['suburb'],
    $deliveryDetails['city'],
    $deliveryDetails['province'],
    $deliveryDetails['postal_code'],
    $users_id
]);

// Insert order

$orderQuery = $pdo->prepare("INSERT INTO orders (users_id, transaction_date, is_delivered, is_completed) VALUES (?, ?, ?, ?)");
if (!$orderQuery->execute([$users_id, $transaction_date, false, false])) {
    throw new Exception('Failed to insert order: ' . implode(', ', $orderQuery->errorInfo()));
}


$order_id = $pdo->lastInsertId();

// Insert order details
foreach ($cartItems as $item) {
    $orderDetailQuery = $pdo->prepare("INSERT INTO orders_detail (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    if (!$orderDetailQuery->execute([$order_id, $item['id'], $item['quantity'], $item['price']])) {
        throw new Exception('Failed to insert order detail: ' . implode(', ', $orderDetailQuery->errorInfo()));
    }
}
echo json_encode(['success' => true]);
}
catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $order_id]);
}
?>
