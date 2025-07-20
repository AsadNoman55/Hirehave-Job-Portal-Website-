<?php include("../includes/header.php"); ?>
<?php include("../includes/db.php"); ?>

<?php
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "<div class='container py-4'><div class='alert alert-danger'>Access denied.</div></div>";
    include("../includes/footer.php");
    exit;
}

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch messages with LIMIT
$result = mysqli_query($conn, "SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT $limit OFFSET $offset");

// Total rows for pagination
$total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM contact_messages");
$total_row = mysqli_fetch_assoc($total_result);
$total_messages = $total_row['total'];
$total_pages = ceil($total_messages / $limit);
?>

<div class="container py-4">
  <h2>Contact Messages</h2>

  <table class="table table-bordered table-sm mt-3">
    <thead>
      <tr>
        <th>#</th>
        <th>Sender Name</th>
        <th>Email</th>
        <th>Message</th>
        <th>Subject</th>
        <th>Received At</th>
      </tr>
    </thead>
    <tbody>
      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($msg = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td><?= $msg['id'] ?></td>
          <td><?= htmlspecialchars($msg['name']) ?></td>
          <td><?= htmlspecialchars($msg['email']) ?></td>
          <td><?= nl2br(htmlspecialchars($msg['message'])) ?></td>
          <td><?= nl2br(htmlspecialchars($msg['subject'])) ?></td>
          <td><?= date("M d, Y", strtotime($msg['created_at'])) ?></td>
        </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="6" class="text-muted">No messages found.</td></tr>
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
