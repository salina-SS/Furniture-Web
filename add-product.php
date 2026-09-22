<?php
session_start();
require_once "connection.php";

if (!isset($_SESSION['auth'])) {
    $_SESSION['error'] = "Login to access this page";
    header("Location: login.php");
    exit();
}

$message = "";

if (isset($_POST['add_product'])) {
    $category_id = (int) $_POST['category_id'];
    $user_id = (int) $_SESSION['auth']['uid'];
    $title = trim($_POST['title']);
    $quantity = (int) $_POST['quantity'];
    $price = (float) $_POST['price'];
    $description = trim($_POST['description']);

    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));

    $image = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (in_array($extension, $allowed)) {
            if (!is_dir("images")) {
                mkdir("images", 0777, true);
            }

            $image = time() . "_" . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], "images/" . $image);
        }
    }

    $stmt = mysqli_prepare($conn, "INSERT INTO products(category_id,user_id,title,slug,quantity,price,image,description) VALUES(?,?,?,?,?,?,?,?)");
    mysqli_stmt_bind_param($stmt, "iissidss", $category_id, $user_id, $title, $slug, $quantity, $price, $image, $description);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: products.php");
        exit();
    } else {
        $message = "Error adding product.";
    }
}

$categories = mysqli_query($conn, "SELECT * FROM category ORDER BY name");

require_once "header.php";
?>

<div class="form-box large">
    <h2>ADD PRODUCT</h2>

    <?php if ($message): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <select name="category_id" required>
            <option value="">Select Category</option>
            <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                <option value="<?php echo $cat['cid']; ?>">
                    <?php echo ($cat['name']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <input type="text" name="title" placeholder="Product title" required>
        <input type="number" name="quantity" placeholder="Quantity" min="1" required>
        <input type="number" name="price" placeholder="Price" step="0.01" min="0" required>
        <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp" required>
        <textarea name="description" placeholder="Product description" rows="5" required></textarea>

        <button type="submit" name="add_product" class="btn">Add Product</button>
    </form>
</div>

<?php require_once "footer.php"; ?>