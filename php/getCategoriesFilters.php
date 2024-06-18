<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

require 'db.php'; // Update this with your actual database connection script

$sql = 'SELECT category_id, category_name FROM categories ORDER BY category_name ASC';
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(['error' => $conn->error]);
    exit;
}

$categories = array();
while ($row = $result->fetch_assoc()) {
    $categories[$row['id']] = $row['name'];
}

echo json_encode($categories);
?>
