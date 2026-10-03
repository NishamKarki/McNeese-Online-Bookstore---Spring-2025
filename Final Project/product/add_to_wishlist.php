<?php
session_start();
require '../includes/db_connect.php';

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);

    $productId = isset($input['product_id']) ? (int)$input['product_id'] : 0;
    $userId = $_SESSION['user_id'] ?? 0;

    if ($userId && $productId) {
        $stmt = $pdo->prepare("INSERT IGNORE INTO wishlist (UserID, ProductID) VALUES (?, ?)");
        $stmt->execute([$userId, $productId]);

        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Missing user or product"]);
    }
    exit();
}
