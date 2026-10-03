<?php
session_start();
require '../includes/db_connect.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

// Handle add to cart
if (isset($data['action']) && $data['action'] === 'add' && isset($data['product_id'])) {
    $productId = $data['product_id'];

    // Initialize cart if not set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Increment or add item
    if (isset($_SESSION['cart'][$productId])) {
        $_SESSION['cart'][$productId]++;
    } else {
        $_SESSION['cart'][$productId] = 1;
    }

    echo json_encode(["message" => "Item added to cart."]);
    exit;
}

// Handle count for cart icon
if (isset($_GET['action']) && $_GET['action'] === 'count') {
    $count = 0;
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $qty) {
            $count += $qty;
        }
    }
    echo json_encode(["count" => $count]);
    exit;
}

// Clear cart
if (isset($data['clearCart'])) {
    $_SESSION['cart'] = [];

    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];

        // Delete the user's cart
        $deleteCartStmt = $pdo->prepare("DELETE FROM cart WHERE UserID = ?");
        $deleteCartStmt->execute([$userId]);
    }

    echo json_encode(["message" => "Cart cleared successfully."]);
    exit;
}

// Remove product
if (isset($data['removeProduct'])) {
    $productId = $data['removeProduct'];
    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);
    }
    echo json_encode(["message" => "Item removed from cart."]);
    exit;
}

// Default fallback
echo json_encode(["message" => "Invalid request."]);
exit;
