<?php
session_start();
require '../includes/db_connect.php';

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Fetch cart items
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../js/cart.js" defer></script> 
    <title>Your Cart - McNeese Bookstore</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/cart.css">
</head>

<body>

    <!-- Header -->
    <header>
        <h1>Your Shopping Cart</h1>
        <div class="auth-buttons">
            <button onclick="window.location.href='../index.php'">Continue Shopping</button>
        </div>
    </header>

    <!-- Cart Content -->
    <div class="cart-container">
        <h2>Your Cart Items</h2>
        <div id="cartItems">
            <?php if (count($cartItems) > 0): ?>
                <?php foreach ($cartItems as $item): ?>
                    <div class="cart-item">
                        <img src="/bookstore/<?= $item['ImagePath'] ?>" alt="<?= $item['ProductName'] ?>" 
                            onerror="this.onerror=null; this.src='/bookstore/images/no-image.jpg';">

                        <h3><?= htmlspecialchars($item['ProductName']) ?></h3>
                        <p>$<?= number_format($item['Price'], 2) ?> x <?= $item['quantity'] ?></p>
                        <button class="remove-item" data-id="<?= $item['ProductID'] ?>">Remove</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-cart">Your cart is empty.</div>
            <?php endif; ?>
        </div>

        <!-- Total Price Display -->
        <div id="cartTotal" class="cart-total">Total: $<?= number_format($totalPrice, 2) ?></div>

        <!-- Clear Cart Button -->
        <button id="clearCart" class="clear-cart-btn">Clear Cart</button>

        <!-- Checkout Button -->
        <button id="checkoutButton" class="checkout-btn">Proceed to Checkout</button>
    </div>

    <!-- Footer -->
    <footer>
        &copy; 2024 McNeese State University Bookstore | All Rights Reserved
    </footer>

</body>
</html>
