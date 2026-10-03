<?php
session_start();
require '../includes/db_connect.php';

header("Content-Type: application/json");

$totalPrice = 0;
$cartItems = $_SESSION['cart'] ?? [];

if (!empty($cartItems)) {
    $placeholders = implode(',', array_fill(0, count($cartItems), '?'));
    $stmt = $pdo->prepare("SELECT ProductID, Price FROM Products WHERE ProductID IN ($placeholders)");
    $stmt->execute(array_keys($cartItems));
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as $product) {
        $totalPrice += $product['Price'] * $cartItems[$product['ProductID']];
    }
}

echo json_encode(["totalPrice" => $totalPrice]);
?>
