<?php include("../includes/header.php"); ?>
<?php include("../includes/db.php"); ?>

<?php
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'seeker') {
    echo "<div class='container py-4'><div class='alert alert-danger'>Access denied.</div></div>";
    include("../includes/footer.php");
    exit;
}
?>

<div class="container py-4">
  <h2>Search for Jobs</h2>

  <!-- Search Form -->
  <form method="GET" class="row g-3 mb-4">
    <div class="col-md-4">
      <input type="text" name="keyword" class="form-control" placeholder="Job title or company" value="<?= $_GET['keyword'] ?? '' ?>">
    </div>
    <div class="col-md-4">
      <input type="text" name="location" class="form-control" placeholder="Location" value="<?= $_GET['location'] ?? '' ?>">
    </div>
    <div class="col-md-4">
      <button type="submit" class="btn btn-primary w-100">Search</button>
    </div>
  </form>

  <!-- Job Results -->
  <div class="row">
    <?php
      $keyword = mysqli_real_escape_string($conn, $_GET['keyword'] ?? '');
      $location = mysqli_real_escape_string($conn, $_GET['location'] ?? '');

      // Pagination setup
      $limit = 6;
      $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
      $offset = ($page - 1) * $limit;

      // Count total jobs
      $countSql = "SELECT COUNT(*) as total FROM jobs WHERE 1";
      if ($keyword) $countSql .= " AND (title LIKE '%$keyword%' OR company LIKE '%$keyword%')";
      if ($location) $countSql .= " AND location LIKE '%$location%'";
      $totalResult = mysqli_query($conn, $countSql);
      $totalJobs = mysqli_fetch_assoc($totalResult)['total'];
      $totalPages = ceil($totalJobs / $limit);

      // Fetch paginated jobs
      $sql = "SELECT * FROM jobs WHERE 1";
      if ($keyword) $sql .= " AND (title LIKE '%$keyword%' OR company LIKE '%$keyword%')";
      if ($location) $sql .= " AND location LIKE '%$location%'";
      $sql .= " ORDER BY created_at DESC LIMIT $limit OFFSET $offset";

      $jobs = mysqli_query($conn, $sql);

      if (mysqli_num_rows($jobs) > 0):
        while ($job = mysqli_fetch_assoc($jobs)):
    ?>
    <div class="col-md-6 mb-4">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h5 class="card-title"><?= htmlspecialchars($job['title']) ?></h5>
          <h6 class="card-subtitle text-muted mb-2"><?= htmlspecialchars($job['company']) ?> - <?= htmlspecialchars($job['location']) ?></h6>
          <p class="text-muted"><small>Posted: <?= date("M d, Y", strtotime($job['created_at'])) ?></small></p>
          <a href="../job_details.php?id=<?= $job['id'] ?>" class="btn btn-outline-primary btn-sm">View Details</a>
        </div>
      </div>
    </div>
    <?php endwhile;
      else: ?>
        <div class="col-12"><p class="text-muted">No matching jobs found.</p></div>
    <?php endif; ?>
  </div>

  <!-- Pagination -->
  <?php if ($totalPages > 1): ?>
  <nav>
    <ul class="pagination justify-content-center mt-4">
      <?php if ($page > 1): ?>
        <li class="page-item">
          <a class="page-link" href="?keyword=<?= urlencode($keyword) ?>&location=<?= urlencode($location) ?>&page=<?= $page - 1 ?>">Previous</a>
        </li>
      <?php endif; ?>
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
          <a class="page-link" href="?keyword=<?= urlencode($keyword) ?>&location=<?= urlencode($location) ?>&page=<?= $i ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>
      <?php if ($page < $totalPages): ?>
        <li class="page-item">
          <a class="page-link" href="?keyword=<?= urlencode($keyword) ?>&location=<?= urlencode($location) ?>&page=<?= $page + 1 ?>">Next</a>
        </li>
      <?php endif; ?>
    </ul>
  </nav>
  <?php endif; ?>
</div>

<?php include("../includes/footer.php"); ?>
