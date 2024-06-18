<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['user_id'])) {
    echo json_encode(['isLoggedIn' => true, 'isAdmin' => $_SESSION['is_admin']]);
} else {
    echo json_encode(['isLoggedIn' => false]);
}
?>
