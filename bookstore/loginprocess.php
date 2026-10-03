<?php
session_start();
require 'includes/db_connect.php'; 

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (empty($email) || empty($password)) {
        echo json_encode(["error" => "Please fill in all fields."]);
        exit();
    }

    // Check if the email exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE Email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['Password'])) {
        $_SESSION['user_id'] = $user['UserID'];
        $_SESSION['user_role'] = $user['Role'];
        $_SESSION['user_email'] = $user['Email'];
        $_SESSION['user_firstname'] = $user['FirstName'];
        $_SESSION['user_lastname'] = $user['LastName'];


        $stmt = $pdo->prepare("SELECT CartID FROM cart WHERE UserID = ? ORDER BY CartID DESC LIMIT 1");
        $stmt->execute([$user['UserID']]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($cart) {
            $cartId = $cart['CartID'];

            $stmt = $pdo->prepare("SELECT ProductID, Quantity FROM cartitems WHERE CartID = ?");
            $stmt->execute([$cartId]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $_SESSION['cart'] = []; // Clear any existing session cart
            foreach ($items as $item) {
                $_SESSION['cart'][$item['ProductID']] = $item['Quantity'];
            }
        }

        if ($user['Role'] === 'admin') {
            echo json_encode(["success" => "Login successful!", "redirect" => "admin_dashboard.php"]);
        } else {
            echo json_encode(["success" => "Login successful!", "redirect" => "index.php"]);
        }
    } else {
        echo json_encode(["error" => "Invalid email or password."]);
    }
    exit();
}
?>
