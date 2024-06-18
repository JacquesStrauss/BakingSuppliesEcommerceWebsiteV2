<?php
require 'db.php';

try {
    $stmt = $pdo->prepare('SELECT id, name, description, price, image_url FROM products');
    $stmt->execute();
    $products = $stmt->fetchAll();
    echo json_encode($products);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
