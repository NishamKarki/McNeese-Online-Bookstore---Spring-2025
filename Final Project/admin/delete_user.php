<?php
require '../includes/db_connect.php';
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE UserID = ?");
    $stmt->execute([$_GET['id']]);
}
header("Location: view_users.php");
exit();
