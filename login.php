<?php include("includes/db.php"); ?>
<?php include("includes/functions.php"); ?>

<?php
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = clean_input($_POST['email']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_role'] = $row['role'];
            $_SESSION['username'] = $row['username'];

            // Redirect based on role
            if ($row['role'] === 'admin') {
                header("Location: admin/dashboard.php");
            } elseif ($row['role'] === 'employer') {
                header("Location: employer/dashboard.php");
            } else {
                header("Location: seeker/dashboard.php");
            }
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Email not registered.";
    }
}
?>

<?php include("includes/header.php"); ?>
<div class="container py-4">
    <h2>Login</h2>

    <?php display_flash('register_success'); ?>
    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Email</label>
            <input name="email" type="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input name="password" type="password" class="form-control" required>
        </div>
        <button class="btn btn-primary" type="submit">Login</button>
        <p class="mt-2">Don't have an account? <a href="register.php">Register</a></p>
    </form>
</div>
<?php include("includes/footer.php"); ?>
