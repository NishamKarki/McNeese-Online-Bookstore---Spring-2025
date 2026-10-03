<?php
session_start();
require '../includes/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT p.ProductID, p.ProductName, p.Price, p.ImagePath
    FROM wishlist w
    JOIN products p ON w.ProductID = p.ProductID
    WHERE w.UserID = ?
");
$stmt->execute([$userId]);
$wishlistItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Wishlist</title>
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/wishlist.css">
    <script src="../js/wishlist.js" defer></script>
    <script src="../js/apparels.js" defer></script>
</head>
<body>
    <main>
    <div class="page-banner">
        <h2>Your Wishlist</h2>
        <div class="auth-buttons">
                <button onclick="window.location.href='../index.php'">Back To Home</button>
        </div>
    </div>

        <?php if (count($wishlistItems) === 0): ?>
            <p>No items in your wishlist.</p>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach ($wishlistItems as $item): ?>
                    <div class="product-card">
                        <img src="/bookstore/<?= htmlspecialchars($item['ImagePath']) ?>" alt="<?= htmlspecialchars($item['ProductName']) ?>">
                        <h3><?= htmlspecialchars($item['ProductName']) ?></h3>
                        <p>$<?= number_format($item['Price'], 2) ?></p>
                        <button class="remove-from-wishlist-btn" data-id="<?= $item['ProductID'] ?>">Remove from Wishlist</button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
