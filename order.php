<?php
session_start();
require_once "connection.php";

// Check if user is logged in
if (!isset($_SESSION['auth'])) {
    $_SESSION['error'] = "Please login to place an order.";
    header("Location: login.php");
    exit();
}

// Get product slug
$slug = $_GET['slug'] ?? '';

if ($slug == '') {
    die("Product not found.");
}

// Get product details
$stmt = mysqli_prepare($conn, "SELECT products.*, category.name AS category_name
    FROM products
    INNER JOIN category ON category.cid = products.category_id
    WHERE products.slug = ?");

mysqli_stmt_bind_param($stmt, "s", $slug);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    die("Product not found.");
}

$message = "";

// Place order
if (isset($_POST['place_order'])) {

    $user_id = $_SESSION['auth']['uid'];
    $product_id = $product['pid'];

    $customer_name = trim($_POST['customer_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $quantity = (int) $_POST['quantity'];

    // Check quantity
    if ($quantity < 1) {

        $message = "Quantity must be at least 1.";

    } elseif ($quantity > $product['quantity']) {

        $message = "Not enough stock available.";

    } elseif ($customer_name == "" || $phone == "" || $address == "") {

        $message = "Please fill all fields.";

    } else {

        // Calculate total price
        $total_price = $product['price'] * $quantity;

        // Insert order
        $order = mysqli_prepare($conn, "INSERT INTO orders
            (user_id, product_id, quantity, total_price, customer_name, phone, address, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending')");

        mysqli_stmt_bind_param(
            $order,
            "iiidsss",
            $user_id,
            $product_id,
            $quantity,
            $total_price,
            $customer_name,
            $phone,
            $address
        );

        if (mysqli_stmt_execute($order)) {

            // Update product stock
            $update = mysqli_prepare(
                $conn,
                "UPDATE products
                 SET quantity = quantity - ?
                 WHERE pid = ?"
            );

            mysqli_stmt_bind_param(
                $update,
                "ii",
                $quantity,
                $product_id
            );

            mysqli_stmt_execute($update);

            $message = "Order placed successfully!";
        } else {

            $message = "Failed to place order.";
        }
    }
}

require_once "header.php";
?>

<div class="form-box large">

    <h2>Place Your Order</h2>

    <?php if ($message != ""): ?>

        <p class="message">
            <?php echo htmlspecialchars($message); ?>
        </p>

    <?php endif; ?>


    <!-- Product Information -->

    <div class="order-product">

        <?php if (!empty($product['image'])): ?>

            <img
                src="images/<?php echo htmlspecialchars($product['image']); ?>"
                alt="<?php echo htmlspecialchars($product['title']); ?>"
            >

        <?php else: ?>

            <div class="no-image">
                No Image
            </div>

        <?php endif; ?>


        <div>

            <h3>
                <?php echo htmlspecialchars($product['title']); ?>
            </h3>

            <p class="price">
                NPR <?php echo number_format($product['price'], 2); ?>
            </p>

            <p>
                Category:
                <?php echo htmlspecialchars($product['category_name']); ?>
            </p>

            <p>
                Available Quantity:
                <?php echo $product['quantity']; ?>
            </p>

        </div>

    </div>


    <?php if ($product['quantity'] > 0): ?>

        <!-- Order Form -->

        <form method="post">

            <input
                type="text"
                name="customer_name"
                placeholder="Customer Name"
                value="<?php echo htmlspecialchars($_SESSION['auth']['name']); ?>"
                required
            >

            <input
                type="text"
                name="phone"
                placeholder="Phone Number"
                required
            >

            <textarea
                name="address"
                placeholder="Delivery Address"
                rows="4"
                required
            ></textarea>

            <input
                type="number"
                name="quantity"
                placeholder="Quantity"
                min="1"
                max="<?php echo $product['quantity']; ?>"
                value="1"
                required
            >

            <button
                type="submit"
                name="place_order"
                class="btn"
            >
                Place Order
            </button>

        </form>

    <?php else: ?>

        <p class="message">
            This product is out of stock.
        </p>

    <?php endif; ?>

</div>


<?php require_once "footer.php"; ?>