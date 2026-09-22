<?php
require_once "connection.php";

$slug = $_GET['slug'] ?? '';

$stmt = mysqli_prepare($conn, "SELECT products.*, category.name AS category_name, users.name AS seller
    FROM products
    INNER JOIN category ON category.cid = products.category_id
    INNER JOIN users ON users.uid = products.user_id
    WHERE products.slug = ?");

mysqli_stmt_bind_param($stmt, "s", $slug);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

require_once "header.php";
?>

<div class="detail container">
    <?php if ($product): ?>

        <div class="detail-image">
            <?php if (!empty($product['image'])): ?>
                <img src="images/<?php echo ($product['image']); ?>" alt="">
            <?php else: ?>
                <div class="no-image">No Image</div>
            <?php endif; ?>
        </div>

        <div class="detail-info">
            <p class="category"><?php echo ($product['category_name']); ?></p>
            <h1><?php echo ($product['title']); ?></h1>
            <p class="price big">NPR <?php echo number_format($product['price'], 2); ?></p>
            <p><strong>Available Quantity:</strong> <?php echo $product['quantity']; ?></p>
            <p><strong>Seller:</strong> <?php echo ($product['seller']); ?></p>
            <p><?php echo nl2br(($product['description'])); ?></p>
            <button class="btn" onclick="alert('Order feature can be added next.')">Order Now</button>
        </div>

    <?php else: ?>
        <h2>Product not found.</h2>
    <?php endif; ?>
</div>

<?php require_once "footer.php"; ?>


