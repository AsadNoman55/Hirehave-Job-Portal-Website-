<?php include("includes/header.php"); ?>

<!-- Hero Section -->
<div class="jumbotron text-white text-center" style="background: url('assets/images/job.jpg') center center/cover no-repeat; height: 400px;">
  <div class="container py-5">
    <h1 class="display-4 fw-bold">Find Your Dream Job</h1>
    <p class="lead">Thousands of jobs from top companies. Apply now!</p>
    <form class="row justify-content-center" method="GET" action="job_listings.php">
      <div class="col-md-4 mb-2">
        <input class="form-control" type="search" name="keyword" placeholder="Job title or keyword">
      </div>
      <div class="col-md-3 mb-2">
        <input class="form-control" type="search" name="location" placeholder="Location">
      </div>
      <div class="col-md-2 mb-2">
        <button class="btn btn-light w-100" type="submit">Search Jobs</button>
      </div>
    </form>
  </div>
</div>

<!-- Info Cards -->
<div class="container text-center py-5">
  <div class="row g-4">
    <div class="col-md-4">
      <a href="register.php?role=seeker" style="text-decoration: none;">
        <div class="card text-white bg-success h-100 shadow">
          <div class="card-body">
            <h5 class="card-title">Find Jobs</h5>
            <p class="card-text">Browse jobs from top employers that match your skills.</p>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-4">
      <a href="register.php?role=employer" style="text-decoration: none;">
        <div class="card text-white bg-primary h-100 shadow">
          <div class="card-body">
            <h5 class="card-title">Post Jobs</h5>
            <p class="card-text">Employers can post jobs and view applicants easily.</p>
          </div>
        </div>
      </a>
    </div>
    <div class="col-md-4">
      <a href="register.php?role=seeker" style="text-decoration: none;">
        <div class="card text-white bg-warning h-100 shadow">
          <div class="card-body">
            <h5 class="card-title">Get Hired</h5>
            <p class="card-text">Create your profile, upload your resume, and apply with a click.</p>
          </div>
        </div>
      </a>
    </div>
  </div>
</div>
<!-- Latest Jobs Section -->
<div class="container py-5">
  <h3 class="mb-4">Latest Jobs</h3>
  <div class="row" id="latest-jobs">
    <?php
    require_once("includes/db.php");

    $stmt = $conn->prepare("SELECT id, title, location, company, created_at FROM jobs ORDER BY created_at DESC LIMIT 6");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0):
      while ($job = $result->fetch_assoc()):
    ?>
      <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($job['title']) ?></h5>
            <p class="mb-1"><strong>Company:</strong> <?= htmlspecialchars($job['company']) ?></p>
            <p class="mb-1"><strong>Location:</strong> <?= htmlspecialchars($job['location']) ?></p>
            <p class="text-muted mb-2 small"><?= date('d M Y', strtotime($job['created_at'])) ?></p>
            <a href="<?php
              // session_start();
              if (!isset($_SESSION['user_id'])) {
                echo 'register.php?role=seeker';
              } else {
                echo 'job_details.php?id=' . $job['id'];
              }
            ?>" class="btn btn-primary btn-sm w-100">
              View Job
            </a>
          </div>
        </div>
      </div>
    <?php
      endwhile;
    else:
    ?>
      <p class="text-muted">No job listings available right now. Check back later!</p>
    <?php endif; ?>
  </div>
</div>
<!-- View All Jobs Button -->
<div class="text-center mt-4">
  <a href="jobs.php" class="btn btn-primary">
    <i class="fas fa-briefcase me-1"></i> View All Jobs
  </a>
</div>
<!-- Why Choose Us -->
<div class="bg-light py-5 text-center">
  <h4 class="mb-3">Why Choose HireHaven?</h4>
  <p class="text-muted">Because we connect the right talent with the right opportunity — fast, easy, and reliable.</p>
</div>

<?php include("includes/footer.php"); ?>
