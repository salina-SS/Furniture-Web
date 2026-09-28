<?php
require_once "header.php";
require_once "connection.php";
$slug=$_GET['slug'];
$sql="SELECT * FROM products
WHERE products.slug='$slug'";
$result =mysqli_query($conn,$sql);
$product=mysqli_fetch_assoc($result);
?>

<div class="container">
    <h1>Product Details </h1>
    <h2><?php echo $product['title'] ?></h2>
    <img src="images/<?php echo $product['image'] ?>" width="200" height="200" />
    <p>Price : 
        <?php echo $product['price'] ?>
        Vendor : <?php echo $product['name'] ?>
        Category : <?php echo $product['category_name'] ?>
    </p>
    <p><?php echo $product['description'] ?></p>
    <a class="btn-primary" href="">Order Now</a>
</div>

<?php 
require_once "footer.php";
?>