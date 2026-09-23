<?php
session_start();
require_once "connection.php";

if (!isset($_SESSION['auth'])) {
    header("Location: login.php");
    exit();
}

$message = "";

if (isset($_POST['add_category'])) {
    $name = trim($_POST['name']);

    if ($name != "") {
        $stmt = mysqli_prepare($conn, "INSERT INTO category(name) VALUES(?)");
        mysqli_stmt_bind_param($stmt, "s", $name);

        if (mysqli_stmt_execute($stmt)) {
            $message = "Category added successfully.";
        } else {
            $message = "Error adding category.";
        }
    }
}

$result = mysqli_query($conn, "SELECT * FROM category ORDER BY cid DESC");

require_once "header.php";
?>

<div class="form-box">
    <h2>ADD CATEGORY</h2>

    <?php if ($message): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="name" placeholder="Enter category name" required>
        <button type="submit" name="add_category" class="btn">Add</button>
    </form>
</div>

<section class="section container">
    <h2>Categories</h2>
    <div class="category-list">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div><?php echo htmlspecialchars($row['name']); ?></div>
        <?php endwhile; ?>
    </div>
</section>

<?php require_once "footer.php"; ?>