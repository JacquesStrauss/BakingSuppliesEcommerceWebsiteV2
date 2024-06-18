<?php
session_start();
require 'db.php'; // Make sure 'db.php' contains your database connection code

$cartItems = [];

if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $stmt = $pdo->prepare('SELECT id, name, price, image_url FROM products WHERE id = ?');
        $stmt->execute([$productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch as associative array
        if ($product) {
            $product['quantity'] = $quantity;
            $cartItems[] = $product;
        }
    }
}

// Output JSON encoded array of cart items
header('Content-Type: application/json');
echo json_encode($cartItems);
?>
