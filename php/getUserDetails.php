<?php
session_start();
require_once 'db.php';

try {
    $users_id = $_SESSION['user_id'];

    // Fetch user details
    $userDetailsQuery = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
    $userDetailsQuery->execute([$users_id]);
    $userDetails = $userDetailsQuery->fetch(PDO::FETCH_ASSOC);
    
    // Fetch bank details
    $bankDetailsQuery = $pdo->prepare("SELECT * FROM bank_details WHERE users_id = ?");
    $bankDetailsQuery->execute([$users_id]);
    $bankDetails = $bankDetailsQuery->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'delivery_details' => $userDetails,
        'bank_details' => $bankDetails
    ]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
