<?php
require 'db.php';

try {
    $stmt = $pdo->prepare('SELECT category_id, category_name, category_description, category_url FROM categories');
    $stmt->execute();
    $categories = $stmt->fetchAll();
    echo json_encode($categories);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
