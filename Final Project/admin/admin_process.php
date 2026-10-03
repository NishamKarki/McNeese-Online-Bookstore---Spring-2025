<!-- <?php
// session_start();
// require '../includes/db_connect.php';

// header("Content-Type: application/json");

// $data = json_decode(file_get_contents("php://input"), true);
// $response = [];

// if ($_SERVER['REQUEST_METHOD'] === "POST") {
//     // Add Product
//     if (!empty($_POST['productName']) && !empty($_POST['productPrice']) && !empty($_POST['productImage'])) {
//         $stmt = $pdo->prepare("INSERT INTO Products (ProductName, Price, ImagePath) VALUES (?, ?, ?)");
//         $stmt->execute([$_POST['productName'], $_POST['productPrice'], $_POST['productImage']]);
//         $response['message'] = "Product added successfully!";
//     }
//     // Delete Product
//     elseif (!empty($data['deleteProduct'])) {
//         $stmt = $pdo->prepare("DELETE FROM Products WHERE ProductID = ?");
//         $stmt->execute([$data['deleteProduct']]);
//         $response['message'] = "Product deleted successfully!";
//     }
//     // Delete User
//     elseif (!empty($data['deleteUser'])) {
//         $stmt = $pdo->prepare("DELETE FROM Users WHERE UserID = ?");
//         $stmt->execute([$data['deleteUser']]);
//         $response['message'] = "User deleted successfully!";
//     }
// }

// echo json_encode($response);
// ?>
