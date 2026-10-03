<?php
require '../includes/db_connect.php';

header("Content-Type: application/json");

$stmt = $pdo->query("SELECT CategoryID, CategoryName FROM Categories");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($categories);
?>
