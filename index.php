<?php
require_once "connection.php";
require_once "header.php";

$query = "SELECT * FROM products ORDER BY pid DESC LIMIT 6";
$result = mysqli_query($conn, $query);
?>

<section class="hero">
    <div class="container hero-content">
        <div>
            <p class="small-title">MODERN FURNITURE</p>
            <h1>Make Your Home<br>Beautiful & Comfortable</h1>
            <p>Discover stylish furniture made for modern living.</p>
            <a href="products.php" class="btn">Shop Now</a>
        </div>
    </div>
</section>

<section class="section container">
    <h2>Featured Products</h2>

    <div class="product-grid">
        <?php while ($product = mysqli_fetch_assoc($result)): ?>
            <div class="product-card">
                <?php if (!empty($product['image'])): ?>
                    <img src="images/<?php echo ($product['image']); ?>" alt="">
                <?php else: ?>
                    <div class="no-image">No Image</div>
                <?php endif; ?>

                <div class="product-info">
                    <h3><?php echo ($product['title']); ?></h3>
                    <p class="price">NPR <?php echo number_format($product['price'], 2); ?></p>
                    <a class="btn-small" href="product-details.php?slug=<?php echo ($product['slug']); ?>">View Details</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<?php require_once "footer.php"; ?>