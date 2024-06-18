<?php
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input']);
    exit;
}

if (!isset($data['id']) || !isset($data['name']) || !isset($data['description']) || !isset($data['price']) || !isset($data['category_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit;
}

$id = $data['id'];
$name = $data['name'];
$description = $data['description'];
$price = $data['price'];
$category_id = $data['category_id'];
$image_url = isset($data['image_url']) ? $data['image_url'] : null;

try {
    $stmt = $pdo->prepare('UPDATE products SET name = ?, description = ?, price = ?, category_id = ?, image_url = ? WHERE id = ?');
    $stmt->execute([$name, $description, $price, $category_id, $image_url, $id]);
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
