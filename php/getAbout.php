<?php
require 'db.php';

try {
    $stmt = $pdo->prepare('SELECT content FROM about');
    $stmt->execute();
    $about = $stmt->fetch();
    echo json_encode($about);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
