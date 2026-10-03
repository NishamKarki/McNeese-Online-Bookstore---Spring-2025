<?php
require '../includes/db_connect.php';  
header("Content-Type: application/json");

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$price = $_GET['price'] ?? '';

$query = "SELECT * FROM Products WHERE 1=1";
$params = [];

if (!empty($search)) {
    $query .= " AND (ProductName LIKE ? OR Description LIKE ?)";
    $searchTerm = "%$search%";
    array_push($params, $searchTerm, $searchTerm);
}

if (!empty($category)) {
    $query .= " AND CategoryID = ?";
    array_push($params, $category);
}

if (!empty($price)) {
    $priceRange = explode("-", $price);
    if (count($priceRange) == 2) {
        $query .= " AND Price BETWEEN ? AND ?";
        array_push($params, $priceRange[0], $priceRange[1]);
    }
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($products);
?>
