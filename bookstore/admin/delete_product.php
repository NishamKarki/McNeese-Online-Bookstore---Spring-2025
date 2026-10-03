<?php
session_start();
require '../includes/db_connect.php';

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$response = [];

if ($_SERVER['REQUEST_METHOD'] === "POST" && !empty($data['productID'])) {
    $stmt = $pdo->prepare("DELETE FROM Products WHERE ProductID = ?");
    $stmt->execute([$data['productID']]);
    $response['message'] = "Product deleted successfully!";
}

echo json_encode($response);
?>
