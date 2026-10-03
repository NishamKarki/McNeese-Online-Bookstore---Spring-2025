<?php
session_start();
require '../includes/db_connect.php';

header('Content-Type: application/json');

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in."]);
    exit;
}

$userID = $_SESSION['user_id'];

// Retrieve and sanitize input
$cardholder = $_POST['cardholder'] ?? '';
$cardnumber = $_POST['cardnumber'] ?? '';
$expMonth = $_POST['expMonth'] ?? '';
$expYear = $_POST['expYear'] ?? '';
$billingAddress = $_POST['billingAddress'] ?? '';
$paymentType = $_POST['paymentType'] ?? 'Credit';

if (!$cardholder || !$cardnumber || !$expMonth || !$expYear || !$billingAddress) {
    echo json_encode(["success" => false, "message" => "Missing payment fields."]);
    exit;
}

try {
    // Insert into paymentmethods
    $stmt = $pdo->prepare("INSERT INTO paymentmethods 
        (UserID, CardHolderName, EncryptedCardNumber, ExpirationMonth, ExpirationYear, BillingAddress, PaymentType) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");

    // For now, store card number as plain text
    $stmt->execute([$userID, $cardholder, $cardnumber, $expMonth, $expYear, $billingAddress, $paymentType]);

    $paymentMethodID = $pdo->lastInsertId();

    // Retrieve latest order ID
    $orderStmt = $pdo->prepare("SELECT OrderID, TotalAmount FROM orders WHERE UserID = ? ORDER BY OrderID DESC LIMIT 1");
    $orderStmt->execute([$userID]);
    $order = $orderStmt->fetch();

    if (!$order) {
        echo json_encode(["success" => false, "message" => "No active order found."]);
        exit;
    }

    $orderID = $order['OrderID'];
    $amount = $order['TotalAmount'];

    // Insert into payments
    $payStmt = $pdo->prepare("INSERT INTO payments 
        (OrderID, PaymentAmount, PaymentDate, PaymentMethodID, PaymentStatus) 
        VALUES (?, ?, NOW(), ?, ?)");

    $payStmt->execute([$orderID, $amount, $paymentMethodID, 'Completed']);

    echo json_encode(["success" => true, "message" => "Payment recorded successfully."]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
