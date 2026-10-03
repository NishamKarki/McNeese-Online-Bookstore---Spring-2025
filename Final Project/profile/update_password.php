<?php
session_start();
require '../includes/db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "You must be logged in to change your password."]);
    exit();
}

$userID = $_SESSION['user_id'];

$oldPassword = $_POST['oldPassword'];
$newPassword = $_POST['newPassword'];
$confirmPassword = $_POST['confirmPassword'];

// Basic validations
if ($newPassword !== $confirmPassword) {
    echo json_encode(["error" => "Passwords do not match."]);
    exit();
}

// Get the current password from the database
$stmt = $pdo->prepare("SELECT Password FROM users WHERE UserID = ?");
$stmt->execute([$userID]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode(["error" => "User not found."]);
    exit();
}

$currentPassword = $user['Password']; // Assuming password is stored as a hashed string

// Check if the old password matches
if (!password_verify($oldPassword, $currentPassword)) {
    echo json_encode(["error" => "Old password is incorrect."]);
    exit();
}

// Hash the new password before saving it
$newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

// Update the password in the database
$stmt = $pdo->prepare("UPDATE users SET Password = ? WHERE UserID = ?");
$stmt->execute([$newPasswordHash, $userID]);

echo json_encode(["success" => "Password changed successfully."]);
?>
