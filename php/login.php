<?php
require 'db.php';
session_start();

$data = json_decode(file_get_contents('php://input'), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input']);
    exit;
}

if (!isset($data['email']) || !isset($data['password'])) {
    echo json_encode(['status' => 'error', 'message' => 'Email and password are required']);
    exit;
}

$email = $data['email'];
$password = $data['password'];

$stmt = $pdo->prepare('SELECT user_id, password, is_admin FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['is_admin'] = $user['is_admin'];
    setcookie('isLoggedIn', 'true', time() + (86400 * 30), "/");
    echo json_encode(['status' => 'success', 'is_admin' => $user['is_admin'], 'is_logged_in' => true]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email or password', 'is_logged_in' => false]);
}
?>
