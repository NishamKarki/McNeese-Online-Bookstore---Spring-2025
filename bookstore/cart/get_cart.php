<?php
session_start();
require '../includes/db_connect.php';

header('Content-Type: application/json');

$cartItems = [];
$totalPrice = 0;

if (!empty($_SESSION['cart'])) {
    $placeholders = implode(',', array_fill(0, count($_SESSION['cart']), '?'));
    $stmt = $pdo->prepare("SELECT * FROM Products WHERE ProductID IN ($placeholders)");
    $stmt->execute(array_keys($_SESSION['cart']));
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($cartItems as &$item) {
        $item['quantity'] = $_SESSION['cart'][$item['ProductID']];
        $totalPrice += $item['Price'] * $item['quantity'];
    }
}

echo json_encode($cartItems);
