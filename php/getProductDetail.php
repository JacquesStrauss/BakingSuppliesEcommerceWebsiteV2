<?php
require 'db.php';

if (isset($_GET['product_id'])) {
    $productId = $_GET['product_id'];

    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$productId]);
    $product = $stmt->fetch();

    if ($product) {
        echo json_encode($product);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Product not found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Product ID is required']);
}
?>
