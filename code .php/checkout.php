<?php
session_start();
require 'includes/db_connect.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['user_id']; // Get logged-in user ID
$cartItems = $_SESSION['cart'] ?? [];
$totalPrice = 0;

// If the cart is empty, redirect to cart page
if (empty($cartItems)) {
    header("Location: cart.php");
    exit();
}

// Fetch products from the database
$placeholders = implode(',', array_fill(0, count($cartItems), '?'));
$stmt = $pdo->prepare("SELECT * FROM Products WHERE ProductID IN ($placeholders)");
$stmt->execute(array_keys($cartItems));
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total price and prepare order items
$orderItems = [];
foreach ($products as $product) {
    $productID = $product['ProductID'];
    $quantity = $cartItems[$productID];
    $price = $product['Price'];
    $totalPrice += $price * $quantity;

    // Store order item details
    $orderItems[] = [
        'ProductID' => $productID,
        'Quantity' => $quantity,
        'Price' => $price
    ];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $paymentMethod = $_POST['paymentMethod'];

    // Insert order into Orders table
    $stmt = $pdo->prepare("INSERT INTO orders (UserID, TotalPrice, Status) VALUES (?, ?, 'Pending')");
    $stmt->execute([$userID, $totalPrice]);

    // Get the last inserted OrderID
    $orderID = $pdo->lastInsertId();

    // Insert order items into OrderItems table
    $stmt = $pdo->prepare("INSERT INTO orderitems (OrderID, ProductID, Quantity, Price) VALUES (?, ?, ?, ?)");
    foreach ($orderItems as $item) {
        $stmt->execute([$orderID, $item['ProductID'], $item['Quantity'], $item['Price']]);
    }

    // Clear cart session after order placement
    $_SESSION['cart'] = [];

    // Redirect to order success page
    header("Location: order_success.php?order_id=$orderID");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - McNeese Bookstore</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/checkout.css">
</head>

<body>

    <!-- Header -->
    <header>
        <h1>Checkout</h1>
    </header>

    <!-- Checkout Form -->
    <div class="checkout-container">
        <h2>Order Summary</h2>

        <!-- ✅ Display Total Price from Cart -->
        <div class="cart-summary">
            <p>Total Amount: $<?= number_format($totalPrice, 2) ?></p>
        </div>

        <form method="POST" action="checkout.php">
            <div class="form-group">
                <label for="fullName">Full Name</label>
                <input type="text" id="fullName" name="fullName" placeholder="Enter your full name" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label for="address">Shipping Address</label>
                <input type="text" id="address" name="address" placeholder="Enter your shipping address" required>
            </div>

            <div class="form-group">
                <label for="paymentMethod">Payment Method</label>
                <select id="paymentMethod" name="paymentMethod" required>
                    <option value="credit-card">Credit Card</option>
                    <option value="paypal">PayPal</option>
                    <option value="cash">Cash on Delivery</option>
                </select>
            </div>

            <button type="submit" class="checkout-btn">Place Order</button>
        </form>

        <button onclick="window.location.href='cart.php'" class="back-to-cart-btn">Back to Cart</button>
    </div>

    <!-- Footer -->
    <footer>
        &copy; 2024 McNeese State University Bookstore | All Rights Reserved
    </footer>

</body>
</html>
