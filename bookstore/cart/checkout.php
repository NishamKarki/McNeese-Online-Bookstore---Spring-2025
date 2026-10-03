<?php
session_start();
require '../includes/db_connect.php';

// // Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$userID = $_SESSION['user_id']; // Get logged-in user ID
$cartItems = $_SESSION['cart'] ?? [];
$totalPrice = 0;

// If the cart is empty, redirect to cart page
if (empty($cartItems)) {
    header("Location: ../cart/cart.php");
    exit();
}

// Fetch products from the database (Only if cart is not empty)
if (!empty($cartItems)) {
    $placeholders = implode(',', array_fill(0, count($cartItems), '?'));
    $stmt = $pdo->prepare("SELECT * FROM Products WHERE ProductID IN ($placeholders)");
    $stmt->execute(array_keys($cartItems));
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $products = [];
}

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
    $paymentMethod = $_POST['paymentType'];
    
    // Handle Promo Discount
    // $discount = isset($_POST['discount']) ? floatval($_POST['discount']) : 0;
    // if ($discount > 0) {
    //     $totalPrice -= ($totalPrice * $discount);
    // }

    // Insert order into Orders table
    $stmt = $pdo->prepare("INSERT INTO orders (UserID, TotalPrice, Status, OrderDate) VALUES (?, ?, 'Pending', NOW())");
    $stmt->execute([$userID, $totalPrice]);

    // Get the last inserted OrderID
    $orderID = $pdo->lastInsertId();

    // Insert into orderhistory table
    $stmt = $pdo->prepare("INSERT INTO orderhistory (OrderID, Status, Comment) VALUES (?, 'Placed', 'Initial order placed')");
    $stmt->execute([$orderID]);


    // Insert order items into OrderItems table
    $stmt = $pdo->prepare("INSERT INTO orderitems (OrderID, ProductID, Quantity, Price) VALUES (?, ?, ?, ?)");
    foreach ($orderItems as $item) {
        $stmt->execute([$orderID, $item['ProductID'], $item['Quantity'], $item['Price']]);
    }

    $cardNumber = $_POST['cardnumber'];
    $last4 = substr($cardNumber, -4);
    $cardHash = hash('sha256', $userID . $last4 . $_POST['cardholder']);
    
    // Check for existing method
    $stmt = $pdo->prepare("SELECT PaymentMethodID FROM paymentmethods WHERE UserID = ? AND Token = ?");
    $stmt->execute([$userID, $cardHash]);
    $existingMethod = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingMethod) {
        $paymentMethodID = $existingMethod['PaymentMethodID'];
    } else {
        // Encrypt the card number (simulate)
        $encryptedCard = password_hash($cardNumber, PASSWORD_BCRYPT);

        $stmt = $pdo->prepare("
            INSERT INTO paymentmethods 
            (UserID, PaymentType, CardHolderName, EncryptedCardNumber, ExpirationMonth, ExpirationYear, BillingAddress, Token)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $userID,
            $_POST['paymentType'],
            $_POST['cardholder'],
            $encryptedCard,
            $_POST['expMonth'],
            $_POST['expYear'],
            $_POST['billingAddress'],
            $cardHash
        ]);

        $paymentMethodID = $pdo->lastInsertId();
    }

    //Save payment transaction
    $stmt = $pdo->prepare("INSERT INTO payments (
        OrderID, PaymentAmount, PaymentDate, PaymentMethodID, PaymentStatus
    ) VALUES (?, ?, NOW(), ?, 'Completed')");

    $stmt->execute([
        $orderID,
        $totalPrice,
        $paymentMethodID
    ]);

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
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/checkout.css">
</head>

<body>

    <!-- Header -->
    <header>
        <h1>Checkout</h1>
    </header>

    <!-- Checkout Form -->
    <div class="checkout-container">
        <h2>Order Summary</h2>

        <!-- Display Total Price from Cart -->
        <div class="cart-summary">
            <p>Total Amount: $<span id="checkoutTotal"><?= number_format($totalPrice, 2) ?></span></p>
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
                <label for="cardholder">Cardholder Name</label>
                <input type="text" name="cardholder" placeholder="Cardholder Name" required>
            </div>

            <div class="form-group">
                <label for="cardnumber">Card Number</label>
                <input type="text" name="cardnumber" placeholder="Card Number" required>
            </div>

            <div class="form-group">
                <label for="expMonth">Expiration Month</label>
                <input type="text" name="expMonth" placeholder="MM" required>
            </div>

            <div class="form-group">
                <label for="expYear">Expiration Year</label>
                <input type="text" name="expYear" placeholder="YYYY" required>
            </div>

            <div class="form-group">
                <label for="billingAddress">Billing Address</label>
                <input type="text" name="billingAddress" placeholder="Billing Address" required>
            </div>

            <div class="form-group">
                <label for="paymentType">Payment Type</label>
                <select name="paymentType">
                    <option value="CreditCard">Credit</option>
                    <option value="DebitCard">Debit</option>
                </select>
            </div>

            <button type="submit" class="checkout-btn">Place Order</button>
        </form>

        </form>

        <button onclick="window.location.href='cart.php'" class="back-to-cart-btn">Back to Cart</button>
    </div>

    <!-- Footer -->
    <footer>
        &copy; 2025 McNeese State University Bookstore | All Rights Reserved
    </footer>

</body>
</html>
