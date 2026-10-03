<?php
// Include database connection
require 'includes/db_connect.php';

// Fetch categories for the dropdown
$categoryStmt = $pdo->query("SELECT * FROM Categories");
$categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);

// Initialize query
$query = "SELECT * FROM Products WHERE 1=1";
$params = [];

// Check if search term is provided
if (!empty($_GET['search'])) {
    $query .= " AND (ProductName LIKE ? OR Description LIKE ?)";
    $searchTerm = "%" . $_GET['search'] . "%";
    array_push($params, $searchTerm, $searchTerm);
}

// Check if category filter is applied
if (!empty($_GET['category'])) {
    $query .= " AND CategoryID = ?";
    array_push($params, $_GET['category']);
}

// Check if price range filter is applied
if (!empty($_GET['price'])) {
    $priceRange = explode("-", $_GET['price']);
    if (count($priceRange) == 2) {
        $query .= " AND Price BETWEEN ? AND ?";
        array_push($params, $priceRange[0], $priceRange[1]);
    }
}

// Prepare and execute query
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>McNeese Bookstore</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/index.css">
</head>

<body>

    <!-- Header -->
    <header>
        <h1>McNeese State University Bookstore</h1>
        <div class="auth-buttons">
            <button onclick="window.location.href='login.php'">Login</button>
            <button onclick="window.location.href='signup.php'">Sign Up</button>
        </div>
    </header>

    <!-- Navigation -->
    <nav>
        <a href="index.php">Home</a>
        <a href="textbooks.php">Textbooks</a>
        <a href="apparels.php">Apparel</a>
        <a href="supplies.php">Supplies</a>
        <a href="help.php">Help</a>
        <a href="contact.php">Contact Us</a>
    </nav>

    <!-- Hero Section -->
    <div class="hero">
        <h1>Welcome to the McNeese Bookstore!</h1>
    </div>

    <div class="content-wrapper">
        <!-- Search & Filter Bar -->
        <div class="search-bar">
            <form method="GET" action="index.php">
                <input type="text" name="search" placeholder="Search for books, apparel, and more..."
                    value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                
                <!-- Dropdown for Categories -->
                <select name="category">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['CategoryID'] ?>" 
                            <?= (isset($_GET['category']) && $_GET['category'] == $cat['CategoryID']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['CategoryName']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Price Range Filter -->
                <select name="price">
                    <option value="">All Prices</option>
                    <option value="0-20" <?= (isset($_GET['price']) && $_GET['price'] == "0-20") ? 'selected' : '' ?>>$0 - $20</option>
                    <option value="20-50" <?= (isset($_GET['price']) && $_GET['price'] == "20-50") ? 'selected' : '' ?>>$20 - $50</option>
                    <option value="50-100" <?= (isset($_GET['price']) && $_GET['price'] == "50-100") ? 'selected' : '' ?>>$50 - $100</option>
                    <option value="100-500" <?= (isset($_GET['price']) && $_GET['price'] == "100-500") ? 'selected' : '' ?>>$100 - $500</option>
                </select>

                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Featured Products Section -->
        <section class="products">
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                    <img src="<?= !empty($product['ImagePath']) ? htmlspecialchars($product['ImagePath']) : 'images/blank.png'; ?>" 
                        alt="<?= htmlspecialchars($product['ProductName']) ?>" 
                        onerror="this.onerror=null; this.src='images/no-image.jpg';">


                        <h3><?= htmlspecialchars($product['ProductName']) ?></h3>
                        <p>$<?= number_format($product['Price'], 2) ?></p>
                        <a href="product_detail.php?id=<?= $product['ProductID'] ?>">View Details</a>
                        <form method="POST" action="cart.php">
                            <input type="hidden" name="product_id" value="<?= $product['ProductID'] ?>">
                            <button type="submit">Add to Cart</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No products available.</p>
            <?php endif; ?>
        </section>
    </div>

    <!-- Footer -->
    <footer>
        &copy; 2024 McNeese State University Bookstore | All Rights Reserved
    </footer>

</body>
</html>
