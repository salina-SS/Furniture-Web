<?php
require_once "connection.php";
require_once "header.php";

$query = "SELECT products.*, category.name AS category_name
          FROM products
          INNER JOIN category ON category.cid = products.category_id
          ORDER BY products.pid DESC";

$result = mysqli_query($conn, $query);
?>

<section class="section container">
    <h2>All Furniture Products</h2>

    <div class="product-grid">
        <?php while ($product = mysqli_fetch_assoc($result)): ?>
            <div class="product-card">
                <?php if (!empty($product['image'])): ?>
                    <img src="images/<?php echo htmlspecialchars($product['image']); ?>" alt="">
                <?php else: ?>
                    <div class="no-image">No Image</div>
                <?php endif; ?>

                <div class="product-info">
                    <p class="category"><?php echo htmlspecialchars($product['category_name']); ?></p>
                    <h3><?php echo htmlspecialchars($product['title']); ?></h3>
                    <p class="price">NPR <?php echo number_format($product['price'], 2); ?></p>
                    <a class="btn-small" href="product-details.php?slug=<?php echo urlencode($product['slug']); ?>">View Details</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<?php require_once "footer.php"; ?>