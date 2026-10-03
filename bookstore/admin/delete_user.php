<?php
session_start();
require '../includes/db_connect.php';

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$response = [];

if ($_SERVER['REQUEST_METHOD'] === "POST" && !empty($data['userID'])) {
    $stmt = $pdo->prepare("DELETE FROM Users WHERE UserID = ?");
    $stmt->execute([$data['userID']]);
    $response['message'] = "User deleted successfully!";
}

echo json_encode($response);
?>
