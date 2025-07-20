<?php include("../includes/header.php"); ?>
<?php include("../includes/db.php"); ?>

<?php
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'employer') {
    echo "<div class='container py-4'><div class='alert alert-danger'>Access denied.</div></div>";
    include("../includes/footer.php");
    exit;
}

$employer_id = $_SESSION['user_id'];

// Handle deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM jobs WHERE id = $delete_id AND employer_id = $employer_id");
    echo "<script>window.location.href='manage_jobs.php';</script>";
    exit;
}

// Pagination setup
$limit = 8;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Total jobs count
$count_query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM jobs WHERE employer_id = $employer_id");
$total_jobs = mysqli_fetch_assoc($count_query)['total'];
$total_pages = ceil($total_jobs / $limit);

// Fetch paginated jobs
$result = mysqli_query($conn, "SELECT * FROM jobs WHERE employer_id = $employer_id ORDER BY created_at DESC LIMIT $offset, $limit");
?>

<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Manage Your Jobs</h2>
    <a href="post_job.php" class="btn btn-primary">+ Post New Job</a>
  </div>

  <div class="table-responsive">
    <table class="table table-hover align-middle border shadow-sm">
      <thead class="table-light">
        <tr>
          <th>Title</th>
          <th>Location</th>
          <th>Posted On</th>
          <th class="text-end">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
          <?php while ($job = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><?= htmlspecialchars($job['title']) ?></td>
              <td><?= htmlspecialchars($job['location']) ?></td>
              <td><?= date("M d, Y", strtotime($job['created_at'])) ?></td>
              <td class="text-end">
                <a href="edit_job.php?id=<?= $job['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                <a href="manage_jobs.php?delete=<?= $job['id'] ?>" 
                   class="btn btn-sm btn-outline-danger" 
                   onclick="return confirm('Are you sure you want to delete this job?')">
                   Delete
                </a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="4" class="text-center text-muted">No jobs posted yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <?php if ($total_pages > 1): ?>
    <nav aria-label="Job pagination">
      <ul class="pagination justify-content-center mt-4">
        <?php if ($page > 1): ?>
          <li class="page-item">
            <a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a>
          </li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <li class="page-item <?= $i == $page ? 'active' : '' ?>">
            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
          <li class="page-item">
            <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
          </li>
        <?php endif; ?>
      </ul>
    </nav>
  <?php endif; ?>
</div>

<?php include("../includes/footer.php"); ?>
