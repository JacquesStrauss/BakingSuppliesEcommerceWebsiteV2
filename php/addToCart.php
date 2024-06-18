<?php
session_start();
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);
$productId = $data['productId'];
$quantity = $data['quantity'];

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (!isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId] = 0;
}

$_SESSION['cart'][$productId] += $quantity;

echo json_encode(['status' => 'success']);
?>
