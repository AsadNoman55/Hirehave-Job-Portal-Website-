<?php include("../includes/header.php"); ?>
<?php include("../includes/db.php"); ?>

<?php
// Restrict access to job seekers only
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'seeker') {
    echo "<div class='container py-4'><div class='alert alert-danger'>Access denied.</div></div>";
    include("../includes/footer.php");
    exit;
}

$seeker_id = $_SESSION['user_id'];
?>

<div class="container py-4">
  <h2>Welcome, <?= $_SESSION['username'] ?>!</h2>

  <div class="row mt-4">
    <!-- Profile Overview -->
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5>Your Profile</h5>
          <?php
          $profile = mysqli_query($conn, "SELECT * FROM seekers WHERE user_id = $seeker_id LIMIT 1");
          if ($profile && mysqli_num_rows($profile) > 0) {
              $row = mysqli_fetch_assoc($profile);
              echo "<p><strong>Name:</strong> " . htmlspecialchars($row['full_name']) . "</p>";
              echo "<p><strong>Education:</strong> " . htmlspecialchars($row['education']) . "</p>";
              echo "<p><strong>Experience:</strong> " . htmlspecialchars($row['experience']) . "</p>";
             if ($row['resume']) {
    echo "<a href='../uploads/" . htmlspecialchars($row['resume']) . "' target='_blank' class='btn btn-sm btn-outline-primary'>View Resume</a>";
}
          } else {
              echo "<p class='text-muted'>Profile not completed.</p>";
          }
          ?>
          <a href="edit_profile.php" class="btn btn-sm btn-outline-primary">Edit Profile</a>
        </div>
      </div>
    </div>

    <!-- Applied Jobs -->
    <div class="col-md-8">
      <div class="card shadow-sm">
        <div class="card-body">
          <h5>Your Applications</h5>
          <table class="table table-bordered table-sm">
            <thead>
              <tr>
                <th>Job Title</th>
                <th>Company</th>
                <th>Applied On</th>
                <th>Status</th>
                <th>Employer Note</th>
              </tr>
            </thead>
            <tbody>
             <?php
// Pagination logic
$limit = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Count total applications
$count_sql = "SELECT COUNT(*) AS total FROM applications WHERE seeker_id = $seeker_id";
$count_result = mysqli_query($conn, $count_sql);
$total_apps = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_apps / $limit);

// Fetch paginated applications
$sql = "SELECT j.title, j.company, a.applied_at, a.status, a.note 
        FROM applications a 
        JOIN jobs j ON a.job_id = j.id 
        WHERE a.seeker_id = $seeker_id 
        ORDER BY a.applied_at DESC 
        LIMIT $limit OFFSET $offset";

$apps = mysqli_query($conn, $sql);
if ($apps && mysqli_num_rows($apps) > 0):
    while ($app = mysqli_fetch_assoc($apps)):
        $status = $app['status'];
        $note = htmlspecialchars($app['note']);
        $badge = "secondary";
        if ($status === 'Pending') $badge = "warning";
        elseif ($status === 'Shortlisted') $badge = "success";
        elseif ($status === 'Rejected') $badge = "danger";
?>
<tr>
  <td><?= htmlspecialchars($app['title']) ?></td>
  <td><?= htmlspecialchars($app['company']) ?></td>
  <td><?= date("M d, Y", strtotime($app['applied_at'])) ?></td>
  <td><span class="badge bg-<?= $badge ?>"><?= $status ?></span></td>
  <td>
    <?php
      if (!empty($note)) {
          $short_note = strlen($note) > 50 ? substr($note, 0, 50) . '...' : $note;
          echo "<div class='note-preview'>$short_note";
          if (strlen($note) > 30) {
              echo " <a href='#' class='toggle-note btn-primary' data-note='$note'>Read more</a>";
          }
          echo "</div>";
      } else {
          echo "<span class='text-muted'>—</span>";
      }
    ?>
  </td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr><td colspan="5" class="text-muted">No applications found.</td></tr>
<?php endif; ?>

            </tbody>
          </table>
          <?php if ($total_pages > 1): ?>
<nav>
  <ul class="pagination justify-content-center">
    <?php if ($page > 1): ?>
      <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a></li>
    <?php endif; ?>
    
    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
      <li class="page-item <?= $i == $page ? 'active' : '' ?>">
        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
      </li>
    <?php endfor; ?>
    
    <?php if ($page < $total_pages): ?>
      <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>">Next</a></li>
    <?php endif; ?>
  </ul>
</nav>
<?php endif; ?>

          <a href="search_jobs.php" class="btn btn-primary btn-sm">Search More Jobs</a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Toggle Note Script -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.toggle-note').forEach(function(link) {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        const fullNote = this.getAttribute('data-note');
        this.parentElement.innerHTML = fullNote;
      });
    });
  });
</script>

<?php include("../includes/footer.php"); ?>
