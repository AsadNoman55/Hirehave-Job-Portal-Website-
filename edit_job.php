<?php include("../includes/functions.php"); ?>
<?php include("../includes/header.php"); ?>
<?php include("../includes/db.php"); ?>

<?php
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'employer') {
    echo "<div class='container py-4'><div class='alert alert-danger'>Access denied.</div></div>";
    include("../includes/footer.php");
    exit;
}

$employer_id = $_SESSION['user_id'];
$job_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$job = mysqli_query($conn, "SELECT * FROM jobs WHERE id = $job_id AND employer_id = $employer_id");
if (!$job || mysqli_num_rows($job) === 0) {
    echo "<div class='container py-4'><div class='alert alert-warning'>Job not found.</div></div>";
    include("../includes/footer.php");
    exit;
}

$job = mysqli_fetch_assoc($job);
$success = $error = '';

// Handle form update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = clean_input($_POST['title']);
    $location = clean_input($_POST['location']);
    $description = clean_input($_POST['description']);

    $query = "UPDATE jobs SET title='$title', location='$location', description='$description' WHERE id=$job_id AND employer_id=$employer_id";
    if (mysqli_query($conn, $query)) {
        $success = "Job updated successfully.";
    } else {
        $error = "Failed to update job.";
    }
}
?>

<div class="container py-4">
    <h2>Edit Job</h2>

    <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Job Title</label>
            <input name="title" class="form-control" value="<?= htmlspecialchars($job['title']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Location</label>
            <input name="location" class="form-control" value="<?= htmlspecialchars($job['location']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="6" required><?= htmlspecialchars($job['description']) ?></textarea>
        </div>
        <button class="btn btn-primary">Update Job</button>
    </form>
</div>

<?php include("../includes/footer.php"); ?>
