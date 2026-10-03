<?php 
session_start(); 
require '../includes/db_connect.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>McNeese Bookstore - Textbooks</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/apparel.css">
    <script src="../js/textbooks.js" defer></script>
</head>
<body>

    <!-- Header -->
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
                <button onclick="window.location.href='../logout.php'">Logout</button>
            <?php else: ?>
                <button onclick="window.location.href='login.php'">Login</button>
                <button onclick="window.location.href='signup.php'">Sign Up</button>
            <?php endif; ?>
        </div>
    </header>

    <!-- Navigation -->
    <nav>
        <a href="/bookstore/index.php">Home</a>
        <a href="/bookstore/product/apparels.php">Merchandise</a>
        <a href="/bookstore/product/textbooks.php">Textbooks</a>
        <a href="/bookstore/product/supplies.php">Supplies</a>
        <a href="/bookstore/contactus.php">Contact Us</a>
    </nav>

    <!-- Hero Section -->
    <div class="hero">
        <h1>Explore Your Textbooks!</h1>
    </div>

    <!-- Search Bar -->
    <div class="search-bar">
        <form id="searchForm">
            <input type="text" name="search" id="searchInput" placeholder="Search textbooks (e.g., Calculus, C++)">
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <h2 style="text-align: center;">Available Textbooks</h2>
        <section class="products" id="textbookList">
            <!-- Products will be inserted here by textbooks.js -->
        </section>
    </div>

    <!-- Footer -->
    <footer>
        &copy; 2024 McNeese State University Bookstore | All Rights Reserved
    </footer>
</body>
</html>
