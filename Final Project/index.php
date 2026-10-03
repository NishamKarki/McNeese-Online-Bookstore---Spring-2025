<?php 
session_start();
require 'includes/db_connect.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>McNeese Bookstore</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/index.css">
    <script src="js/index.js?v=2" defer></script>
    <script src="js/wishlist.js"></script>


<body>

    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    ?>

    <header>
        <h1>McNeese State University Bookstore</h1>

        <?php if (isset($_SESSION['user_firstname'])): ?>
            <div class="user-welcome" style="color: #fff; font-weight: bold;">
                Welcome, <?= htmlspecialchars($_SESSION['user_firstname']) ?>
            </div>
        <?php endif; ?>

        <div class="auth-buttons">
        <a href="/bookstore/cart/cart.php">
            <button>
                🛒 Cart <span id="cart-count">0</span>
            </button>
        </a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/bookstore/cart/order_history.php">
                <button>📦 Order History</button>
            </a>
            <button onclick="window.location.href='/bookstore/profile/profile.php'">Profile</button>
            <button onclick="window.location.href='logout.php'">Logout</button>
        <?php else: ?>
            <button onclick="window.location.href='login.php'">Login</button>
            <button onclick="window.location.href='signup.php'">Sign Up</button>
        <?php endif; ?>
    </div>

    </header>

    <!-- Navigation -->
    <nav>
        <a href="index.php">Home</a>
        <a href="product/apparels.php">Merchandise</a>
        <a href="product/textbooks.php">Books & Stationery</a>
        <a href="product/supplies.php">Supplies</a>
        <a href="product/wishlist.php">Wishlist</a>
        <!-- <a href="contactus.php">Contact Us</a> -->
    </nav>

    <!-- Hero Section -->
    <div class="hero">
        <h1>Welcome to the McNeese Bookstore!</h1>
    </div>

    <div class="content-wrapper">
        <!-- Search & Filter Bar -->
        <div class="search-bar">
            <form id="searchForm">
                <input type="text" name="search" id="searchInput" placeholder="Search for books, apparel, and more...">
                
                <!-- Dropdown for Categories -->
                <select name="category" id="categorySelect">
                    <option value="">All Categories</option>
                </select>

                <!-- Price Range Filter -->
                <select name="price" id="priceSelect">
                    <option value="">All Prices</option>
                    <option value="0-20">$0 - $20</option>
                    <option value="20-50">$20 - $50</option>
                    <option value="50-100">$50 - $100</option>
                    <option value="100-500">$100 - $500</option>
                </select>

                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Product Section -->
        <section class="products" id="productList">
            <!-- Products will be dynamically inserted here -->
        </section>
    </div>

    <!-- Footer -->
    <footer>
        &copy; 2025 McNeese State University Bookstore | All Rights Reserved 
        <p><a href="adminlogin.php">Admin</a></p>
    </footer>

</body>
</html>
