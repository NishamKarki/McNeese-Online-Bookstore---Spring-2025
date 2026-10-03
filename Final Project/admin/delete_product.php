<?php
// Include database connection
require '../includes/db_connect.php';

if (isset($_GET['ProductID'])) {
    $productID = $_GET['ProductID'];

    // Delete product from the database
    $stmt = $pdo->prepare("DELETE FROM products WHERE ProductID = ?");
    $stmt->execute([$productID]);

    // Redirect back to the manage products page
    header("Location: manage_products.php");
    exit();
}
?>
