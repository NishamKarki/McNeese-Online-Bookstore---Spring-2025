<?php
require 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Adding a product
    if (isset($_POST['productName'], $_POST['productPrice'], $_POST['productImage'])) {
        $name = $_POST['productName'];
        $price = $_POST['productPrice'];
        $image = $_POST['productImage'];

        $stmt = $pdo->prepare("INSERT INTO Products (ProductName, Price, ImagePath) VALUES (?, ?, ?)");
        $stmt->execute([$name, $price, $image]);

        header("Location: adminpage.php");
        exit();
    }

    // Deleting a product
    if (isset($_POST['deleteProduct'])) {
        $productId = $_POST['deleteProduct'];

        $stmt = $pdo->prepare("DELETE FROM Products WHERE ProductID = ?");
        $stmt->execute([$productId]);

        header("Location: adminpage.php");
        exit();
    }

    // Deleting a user
    if (isset($_POST['deleteUser'])) {
        $userId = $_POST['deleteUser'];

        $stmt = $pdo->prepare("DELETE FROM Users WHERE UserID = ?");
        $stmt->execute([$userId]);

        header("Location: adminpage.php");
        exit();
    }
}

header("Location: adminpage.php");
exit();
?>
