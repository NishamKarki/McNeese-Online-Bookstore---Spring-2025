<?php
session_start();
require 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        // Check if email exists in the admin table
        $stmt = $pdo->prepare("SELECT * FROM users WHERE Email = ? AND Role = 'Admin'");
        $stmt->execute([$email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['Password'])) {
            // Set admin session
            $_SESSION['admin_id'] = $admin['AdminID'];
            $_SESSION['admin_email'] = $admin['Email'];

            header("Location: ../bookstore/admin/adminpage.php"); // Redirect after successful login
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | McNeese Bookstore</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/login.css">
    <script src="js/adminlogin.js" defer></script> 
</head>

<body>
    <div class="container">
        <div class="login-container">
            <h2><em><b>McNeese Bookstore Admin Login</b></em></h2>

            <?php if (isset($error)): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <form method="POST" action="adminlogin.php">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit">Login</button>
            </form>

            <p class="forgot-password"><a href="forgot-password.php">Forgot Password?</a></p>

            <div class="additional-links">
                <p>Go to <a href="index.php">Home Page</a></p>
                <p>Student Login? <a href="login.php">Click Here</a></p>
            </div>

            <footer>&copy; 2025 McNeese State University Bookstore</footer>
        </div>
    </div>
</body>
</html>
