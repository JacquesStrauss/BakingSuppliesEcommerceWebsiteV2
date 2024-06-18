<?php
require 'db.php';

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'Category ID is required']);
    exit;
}

$category_id = $_GET['category_id'];

try {
    $stmt = $pdo->prepare('SELECT category_id, category_name FROM categories WHERE category_id = ?');
    $stmt->execute([$category_id]);
    $category = $stmt->fetch();
    echo json_encode($category);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
