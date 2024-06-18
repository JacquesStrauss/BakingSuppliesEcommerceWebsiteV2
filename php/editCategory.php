<?php
require 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = $_POST['category-id'];
    $category_name = $_POST['category-name'];
    $category_description = $_POST['category-description'];

    // Handle file upload if a new image is provided
    $category_url = '';
    if (isset($_FILES['category-image']) && $_FILES['category-image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['category-image']['tmp_name'];
        $fileName = $_FILES['category-image']['name'];
        $fileSize = $_FILES['category-image']['size'];
        $fileType = $_FILES['category-image']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $allowedfileExtensions = array('jpg', 'gif', 'png');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            $uploadFileDir = '../category_images/';
            $dest_path = $uploadFileDir . $fileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $category_url = $fileName;
            } else {
                echo json_encode(['success' => false, 'message' => 'Error moving uploaded file']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid file extension']);
            exit;
        }
    }

    try {
        if ($category_url) {
            $stmt = $pdo->prepare('UPDATE categories SET category_name = ?, category_description = ?, category_url = ? WHERE category_id = ?');
            $stmt->execute([$category_name, $category_description, $category_url, $category_id]);
        } else {
            $stmt = $pdo->prepare('UPDATE categories SET category_name = ?, category_description = ? WHERE category_id = ?');
            $stmt->execute([$category_name, $category_description, $category_id]);
        }

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to update category']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?>
