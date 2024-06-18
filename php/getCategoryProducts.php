<?php
require 'db.php';

if (!isset($_GET['category_id'])) {
    echo json_encode(['error' => 'Category ID is required']);
    exit;
}

$category_id = $_GET['category_id'];

try {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE category_id = ?');
    $stmt->execute([$category_id]);
    $products = $stmt->fetchAll();
    echo json_encode($products);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>

