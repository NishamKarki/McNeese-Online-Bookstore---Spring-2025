<?php
session_start();
require 'includes/db_connect.php';

// Fetch products
$productStmt = $pdo->query("SELECT * FROM Products");
$products = $productStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch users
$userStmt = $pdo->query("SELECT * FROM Users");
$users = $userStmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total sales
$salesStmt = $pdo->query("SELECT SUM(TotalPrice) AS total_sales FROM Orders");
$totalSales = $salesStmt->fetch(PDO::FETCH_ASSOC)['total_sales'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="admin.js" defer></script> <!-- Admin JavaScript -->
    <title>Admin Dashboard - McNeese Bookstore</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/adminpage.css">
    
</head>

<body>

    <!-- Header -->
    <header>
        <h1>Admin Dashboard</h1>
    </header>

    <!-- Admin Container -->
    <div class="admin-container">

        <!-- ✅ Total Sales Section -->
        <h2>Total Sales: $<?= number_format($totalSales, 2) ?></h2>

        <!-- ✅ Product Management -->
        <div class="admin-section">
            <h3>Manage Products</h3>
            <form action="admin_process.php" method="POST">
                <input type="text" name="productName" class="admin-input" placeholder="Product Name" required>
                <input type="number" name="productPrice" class="admin-input" placeholder="Price ($)" required>
                <input type="text" name="productImage" class="admin-input" placeholder="Image Filename (e.g., notebook.jpg)" required>
                <button type="submit" class="admin-btn">Add Product</button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><img src="images/<?= htmlspecialchars($product['ImagePath']) ?>" width="50"></td>
                            <td><?= htmlspecialchars($product['ProductName']) ?></td>
                            <td>$<?= number_format($product['Price'], 2) ?></td>
                            <td>
                                <form action="admin_process.php" method="POST">
                                    <input type="hidden" name="deleteProduct" value="<?= $product['ProductID'] ?>">
                                    <button type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- ✅ User Management -->
        <div class="admin-section">
            <h3>Manage Users</h3>
            <table>
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['Email']) ?></td>
                            <td>
                                <form action="admin_process.php" method="POST">
                                    <input type="hidden" name="deleteUser" value="<?= $user['UserID'] ?>">
                                    <button type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>

    <!-- Footer -->
    <footer>
        &copy; 2024 McNeese State University Bookstore | All Rights Reserved
    </footer>

</body>
</html>
