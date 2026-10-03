<?php
session_start();
require 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Remove item from cart
    if (isset($_POST['removeProduct'])) {
        $productId = $_POST['removeProduct'];
        unset($_SESSION['cart'][$productId]);
    }

    // Clear cart
    if (isset($_POST['clearCart'])) {
        $_SESSION['cart'] = [];
    }

    header("Location: cart.php");
    exit();
}
