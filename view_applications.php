<?php include("../includes/header.php"); ?>
<?php include("../includes/db.php"); ?>

<?php
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "<div class='container py-4'><div class='alert alert-danger'>Access denied.</div></div>";
    include("../includes/footer.php");
    exit;
}

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch paginated applications
$query = "
SELECT 
  a.id, 
  a.applied_at, 
  j.title AS job_title, 
  j.company, 
  u.username AS seeker_name
FROM applications a
JOIN jobs j ON a.job_id = j.id
JOIN users u ON a.seeker_id = u.id
ORDER BY a.applied_at DESC
LIMIT $limit OFFSET $offset
";
$result = mysqli_query($conn, $query);

// Count total applications
$total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM applications");
$total_row = mysqli_fetch_assoc($total_result);
$total_apps = $total_row['total'];
$total_pages = ceil($total_apps / $limit);
?>

<div class="container py-4">
  <h2>All Job Applications</h2>

  <table class="table table-bordered table-sm mt-3">
    <thead>
      <tr>
        <th>#</th>
        <th>Seeker</th>
        <th>Job Title</th>
        <th>Company</th>
        <th>Applied At</th>
      </tr>
    </thead>
    <tbody>
      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['seeker_name']) ?></td>
          <td><?= htmlspecialchars($row['job_title']) ?></td>
          <td><?= htmlspecialchars($row['company']) ?></td>
          <td><?= date("M d, Y", strtotime($row['applied_at'])) ?></td>
        </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="5" class="text-muted">No applications found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>

  <!-- Pagination -->
  <?php if ($total_pages > 1): ?>
    <nav>
      <ul class="pagination justify-content-center">
        <?php if ($page > 1): ?>
          <li class="page-item">
            <a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a>
          </li>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
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
