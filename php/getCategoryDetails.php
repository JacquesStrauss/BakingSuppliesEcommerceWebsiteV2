<?php
require 'db.php';

header('Content-Type: application/json');

if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];

    try {
        $stmt = $pdo->prepare('SELECT * FROM categories WHERE category_id = ?');
        $stmt->execute([$category_id]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode($category);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to fetch category details']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Category ID is required']);
}
?>
