<?php
// Include database connection
require 'includes/db_connect.php';

// Fetch apparel products from the database
$query = "SELECT * FROM Products WHERE ProductType = 'Apparel'";
$params = [];

// Check if a search query is present
if (!empty($_GET['search'])) {
    $query .= " AND (ProductName LIKE ? OR Description LIKE ?)";
    $searchTerm = "%" . $_GET['search'] . "%";
    array_push($params, $searchTerm, $searchTerm);
}

// Prepare and execute the query
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>McNeese Bookstore - Apparel</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/apparels.css"> <!-- Link to your external CSS file -->
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

    <!-- Navigation Bar -->
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
        <h1>Find Your Essentials!</h1>
    </div>

    <!-- Search Bar -->
    <div class="search-bar">
        <form method="GET" action="apparels.php">
            <input type="text" name="search" placeholder="Search apparel (e.g., Hoodie, T-shirt, Cap)"
                   value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <h2 style="text-align: center;">Available Apparel</h2>

        <section class="apparel-section">
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                    <div class="item">
                        <img src="<?= !empty($product['ImagePath']) ? htmlspecialchars($product['ImagePath']) : 'images/blank.png'; ?>" 
                            alt="<?= htmlspecialchars($product['ProductName']) ?>" 
                            onerror="this.onerror=null; this.src='images/no-image.jpg';">
                        
                        <h3><?= htmlspecialchars($product['ProductName']) ?></h3>
                        <p>$<?= number_format($product['Price'], 2) ?></p>
                        <form method="POST" action="cart.php">
                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['ProductID']) ?>">
                            <button type="submit">Add to Cart</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center;">No apparel items available.</p>
            <?php endif; ?>
        </section>
    </div>

</body>

</html>
