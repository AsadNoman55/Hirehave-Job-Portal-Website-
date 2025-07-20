<?php include("../includes/header.php"); ?>
<?php include("../includes/db.php"); ?>

<?php
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'employer') {
    echo "<div class='container py-4'><div class='alert alert-danger'>Access denied. Employers only.</div></div>";
    include("../includes/footer.php");
    exit;
}

$employer_id = $_SESSION['user_id'];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $company = mysqli_real_escape_string($conn, $_POST['company']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $requirements = mysqli_real_escape_string($conn, $_POST['requirements']);

    $sql = "INSERT INTO jobs (title, company, location, description, requirements, employer_id)
            VALUES ('$title', '$company', '$location', '$description', '$requirements', $employer_id)";
    
    if (mysqli_query($conn, $sql)) {
        $success = "Job posted successfully!";
    } else {
        $success = "Error posting job. Please try again.";
    }
}
?>

<div class="container py-4">
  <h2>Post a New Job</h2>

  <?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="mb-3">
      <label>Job Title</label>
      <input type="text" name="title" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Company</label>
      <input type="text" name="company" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Location</label>
      <input type="text" name="location" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Job Description</label>
      <textarea name="description" class="form-control" rows="4" required></textarea>
    </div>
    <div class="mb-3">
      <label>Requirements</label>
      <textarea name="requirements" class="form-control" rows="4" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Post Job</button>
  </form>
</div>

<?php include("../includes/footer.php"); ?>
