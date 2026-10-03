<?php
require '../includes/db_connect.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$searchTerm = "%$search%";

// Fetch textbooks (CategoryID 2 and 5)
$stmt = $pdo->prepare("SELECT ProductID, ProductName, Description, Price, ImagePath 
                       FROM Products 
                       WHERE (CategoryID = 2 OR CategoryID = 5) AND ProductName LIKE ?");
$stmt->execute([$searchTerm]);

header('Content-Type: application/json');
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
