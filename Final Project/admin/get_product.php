<?php
require '../includes/db_connect.php';

try {
    $stmt = $pdo->query("SELECT ProductID, ProductName, Price, ImagePath FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($products);
} catch (PDOException $e) {
    echo json_encode(["error" => "Failed to fetch products: " . $e->getMessage()]);
}
?>
