<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Furniture House</title>
    <link rel="stylesheet" href="./CSS/style.css">
</head>
<body>

<header class="header">
    <div class="container nav">
        <a href="index.php" class="logo">Furniture<span>House</span></a>

        <nav>
            <a href="index.php">Home</a>
            <a href="products.php">Products</a>
            <a href="category.php">Categories</a>

            <?php if (isset($_SESSION['auth'])): ?>
                <a href="add-product.php">Add Product</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>