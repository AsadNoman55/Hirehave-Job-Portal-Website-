<?php include("includes/header.php"); ?>
<?php include("includes/db.php"); ?>

<div class="container py-4">
<?php
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $job_id = intval($_GET['id']);

    // Prepared statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT * FROM jobs WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $job = $result->fetch_assoc();
?>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h2 class="card-title mb-2"><?= htmlspecialchars($job['title']) ?></h2>
            <h5 class="text-muted mb-3">
                <i class="fas fa-building me-1"></i> <?= htmlspecialchars($job['company']) ?> 
                <span class="mx-2">|</span> 
                <i class="fas fa-map-marker-alt me-1"></i> <?= htmlspecialchars($job['location']) ?>
            </h5>
            
            <p class="text-muted mb-1">
                <strong>Posted on:</strong> <?= date("F j, Y", strtotime($job['created_at'])) ?>
            </p>

            <hr>

            <h5><i class="fas fa-align-left me-2"></i>Job Description</h5>
            <p><?= nl2br(htmlspecialchars($job['description'])) ?></p>

            <h5><i class="fas fa-list-check me-2"></i>Requirements</h5>
            <p><?= nl2br(htmlspecialchars($job['requirements'])) ?></p>

            <hr>

            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'seeker'): ?>
                <a href="seeker/apply_job.php?job_id=<?= $job['id'] ?>" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-1"></i> Apply Now
                </a>
            <?php else: ?>
                <div class="alert alert-warning mt-3">
                    <strong>Note:</strong> Please 
                    <a href="login.php" class="alert-link">log in</a> 
                    as a job seeker to apply.
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php
    } else {
        echo "<div class='alert alert-danger'>Job not found.</div>";
    }
    $stmt->close();
} else {
    echo "<div class='alert alert-danger'>Invalid job ID.</div>";
}
?>
</div>

<?php include("includes/footer.php"); ?>
