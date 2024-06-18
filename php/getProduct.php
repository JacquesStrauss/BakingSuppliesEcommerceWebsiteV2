<?php
require 'db.php';

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'Product ID is required']);
    exit;
}

$product_id = $_GET['id'];

try {
    $stmt = $pdo->prepare('SELECT id, name, description, price, category_id, image_url FROM products WHERE id = ?');
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();
    echo json_encode($product);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
