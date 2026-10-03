<?php
// Include database connection
require '../includes/db_connect.php';

// Fetch categories for dropdown
$categoryStmt = $pdo->prepare("SELECT CategoryID, CategoryName FROM categories");
$categoryStmt->execute();
$categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productName = $_POST['ProductName'];
    $categoryID = $_POST['CategoryID'];
    $description = $_POST['Description'];
    $price = $_POST['Price'];
    $stock = $_POST['Stock'];

    // Handle uploaded file
    if (isset($_FILES['ImageFile']) && $_FILES['ImageFile']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../userImage/';
        $filename = basename($_FILES['ImageFile']['name']);
        $targetPath = $uploadDir . $filename;

        move_uploaded_file($_FILES['ImageFile']['tmp_name'], $targetPath);

        $imagePath = '/bookstore/userImage/' . $filename; // save relative URL in DB
    } else {
        $imagePath = '/bookstore/userImage/noImage.jpg'; // fallback
    }

    $stmt = $pdo->prepare("INSERT INTO products (ProductName, CategoryID, Description, Price, Stock, ImagePath) 
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$productName, $categoryID, $description, $price, $stock, $imagePath]);

    header("Location: manage_products.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product</title>
    <link rel="stylesheet" href="/Bookstore/css/global.css">
    <link rel="stylesheet" href="/Bookstore/css/add_product.css">
</head>
<body>
    <div class="form-container">
        <div class="page-banner">
            <h2>Add Product</h2>
        </div>

        <form action="add_product.php" method="POST" enctype="multipart/form-data">

            <label>Product Name:</label>
            <input type="text" name="ProductName" required><br>

            <label>Category:</label>
            <select name="CategoryID" required>
                <option value="">-- Select Category --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['CategoryID'] ?>"><?= htmlspecialchars($cat['CategoryName']) ?></option>
                <?php endforeach; ?>
            </select><br>

            <label>Description:</label>
            <textarea name="Description" required></textarea><br>

            <label>Price:</label>
            <input type="number" name="Price" step="0.01" required><br>

            <label>Stock:</label>
            <input type="number" name="Stock" required><br>

            <label for="imageUpload">Upload Image:</label>
            <input type="file" name="ImageFile" id="imageUpload" accept="image/*" required>

            <button type="submit">Add Product</button>
            <button onclick="window.location.href='../admin/adminpage.php'">Back To Admin</button>
        </form>
    </div>
</body>
</html>
