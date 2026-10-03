<?php
require '../includes/db_connect.php';

try {
    $stmt = $pdo->query("SELECT UserID, FirstName, LastName, Email, CreatedAt FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
} catch (PDOException $e) {
    echo json_encode(["error" => "Failed to fetch users: " . $e->getMessage()]);
}
?>
