<?php
session_start();

$response = [
    'isLoggedIn' => isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] === true
];

echo json_encode($response);
?>
