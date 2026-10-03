<?php
session_start();
require '../includes/db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    // Redirect to admin login if session not set
    header("Location: ../adminlogin.php");
    exit();
}

$admin_id = $_SESSION['admin_id'];

// Fetch admin metrics (total orders, total sales, total products)
$stmt = $pdo->prepare("SELECT COUNT(*) as total_orders, SUM(TotalPrice) as total_sales FROM orders");
$stmt->execute(); 
$metrics = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT COUNT(*) as total_products FROM products");
$stmt->execute();
$productCount = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch admin user profile data
$userStmt = $pdo->prepare("SELECT * FROM users WHERE UserID = ?");
$userStmt->execute([$admin_id]);
$user = $userStmt->fetch(PDO::FETCH_ASSOC);

// Check if user data was found
if (!$user) {
    echo "Admin profile not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - McNeese Bookstore</title>
    <link rel="stylesheet" href="/Bookstore/css/global.css">
    <link rel="stylesheet" href="/Bookstore/css/adminpage.css?v=2">
</head>
<body>

    <header>
        <h1>Welcome to Admin Dashboard</h1>
            <div class="auth-buttons">
                <button onclick="window.location.href='../logout.php'">Logout</button>
            </div>
        </nav>
    </header>

    <!-- User Profile Card -->
    <div class="card">
        <h2 style="text-align: center;">Admin Profile</h2>
            <div class="user-profile-info">
                <img src="<?= htmlspecialchars($user['ProfilePicture'] ?: '/bookstore/userImage/noImage.jpg') ?>" 
                    alt="Profile Picture" class="user-profile-image"
                    onerror="this.onerror=null; this.src='/bookstore/userImage/noImage.jpg';">
                <div class="user-profile-details">
                    <p><strong>Name:</strong> <?= htmlspecialchars($user['FirstName'] . ' ' . $user['LastName']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($user['Email']) ?></p>
                    <p><strong>Gender:</strong> <?= htmlspecialchars($user['Gender']) ?></p>
                    <p><strong>Birthday:</strong> <?= date('F j, Y', strtotime($user['Birthday'])) ?></p>
                    <p><strong>Address:</strong> <?= htmlspecialchars($user['Address'] ?? 'Not Provided') ?></p>
                </div>
                <!-- <a href="edit_profile.php" class="edit-profile-btn">Edit Profile</a> -->
            </div>
    </div>
    <section class="admin-dashboard">
        <div class="card">
            <h2 style="text-align: center;">Store Summary</h2>
            
            <div class="summary-container">
                <div class="summary-wrapper">
                    <div class="store-summary">
                        <div class="summary-item">
                        <h3>Total Orders: <span><?= $metrics['total_orders'] ?></span></h3>
                        </div>
                        <div class="summary-item">
                        <h3>Total Sales: <span>$<?= number_format($metrics['total_sales'], 2) ?></span></h3>
                        </div>
                        <div class="summary-item">
                        <h3>Total Products: <span><?= $productCount['total_products'] ?></span></h3>
                    </div>
                </div></div>


                <h2 style="text-align: center;">Manage Items</h2>
                <div class="store-summary manage-box">
                    <div class="check-item">
                        <h3>Manage Products:</h3>
                        <a href="manage_products.php">➤ View All Products</a>

                        <h3>Manage Users:</h3>
                        <a href="view_users.php">➤ View All Users</a>

                        <h3>View All Orders:</h3>
                        <a href="view_orders.php">➤ View Orders</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        &copy; 2025 McNeese State University Bookstore | All Rights Reserved
    </footer>

</body>
</html>