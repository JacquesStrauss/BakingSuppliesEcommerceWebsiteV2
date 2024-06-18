<?php

require 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $categoryName = $_POST['category-name'];
    $categoryDescription = $_POST['category-description'];

    // Handle image upload
    if (isset($_FILES['category-image']) && $_FILES['category-image']['error'] == 0) {
        $image = $_FILES['category-image'];
        $imagePath = '../category_images/' . basename($image['name']);
        if (move_uploaded_file($image['tmp_name'], $imagePath)) {
            // Insert category into database
            $stmt = $pdo->prepare("INSERT INTO categories (category_name, category_description, category_url) VALUES (?, ?, ?)");


            if ($stmt->execute([$categoryName, $categoryDescription, $imagePath])) {
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


