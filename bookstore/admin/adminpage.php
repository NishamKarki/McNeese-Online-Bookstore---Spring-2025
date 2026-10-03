<?php
session_start();
require '../includes/db_connect.php';

// Fetch total sales
$salesStmt = $pdo->query("SELECT SUM(TotalPrice) AS total_sales FROM Orders");
$totalSales = $salesStmt->fetch(PDO::FETCH_ASSOC)['total_sales'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../js/admin.js" defer></script> <!-- Load JavaScript -->
    <title>Admin Dashboard - McNeese Bookstore</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/adminpage.css">
</head>

<body>

    <!-- Header -->
    <header>
        <h1>Admin Dashboard</h1>
        <div class="auth-buttons">
            <form method="POST" action="../logout.php" style="display: inline;">
                <button type="submit">Logout</button>
            </form>
        </div>
    </header>

    <!-- Admin Container -->
    <div class="admin-container">

        <!-- Total Sales Section -->
        <h2>Total Sales: $<?= number_format($totalSales, 2) ?></h2>

        <!-- Product Management -->
        <div class="admin-section">
            <h3>Manage Products</h3>
            <form id="addProductForm">
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
                <tbody id="productTable">
                    <!-- Products will be dynamically inserted here -->
                </tbody>
            </table>
        </div>

        <!-- User Management -->
        <div class="admin-section">
            <h3>Manage Users</h3>
            <table>
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="userTableBody">
                    <!-- Populated by JavaScript -->
                </tbody>
            </table>

        </div>

    </div>

    <!-- Footer -->
    <footer>
        &copy; 2025 McNeese State University Bookstore | All Rights Reserved
    </footer>

</body>
</html>
