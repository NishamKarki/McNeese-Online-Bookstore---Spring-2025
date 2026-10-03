<?php
session_start();
require '../includes/db_connect.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$userID = $_SESSION['user_id'];

// Make sure the userImage directory exists
$uploadDir = '../userImage/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    $fileTmp = $_FILES['profile_image']['tmp_name'];
    $fileName = basename($_FILES['profile_image']['name']);

    // Prevent file name collisions
    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
    $safeName = 'user_' . $userID . '_' . time() . '.' . $fileExt;
    $uploadPath = $uploadDir . $safeName;

    // Move uploaded file
    if (move_uploaded_file($fileTmp, $uploadPath)) {
        // Save relative path to database
        $relativePath = '/bookstore/userImage/' . $safeName;
        $stmt = $pdo->prepare("UPDATE users SET ProfilePicture = ? WHERE UserID = ?");
        $stmt->execute([$relativePath, $userID]);

        header("Location: profile.php");
        exit();
    } else {
        echo "Upload failed. Could not move file.";
    }
} else {
    echo "No file selected or an error occurred.";
}
?>
