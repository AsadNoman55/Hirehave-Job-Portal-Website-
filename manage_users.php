<?php include("../includes/header.php"); ?>
<?php include("../includes/db.php"); ?>

<?php
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "<div class='container py-4'><div class='alert alert-danger'>Access denied.</div></div>";
    include("../includes/footer.php");
    exit;
}

// Delete user
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $user_id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM users WHERE id = $user_id");
    echo "<script>window.location.href='manage_users.php';</script>";
}

// Pagination
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Fetch paginated users
$result = mysqli_query($conn, "SELECT * FROM users ORDER BY created_at DESC LIMIT $limit OFFSET $offset");

// Count total users for pagination
$total_users_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$total_users = mysqli_fetch_assoc($total_users_result)['total'];
$total_pages = ceil($total_users / $limit);
?>

<div class="container py-4">
  <h2>Manage Users</h2>

  <table class="table table-bordered table-sm mt-3">
    <thead>
      <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Role</th>
        <th>Registered On</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if (mysqli_num_rows($result) > 0):
          while ($row = mysqli_fetch_assoc($result)):
      ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['username']) ?></td>
        <td><?= htmlspecialchars($row['email']) ?></td>
        <td><?= ucfirst($row['role']) ?></td>
        <td><?= date("M d, Y", strtotime($row['created_at'])) ?></td>
        <td>
          <a href="manage_users.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
        </td>
      </tr>
      <?php endwhile; else: ?>
        <tr><td colspan="6" class="text-muted">No users found.</td></tr>
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
