<?php
session_start();
require 'db.php'; // Make sure 'db.php' contains your database connection code

$data = json_decode(file_get_contents('php://input'), true);
$productId = $data['productId'];
$quantity = $data['quantity'];

if (isset($_SESSION['cart'][$productId])) {
    $_SESSION['cart'][$productId] = $quantity;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
