<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>McNeese Contact Us</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/contactus.css">
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
            <?php if (isset($_SESSION['user_id'])): ?>
                <button onclick="window.location.href='logout.php'">Logout</button>
            <?php else: ?>
                <button onclick="window.location.href='login.php'">Login</button>
                <button onclick="window.location.href='signup.php'">Sign Up</button>
            <?php endif; ?>
        </div>
    </header>

    <!-- Navigation Bar -->
    <nav>
        <a href="index.php">Home</a>
        <a href="product/apparels.php">Merchandise</a>
        <a href="product/textbooks.php">Textbooks</a>
        <a href="product/supplies.php">Supplies</a>
        <a href="contactus.php">Contact Us</a>
    </nav>

    <!-- Hero Section -->
    <div class="hero">
        <h1>We'd Love to Hear from You!</h1>
    </div>

    <!-- Contact Form Section -->
    <div class="content-wrapper">
        <div class="contact-container">
            <h2>Get in Touch</h2>
            <form action="#" method="POST">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Your Name" required>

                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Your Email" required>

                <label for="subject">Subject</label>
                <input type="text" id="subject" name="subject" placeholder="Subject" required>

                <label for="message">Message</label>
                <textarea id="message" name="message" placeholder="Write your message here..." required></textarea>

                <button type="submit">Send Message</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        &copy; 2024 McNeese State University | All Rights Reserved
    </footer>
</body>
</html>
