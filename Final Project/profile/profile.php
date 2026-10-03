<?php
session_start();
require '../includes/db_connect.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$userID = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT FirstName, LastName, Email, Gender, 
    Birthday, Address, PhoneNumber, CreatedAt, ProfilePicture FROM users WHERE UserID = ?");
$stmt->execute([$userID]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$stmtOrders = $pdo->prepare("SELECT COUNT(*) AS total_orders FROM orders WHERE UserID = ?");
$stmtOrders->execute([$userID]);
$totalOrders = $stmtOrders->fetch(PDO::FETCH_ASSOC)['total_orders'];

$stmtSpent = $pdo->prepare("SELECT SUM(TotalPrice) AS total_spent FROM orders WHERE UserID = ?");
$stmtSpent->execute([$userID]);
$totalSpent = $stmtSpent->fetch(PDO::FETCH_ASSOC)['total_spent'];

$stmt = $pdo->prepare("SELECT p.ProductID, p.ProductName FROM wishlist w JOIN products p ON w.ProductID = p.ProductID WHERE w.UserID = ?");
$stmt->execute([$userID]);
$wishlistItems = $stmt->fetchAll(PDO::FETCH_ASSOC);


if (!$user) {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile - McNeese Bookstore</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/profile.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<script>
document.getElementById('toggleEdit').addEventListener('click', function(e) {
    e.preventDefault();
    const form = document.getElementById('editProfileForm');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
});
</script>

<body>

    <header>
        <h1>Your Profile</h1>
    </header>

    <div class="profile-page">
        <!-- Profile Card Left -->
        <div class="profile-card">
            <div class="profile-header">
                <h2><?= htmlspecialchars($user['FirstName']) ?>'s Profile</h2>
            </div>

            <div class="profile-picture">
                <img src="<?= htmlspecialchars($user['ProfilePicture'] ?: '/bookstore/userImage/noImage.jpg') ?>" 
                    alt="Profile Picture"
                    onerror="this.onerror=null; this.src='/bookstore/userImage/noImage.jpg';">

                <!-- Upload Form -->
                <form action="upload_picture.php" method="POST" enctype="multipart/form-data" class="change-pic-form">
                    <input type="file" name="profile_image" id="profile_image" accept="image/*" required hidden>
                    <label for="profile_image" class="upload-btn">
                        <i class="fas fa-camera"></i> Change Profile Picture
                    </label>
                    <button type="submit" class="submit-btn">Apply</button>
                </form>
            </div>

            <div class="profile-details">
                <p><strong>Name:</strong> <?= htmlspecialchars($user['FirstName'] . ' ' . $user['LastName']) ?></p>
                <p><strong>Gender:</strong> <?= htmlspecialchars($user['Gender']) ?></p>
                <p><strong>Address:</strong> <?= htmlspecialchars($user['Address']) ?></p>
                <p><strong>Joined:</strong> <?= date('M d, Y', strtotime($user['CreatedAt'])) ?></p>
            </div>
        </div>

        <!-- Nav Buttons Right -->
        <div class="nav-container">
            <a href="/bookstore/index.php"><i class="fas fa-arrow-left"></i> Home</a>
            <a href="/bookstore/cart/cart.php"><i class="fas fa-shopping-cart"></i> Cart</a>
            <a href="/bookstore/cart/order_history.php"><i class="fas fa-box-open"></i> Order History</a>
            <a href="/bookstore/logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>

        <div class="center-edit-box">
            <div class="edit-header">
                <h3>✏️ Edit Profile Information</h3>

            </div>

            <form id="editProfileForm" method="POST" action="update_profile.php">
                <div class="edit-table-row"><label>First Name:</label><input type="text" name="FirstName" value="<?= htmlspecialchars($user['FirstName']) ?>"></div>
                <div class="edit-table-row"><label>Last Name:</label><input type="text" name="LastName" value="<?= htmlspecialchars($user['LastName']) ?>"></div>
                <div class="edit-table-row">
                    <label>Email:</label>
                    <input type="email" name="Email" value="<?= htmlspecialchars($user['Email']) ?>" readonly>
                </div>
                <div class="edit-table-row"><label>Gender:</label>
                    <select name="Gender">
                        <option <?= $user['Gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                        <option <?= $user['Gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                        <option <?= $user['Gender'] == 'Other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>
                <div class="edit-table-row"><label>Birthday:</label><input type="date" name="Birthday" value="<?= $user['Birthday'] ?>"></div>
                <div class="edit-table-row"><label>Phone Number:</label><input type="text" name="PhoneNumber" value="<?= htmlspecialchars($user['PhoneNumber'] ?? '') ?>"></div>
                <div class="edit-table-row"><label>Address:</label><input type="text" name="Address" value="<?= htmlspecialchars($user['Address'] ?? '') ?>"></div>
                <div class="form-row">
                    <button type="submit" class="submit-btn">Save Changes</button>
                </div>
                <a href="change_password.php" class="change-password-btn">Change Password</a>
            </form>
        </div>


        <div class="profile-sidebar">

            <!-- Center Profile Info Box -->
            <div class="profile-centerbox">
                <h3>📜 Account Summary</h3>
                <p><strong>Total Orders:</strong> <?= $totalOrders ?></p>
                <p><strong>Total Spent:</strong> $<?= number_format($totalSpent, 2) ?></p>
            </div>

            <h3><a href="/bookstore/product/wishlist.php" style="color: inherit; text-decoration: none;">📁 Saved Items / Wishlist</a></h3>
            <ul class="wishlist-list">
                <?php
                $maxSlots = 5;
                $limitedItems = array_slice($wishlistItems, 0, $maxSlots);

                for ($i = 0; $i < $maxSlots; $i++) {
                    echo '<li>';
                    if (isset($limitedItems[$i])) {
                        echo "<span><strong>" . ($i + 1) . ".</strong> " . htmlspecialchars($limitedItems[$i]['ProductName']) . "</span>";
                        echo '<a href="remove_wishlist.php?product=' . $limitedItems[$i]['ProductID'] . '"></a>';
                    } else {
                        echo "<span><strong>" . ($i + 1) . ".</strong> <em>Empty</em></span>";
                    }
                    echo '</li>';
                }
                ?>
            </ul>

            <h3>📍 Sipping Address</h3>
            <div class="address">
                <?= htmlspecialchars($user['Address'] ?? 'Not provided') ?>
            </div>
        </div>

    </div>

    <footer>
        &copy; 2025 McNeese State University Bookstore | All Rights Reserved
    </footer>

</body>
</html>
