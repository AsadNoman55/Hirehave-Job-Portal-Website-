<?php include("includes/header.php"); ?>
<?php include("includes/db.php"); ?>

<div class="container py-4">
  <h2 class="mb-4">Available Job Listings</h2>

  <!-- Search Bar -->
  <form method="GET" class="row g-3 mb-4">
    <div class="col-md-4">
      <input type="text" name="keyword" class="form-control" placeholder="Job title or company" value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
    </div>
    <div class="col-md-4">
      <input type="text" name="location" class="form-control" placeholder="Location" value="<?= isset($_GET['location']) ? htmlspecialchars($_GET['location']) : '' ?>">
    </div>
    <div class="col-md-4">
      <button type="submit" class="btn btn-primary w-100">Search</button>
    </div>
  </form>

  <!-- Job Listings -->
  <div class="row">
    <?php
      // Pagination setup
      $limit = 6;
      $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
      $offset = ($page - 1) * $limit;

      // Search filters
      $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
      $location = isset($_GET['location']) ? trim($_GET['location']) : '';

      // Base query
      $sql = "FROM jobs WHERE 1";
      if ($keyword) {
        $sql .= " AND (title LIKE '%$keyword%' OR company LIKE '%$keyword%')";
      }
      if ($location) {
        $sql .= " AND location LIKE '%$location%'";
      }

      // Count total jobs
      $count_result = mysqli_query($conn, "SELECT COUNT(*) AS total $sql");
      $count_row = mysqli_fetch_assoc($count_result);
      $total_jobs = $count_row['total'];
      $total_pages = ceil($total_jobs / $limit);

      // Fetch jobs with LIMIT
      $query = "SELECT * $sql ORDER BY created_at DESC LIMIT $offset, $limit";
      $result = mysqli_query($conn, $query);

      if (mysqli_num_rows($result) > 0):
        while ($row = mysqli_fetch_assoc($result)):
    ?>
      <div class="col-md-6 mb-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($row['title']) ?></h5>
            <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($row['company']) ?> - <?= htmlspecialchars($row['location']) ?></h6>
            <p class="card-text text-truncate"><?= htmlspecialchars(substr($row['description'], 0, 100)) ?>...</p>
            <p class="text-muted"><small>Posted on <?= date("M d, Y", strtotime($row['created_at'])) ?></small></p>
            <a href="job_details.php?id=<?= $row['id'] ?>" class="btn btn-outline-primary btn-sm">View Details</a>
          </div>
        </div>
      </div>
    <?php endwhile; else: ?>
      <div class="col-12">
        <p class="text-muted">No jobs found.</p>
      </div>
    <?php endif; ?>
  </div>

  <!-- Pagination -->
  <?php if ($total_pages > 1): ?>
  <nav class="mt-4">
    <ul class="pagination justify-content-center">
      <?php if ($page > 1): ?>
        <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>&keyword=<?= urlencode($keyword) ?>&location=<?= urlencode($location) ?>">Previous</a></li>
      <?php endif; ?>

      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
          <a class="page-link" href="?page=<?= $i ?>&keyword=<?= urlencode($keyword) ?>&location=<?= urlencode($location) ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>

      <?php if ($page < $total_pages): ?>
        <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>&keyword=<?= urlencode($keyword) ?>&location=<?= urlencode($location) ?>">Next</a></li>
      <?php endif; ?>
    </ul>
  </nav>
  <?php endif; ?>
</div>

<?php include("includes/footer.php"); ?>
