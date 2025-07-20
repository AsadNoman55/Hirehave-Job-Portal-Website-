<?php include("includes/db.php"); ?>
<?php include("includes/functions.php"); ?>

<?php
$success = $error = '';

// Get pre-selected role from URL
$pre_selected_role = '';
if (isset($_GET['role']) && in_array($_GET['role'], ['seeker', 'employer'])) {
    $pre_selected_role = $_GET['role'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean_input($_POST['username']);
    $email = clean_input($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = clean_input($_POST['role']);

    // Check if email exists
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $error = "Email is already registered.";
    } else {
        // Insert user
        $sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', '$role')";
        if (mysqli_query($conn, $sql)) {
            $user_id = mysqli_insert_id($conn);
            if ($role === 'seeker') {
                mysqli_query($conn, "INSERT INTO seekers (user_id, full_name) VALUES ($user_id, '$username')");
            } elseif ($role === 'employer') {
                mysqli_query($conn, "INSERT INTO employers (user_id, contact_person) VALUES ($user_id, '$username')");
            }
            set_flash('register_success', 'Registration successful. Please log in.');
            header("Location: login.php");
            exit;
        } else {
            $error = "Registration failed. Try again.";
        }
    }
}
?>

<?php include("includes/header.php"); ?>
<div class="container py-4">
    <h2>Create an Account</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Username</label>
            <input name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input name="email" type="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input name="password" type="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Register As</label>
            <select name="role" class="form-select" required>
                <option value="">Select</option>
                <option value="seeker" <?= ($pre_selected_role === 'seeker') ? 'selected' : '' ?>>Job Seeker</option>
                <option value="employer" <?= ($pre_selected_role === 'employer') ? 'selected' : '' ?>>Employer</option>
            </select>
        </div>
        <button class="btn btn-primary" type="submit">Register</button>
        <p class="mt-2">Already have an account? <a href="login.php">Login</a></p>
    </form>
</div>
<?php include("includes/footer.php"); ?>
