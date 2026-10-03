<?php
session_start();
require 'includes/db_connect.php';

header("Content-Type: application/json");

// Process POST request only
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = isset($_POST['firstName']) ? trim($_POST['firstName']) : '';
    $lastName = isset($_POST['lastName']) ? trim($_POST['lastName']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $confirmPassword = isset($_POST['confirmPassword']) ? trim($_POST['confirmPassword']) : '';
    $role = isset($_POST['role']) ? trim($_POST['role']) : 'student'; // Default role is student

    // Ensure all required fields are filled
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmPassword)) {
        echo json_encode(["error" => "Please fill in all fields."]);
        exit();
    }

    // Ensure passwords match
    if ($password !== $confirmPassword) {
        echo json_encode(["error" => "Passwords do not match."]);
        exit();
    }

    // Hash the password before storing it in the database
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into the database
    $stmt = $pdo->prepare("INSERT INTO users (FirstName, LastName, Email, Password, Role) VALUES (?, ?, ?, ?, ?)");
    if ($stmt->execute([$firstName, $lastName, $email, $hashedPassword, $role])) {
        echo json_encode(["success" => "Account created successfully!", "redirect" => "login.php"]);
    } else {
        echo json_encode(["error" => "Signup failed. Try again!"]);
    }
    exit();
}
?>
