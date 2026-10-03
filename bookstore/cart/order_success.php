<?php
session_start();
require '../includes/db_connect.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Get order ID from the URL
$orderID = $_GET['order_id'] ?? null;

if (!$orderID) {
    header("Location: ../index.php");
    exit();
}

// Fetch order details
$stmt = $pdo->prepare("SELECT * FROM orders WHERE OrderID = ? AND UserID = ?");
$stmt->execute([$orderID, $_SESSION['user_id']]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch order items
$stmt = $pdo->prepare("
    SELECT oi.*, p.ProductName, p.ImagePath 
    FROM orderitems oi
    JOIN Products p ON oi.ProductID = p.ProductID
    WHERE oi.OrderID = ?
");
$stmt->execute([$orderID]);
$orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Redirect if order not found
if (!$order) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success - McNeese Bookstore</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/ordersuccess.css">
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Order Confirmation</h1>
    </header>

    <div class="content-wrapper">
        <h2>Thank you for your order!</h2>
        <p>Your order <strong>#<?= htmlspecialchars($orderID) ?></strong> has been successfully placed.</p>
        <p><strong>Order Total:</strong> $<?= number_format($order['TotalPrice'], 2) ?></p>

        <h3>Order Details</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderItems as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['ProductName']) ?></td>
                        <td><img src="../<?= htmlspecialchars($item['ImagePath']) ?>" width="80"></td>
                        <td><?= $item['Quantity'] ?></td>
                        <td>$<?= number_format($item['Price'] * $item['Quantity'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="../index.php" class="btn">Continue Shopping</a>
    </div>

    <!-- Footer -->
    <footer>
        &copy; 2024 McNeese State University Bookstore | All Rights Reserved
    </footer>

</body>
</html>
