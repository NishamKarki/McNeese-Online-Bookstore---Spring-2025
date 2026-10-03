<?php
session_start();
require '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$userID = $_SESSION['user_id'];

// Fetch user's orders
$stmt = $pdo->prepare("SELECT * FROM orders WHERE UserID = ? ORDER BY OrderDate DESC");
$stmt->execute([$userID]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Order History</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/order_history.css">
</head>
<body>
    <header>
        <h1>Your Order History</h1>
    </header>

    <div class="content-wrapper">
        <?php if (count($orders) === 0): ?>
            <p>You haven’t placed any orders yet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td>#<?= $order['OrderID'] ?></td>
                            <td><?= date("Y-m-d", strtotime($order['OrderDate'])) ?></td>
                            <td>$<?= number_format($order['TotalPrice'], 2) ?></td>
                            <td><?= htmlspecialchars($order['Status']) ?></td>
                            <td><a href="order_success.php?order_id=<?= $order['OrderID'] ?>" class="btn">View</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <footer>
        &copy; 2025 McNeese State University Bookstore | All Rights Reserved
    </footer>
</body>
</html>
