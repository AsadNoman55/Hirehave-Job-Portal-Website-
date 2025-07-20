<?php
include("includes/header.php");
include("includes/db.php");

// Check if user is logged in as seeker
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'seeker') {
    echo "<div class='container py-4'><div class='alert alert-danger'>You must be logged in as a job seeker to apply.</div></div>";
    include("includes/footer.php");
    exit;
}

// Validate job ID
if (!isset($_GET['job_id']) || !is_numeric($_GET['job_id'])) {
    echo "<div class='container py-4'><div class='alert alert-danger'>Invalid job ID.</div></div>";
    include("includes/footer.php");
    exit;
}

$job_id = intval($_GET['job_id']);
$seeker_id = $_SESSION['user_id'];

// Check if already applied
$check = mysqli_query($conn, "SELECT * FROM applications WHERE job_id = $job_id AND seeker_id = $seeker_id");
if (mysqli_num_rows($check) > 0) {
    echo "<div class='container py-4'><div class='alert alert-info'>You have already applied for this job.</div></div>";
} else {
    $query = "INSERT INTO applications (job_id, seeker_id) VALUES ($job_id, $seeker_id)";
    if (mysqli_query($conn, $query)) {
        echo "<div class='container py-4'><div class='alert alert-success'>Application submitted successfully!</div></div>";
    } else {
        echo "<div class='container py-4'><div class='alert alert-danger'>Something went wrong. Please try again.</div></div>";
    }
}

include("includes/footer.php");
?>
