<?php
session_start();
require_once "connection.php";

$message = "";

if (isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($name == "" || $email == "" || $password == "") {
        $message = "All fields are required.";
    } else {
        $check = mysqli_prepare($conn, "SELECT uid FROM users WHERE email = ?");
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        $checkResult = mysqli_stmt_get_result($check);

        if (mysqli_num_rows($checkResult) > 0) {
            $message = "Email already exists.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = mysqli_prepare($conn, "INSERT INTO users(name,email,password) VALUES(?,?,?)");
            mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hash);

            if (mysqli_stmt_execute($stmt)) {
                header("Location: login.php");
                exit();
            } else {
                $message = "Registration failed.";
            }
        }
    }
}

require_once "header.php";
?>

<div class="form-box">
    <h2>Create Account</h2>

    <?php if ($message): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="register" class="btn">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login</a></p>
</div>

<?php require_once "footer.php"; ?>