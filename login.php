<?php
session_start();
require_once "connection.php";

$message = "";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['auth'] = [
            'uid' => $user['uid'],
            'name' => $user['name'],
            'email' => $user['email']
        ];

        header("Location: index.php");
        exit();
    } else {
        $message = "Invalid email or password.";
    }
}

require_once "header.php";
?>

<div class="form-box">
    <h2>Login</h2>

    <?php if ($message): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="post">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login" class="btn">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>
</div>

<?php require_once "footer.php"; ?>