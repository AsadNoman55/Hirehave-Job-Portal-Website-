<?php
session_start();
include("includes/header.php");
include("includes/db.php");

$limit = 6; // Jobs per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Total jobs count
$total_result = $conn->query("SELECT COUNT(*) AS total FROM jobs");
$total_row = $total_result->fetch_assoc();
$total_jobs = $total_row['total'];
$total_pages = ceil($total_jobs / $limit);

// Fetch paginated jobs
$stmt = $conn->prepare("SELECT id, title, company, location, created_at, description FROM jobs ORDER BY created_at DESC LIMIT ?, ?");
$stmt->bind_param("ii", $offset, $limit);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container py-5">
  <h2 class="mb-4 text-center">🧑‍💼 All Job Listings</h2>

  <div class="row">
    <?php if ($result->num_rows > 0): ?>
      <?php while ($job = $result->fetch_assoc()): ?>
        <div class="col-md-6 mb-4">
          <div class="card h-100 shadow-sm">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($job['title']) ?></h5>
              <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($job['company']) ?> | <?= htmlspecialchars($job['location']) ?></h6>
              <p class="card-text text-truncate"><?= htmlspecialchars(substr($job['description'], 0, 100)) ?>...</p>
              <p class="text-muted"><small>Posted on <?= date("M d, Y", strtotime($job['created_at'])) ?></small></p>
              <a href="job_details.php?id=<?= $job['id'] ?>" class="btn btn-outline-primary btn-sm">View Details</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-12">
        <div class="alert alert-info text-center">No jobs found at the moment.</div>
      </div>
    <?php endif; ?>
  </div>

  <!-- Pagination -->
  <?php if ($total_pages > 1): ?>
    <nav aria-label="Job Pagination">
      <ul class="pagination justify-content-center mt-4">
        <?php if ($page > 1): ?>
          <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>">« Prev</a></li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
          <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>">Next »</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  <?php endif; ?>
</div>

<?php include("includes/footer.php"); ?>
