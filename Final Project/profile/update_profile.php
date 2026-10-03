<?php
session_start();
require '../includes/db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$userID = $_SESSION['user_id'];

// Get the updated profile data
$firstName = $_POST['FirstName'];
$lastName = $_POST['LastName'];
$email = $_POST['Email'];
$gender = $_POST['Gender'];
$birthday = $_POST['Birthday'];
$phone = $_POST['PhoneNumber'] ?? null;
$address = $_POST['Address'];

// Prepare the SQL query to update user profile
$stmt = $pdo->prepare("UPDATE users SET FirstName = ?, LastName = ?, Gender = ?, Birthday = ?, PhoneNumber = ?, Address = ? WHERE UserID = ?");
$stmt->execute([$firstName, $lastName, $gender, $birthday, $phone, $address, $userID]);

// Redirect back to the profile page after updating
header("Location: profile.php");
exit();
?>
