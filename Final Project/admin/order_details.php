<?php
session_start();
require '../includes/db_connect.php';

if (!isset($_GET['order_id'])) {
    echo "Order ID not specified.";
    exit();
}

$orderID = $_GET['order_id'];

// Get order and user details
$stmt = $pdo->prepare("
    SELECT o.OrderID, o.UserID, o.TotalPrice, o.OrderDate, 
           u.FirstName, u.LastName, u.Email 
    FROM orders o
    JOIN users u ON o.UserID = u.UserID
    WHERE o.OrderID = ?
");
$stmt->execute([$orderID]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Order not found.";
    exit();
}

// Get ordered items
$itemStmt = $pdo->prepare("
    SELECT p.ProductName, p.Price, oi.Quantity
    FROM orderitems oi
    JOIN products p ON oi.ProductID = p.ProductID
    WHERE oi.OrderID = ?
");
$itemStmt->execute([$orderID]);
$items = $itemStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details - McNeese Bookstore</title>
    <link rel="stylesheet" href="/Bookstore/css/global.css">
    <link rel="stylesheet" href="/Bookstore/css/order_details.css">
</head>
<body>
    <div class="order-details-container">
        <h2>Order #<?= $order['OrderID'] ?> Details</h2>
        <p><strong>Customer:</strong> <?= htmlspecialchars($order['FirstName'] . ' ' . $order['LastName']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($order['Email']) ?></p>
        <p><strong>Date:</strong> <?= date('F j, Y, g:i a', strtotime($order['OrderDate'])) ?></p>
        <p><strong>Total:</strong> $<?= number_format($order['TotalPrice'], 2) ?></p>

        <h3>Items in this Order:</h3>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price Each</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['ProductName']) ?></td>
                        <td>$<?= number_format($item['Price'], 2) ?></td>
                        <td><?= $item['Quantity'] ?></td>
                        <td>$<?= number_format($item['Price'] * $item['Quantity'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="view_orders.php" class="back-btn">← Back to All Orders</a>
    </div>
</body>
</html>
