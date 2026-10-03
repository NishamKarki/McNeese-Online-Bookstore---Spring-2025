<?php
require '../includes/db_connect.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$searchTerm = "%$search%";

$stmt = $pdo->prepare("SELECT ProductID, ProductName, Description, Price, Stock, CategoryID, ImagePath 
                       FROM Products 
                       WHERE CategoryID = 7 AND ProductName LIKE ?");
$stmt->execute([$searchTerm]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($products);
?>
