<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>HireHaven - Job Portal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">HireHaven</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">

        <?php if (!isset($_SESSION['user_role'])): ?>
          <!-- Guest View -->
          <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="job_listings.php">Jobs</a></li>
          <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
          <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
          <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>

        <?php else: ?>
          <!-- Authenticated User View -->
          <?php if ($_SESSION['user_role'] === 'seeker'): ?>
            <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
          <?php elseif ($_SESSION['user_role'] === 'employer'): ?>
            <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
          <?php elseif ($_SESSION['user_role'] === 'admin'): ?>
            <li class="nav-item"><a class="nav-link" href="dashboard.php">Admin</a></li>
          <?php endif; ?>

          <li class="nav-item"><a class="nav-link text-warning" href="logout.php">Logout</a></li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>
