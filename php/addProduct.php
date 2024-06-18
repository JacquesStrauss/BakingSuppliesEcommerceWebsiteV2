<?php

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productName = $_POST['product-name'];
    $productDescription = $_POST['product-description'];
    $productprice = $_POST['product-price'];
    $category = $_POST['category'];

    // Handle image upload
    if (isset($_FILES['product-image']) && $_FILES['product-image']['error'] == 0) {
        $image = $_FILES['product-image'];
        $imagePath = '../product_images/' . basename($image['name']);
        if (move_uploaded_file($image['tmp_name'], $imagePath)) {
            // Insert category into database
            $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image_url, category_id) VALUES (?, ?, ?, ?, ?)");

            if ($stmt->execute([$productName, $productDescription, $productprice , $imagePath, $category])) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Database insertion failed: ' . $stmt->error]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to move uploaded file.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Image upload failed.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>


