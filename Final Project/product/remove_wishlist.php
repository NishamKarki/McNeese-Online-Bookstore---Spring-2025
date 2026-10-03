<?php
session_start();
require '../includes/db_connect.php';

header("Content-Type: application/json");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

// Read JSON data from fetch body
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['product_id'])) {
    echo json_encode(['error' => 'Missing product ID']);
    exit;
}

$userId = $_SESSION['user_id'];
$productId = $data['product_id'];

// Delete from wishlist
$stmt = $pdo->prepare("DELETE FROM wishlist WHERE UserID = ? AND ProductID = ?");
$stmt->execute([$userId, $productId]);

echo json_encode(['success' => true]);
?>
