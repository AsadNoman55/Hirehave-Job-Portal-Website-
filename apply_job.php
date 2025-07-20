<?php
session_start();
include("../includes/header.php");
include("../includes/db.php");

// Access control
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'seeker') {
    echo "<div class='container py-4'><div class='alert alert-danger'>Access denied. Please log in as a job seeker.</div></div>";
    include("../includes/footer.php");
    exit;
}

$seeker_id = $_SESSION['user_id'];

// Validate job_id
if (!isset($_GET['job_id']) || !is_numeric($_GET['job_id'])) {
    echo "<div class='container py-4'><div class='alert alert-danger'>Invalid job ID.</div></div>";
    include("../includes/footer.php");
    exit;
}

$job_id = intval($_GET['job_id']);

// Check if job exists
$job_sql = "SELECT * FROM jobs WHERE id = $job_id LIMIT 1";
$job_result = mysqli_query($conn, $job_sql);
if (!$job_result || mysqli_num_rows($job_result) == 0) {
    echo "<div class='container py-4'><div class='alert alert-danger'>Job not found.</div></div>";
    include("../includes/footer.php");
    exit;
}

// Check duplicate application
$check = "SELECT * FROM applications WHERE job_id = $job_id AND seeker_id = $seeker_id";
$check_result = mysqli_query($conn, $check);
?>

<div class="container py-5 text-center">
    <?php if (mysqli_num_rows($check_result) > 0): ?>
        <div class="alert alert-info shadow-sm rounded-3 p-4">
            <h5 class="mb-2">You already applied for this job! 🙌</h5>
            <p class="mb-0">Visit your dashboard to track your applications.</p>
        </div>
    <?php else: ?>
        <?php 
        $apply_sql = "INSERT INTO applications (job_id, seeker_id) VALUES ($job_id, $seeker_id)";
        if (mysqli_query($conn, $apply_sql)): ?>
            <div class="alert alert-success shadow-sm rounded-3 p-4">
                <h4 class="mb-2">🎯 Application Submitted!</h4>
                <p class="mb-0">Thank you for applying. We’ll notify you on updates.</p>
            </div>
        <?php else: ?>
            <div class="alert alert-danger">Something went wrong. Please try again later.</div>
        <?php endif; ?>
    <?php endif; ?>

    <div class="mt-4 d-flex justify-content-center gap-3">
        <a href="../seeker/dashboard.php" class="btn btn-primary">🏠 Go to Dashboard</a>
        <a href="search_jobs.php" class="btn btn-outline-secondary">🔍 Browse More Jobs</a>
    </div>
</div>

<?php include("../includes/footer.php"); ?>
