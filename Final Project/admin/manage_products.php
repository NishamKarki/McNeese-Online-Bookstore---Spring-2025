<?php
// Include database connection
require '../includes/db_connect.php';

// Search term
$searchTerm = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch products with category name
$sql = "SELECT p.*, c.CategoryName 
        FROM products p 
        JOIN categories c ON p.CategoryID = c.CategoryID 
        WHERE p.ProductName LIKE :search 
        LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total product count for pagination
$stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE ProductName LIKE :search");
$stmt->bindValue(':search', $searchTerm, PDO::PARAM_STR);
$stmt->execute();
$totalProducts = $stmt->fetchColumn();
$totalPages = ceil($totalProducts / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products</title>
    <link rel="stylesheet" href="/Bookstore/css/global.css">
    <link rel="stylesheet" href="/Bookstore/css/manage_products.css?v=4">
</head>
<body>
    <div class="page-banner">
        <h1>Manage Products</h1>
        <div class="auth-buttons">
                <button onclick="window.location.href='adminpage.php'">Admin Dashboard</button>
        </div>
    </div>

    <!-- Search Form -->
    <form action="manage_products.php" method="GET">
        <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="Search by product name">
        <button type="submit">Search</button>
    </form>

    <!-- Product Table -->
    <table>
        <tr>
            <th>Product Name</th>
            <th>Category</th>
            <th>Description</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>

        <?php if (count($products) === 0): ?>
            <tr><td colspan="6">No products found.</td></tr>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['ProductName']) ?></td>
                    <td><?= htmlspecialchars($product['CategoryName']) ?></td>
                    <td><?= htmlspecialchars($product['Description']) ?></td>
                    <td>$<?= htmlspecialchars(number_format($product['Price'], 2)) ?></td>
                    <td><?= htmlspecialchars($product['Stock']) ?></td>
                    <td>
                        <a href="update_product.php?ProductID=<?= $product['ProductID'] ?>">Edit</a> |
                        <a href="delete_product.php?ProductID=<?= $product['ProductID'] ?>" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>


<div class="product-controls">
    <a href="add_product.php" class="add-btn">➕ Add New Product</a>

    <div class="pagination">
        <a href="?page=1&search=<?= urlencode($_GET['search'] ?? '') ?>">First</a>
        <a href="?page=<?= max(1, $page - 1) ?>&search=<?= urlencode($_GET['search'] ?? '') ?>">Prev</a>
        <span>Page <?= $page ?> of <?= $totalPages ?></span>
        <a href="?page=<?= min($totalPages, $page + 1) ?>&search=<?= urlencode($_GET['search'] ?? '') ?>">Next</a>
        <a href="?page=<?= $totalPages ?>&search=<?= urlencode($_GET['search'] ?? '') ?>">Last</a>
    </div>
</div>

</body>
</html>
