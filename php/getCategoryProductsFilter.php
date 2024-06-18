<?php
header('Content-Type: application/json');
require 'db.php'; 

$categoryId = isset($_GET['category_id']) ? $_GET['category_id'] : 0;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'price-asc';

$sql = "SELECT * FROM products WHERE category_id = ?";

switch ($sort) {
    case 'price-asc':
        $sql .= " ORDER BY price ASC";
        break;
    case 'price-desc':
        $sql .= " ORDER BY price DESC";
        break;
    case 'relevance':
    default:
        $sql //.= " ORDER BY relevance DESC"; // Adjust this to your relevance logic
        break;
}

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$category_id]);


    $products = array();
    while ($row =$stmt->fetchAll();) {
        $products[] = $row;
    }
    

    echo json_encode($products);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}


?>
