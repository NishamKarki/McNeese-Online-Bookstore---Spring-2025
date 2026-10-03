<?php
session_start();
require 'includes/db_connect.php';

$userId = $_SESSION['user_id'] ?? null;

// Save cart to database
if ($userId && !empty($_SESSION['cart'])) {
    // Delete old cart and items
    $stmt = $pdo->prepare("SELECT CartID FROM cart WHERE UserID = ?");
    $stmt->execute([$userId]);
    $oldCart = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($oldCart) {
        $oldCartId = $oldCart['CartID'];
        $pdo->prepare("DELETE FROM cartitems WHERE CartID = ?")->execute([$oldCartId]);
        $pdo->prepare("DELETE FROM cart WHERE CartID = ?")->execute([$oldCartId]);
    }

    // Insert new cart
    $stmt = $pdo->prepare("INSERT INTO cart (UserID) VALUES (?)");
    $stmt->execute([$userId]);
    $newCartId = $pdo->lastInsertId();

    // Insert cart items
    $stmt = $pdo->prepare("INSERT INTO cartitems (CartID, ProductID, Quantity) VALUES (?, ?, ?)");
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $stmt->execute([$newCartId, $productId, $quantity]);
    }
}


session_unset();
session_destroy();

header("Location: index.php");
exit();
