<?php include("../includes/header.php"); ?>
<?php include("../includes/db.php"); ?>

<?php
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'employer') {
    echo "<div class='container py-4'><div class='alert alert-danger'>Access denied.</div></div>";
    include("../includes/footer.php");
    exit;
}

$employer_id = $_SESSION['user_id'];

if (!isset($_GET['job_id']) || !is_numeric($_GET['job_id'])) {
    echo "<div class='container py-4'><div class='alert alert-danger'>Invalid Job ID.</div></div>";
    include("../includes/footer.php");
    exit;
}

$job_id = intval($_GET['job_id']);
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';

// Check if job belongs to employer
$job_check = mysqli_query($conn, "SELECT * FROM jobs WHERE id = $job_id AND employer_id = $employer_id");
if (mysqli_num_rows($job_check) == 0) {
    echo "<div class='container py-4'><div class='alert alert-danger'>Unauthorized access.</div></div>";
    include("../includes/footer.php");
    exit;
}
?>
<head><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<div class="container py-4">
  <h2>Applicants for Job #<?= $job_id ?></h2>

  <!-- Filter Dropdown -->
  <form method="get" class="mb-3">
    <input type="hidden" name="job_id" value="<?= htmlspecialchars($job_id) ?>">
    <label for="status">Filter by Status:</label>
    <select name="status" id="status" class="form-select w-auto d-inline-block" onchange="this.form.submit()">
      <option value="all" <?= $status_filter == 'all' ? 'selected' : '' ?>>All</option>
      <option value="Pending" <?= $status_filter == 'Pending' ? 'selected' : '' ?>>Pending</option>
      <option value="Shortlisted" <?= $status_filter == 'Shortlisted' ? 'selected' : '' ?>>Shortlisted</option>
      <option value="Rejected" <?= $status_filter == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
    </select>
  </form>

  <table class="table table-bordered table-sm mt-3">
    <thead>
      <tr>
        <th>Name</th>
        <th>Education</th>
        <th>Experience</th>
        <th>Resume</th>
        <th>Applied On</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
<?php
$where = "a.job_id = $job_id";

if ($status_filter === 'Pending') {
    $where .= " AND (a.status IS NULL OR a.status = '')";
} elseif (in_array($status_filter, ['Shortlisted', 'Rejected'])) {
    $where .= " AND a.status = '$status_filter'";
}

$sql = "SELECT a.id as application_id, a.applied_at, a.status, a.note, s.*
        FROM applications a 
        JOIN seekers s ON a.seeker_id = s.user_id 
        WHERE $where 
        ORDER BY a.applied_at DESC";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0):
    while ($applicant = mysqli_fetch_assoc($result)):
?>
<tr>
  <td><?= htmlspecialchars($applicant['full_name']) ?></td>
  <td><?= htmlspecialchars($applicant['education']) ?></td>
  <td><?= htmlspecialchars($applicant['experience']) ?></td>
  <td>
    <?php if ($applicant['resume']): ?>
    <a href="../uploads/<?= $applicant['resume'] ?>" class="btn btn-sm btn-outline-primary" target="_blank">Download</a>
    <?php else: ?>
      <span class="text-muted">No Resume</span>
    <?php endif; ?>
  </td>
  <td><?= date("M d, Y", strtotime($applicant['applied_at'])) ?></td>
  <td>
    <?php if ($applicant['status'] == 'Shortlisted'): ?>
      <span class="badge bg-success">Shortlisted</span>
    <?php elseif ($applicant['status'] == 'Rejected'): ?>
      <span class="badge bg-danger">Rejected</span>
    <?php else: ?>
      <span class="badge bg-warning text-dark">Pending</span>
    <?php endif; ?>
  </td>
</tr>
<tr>
  <td colspan="6">
    <!-- Status Form -->
    <form class="application-status-form d-flex align-items-center gap-2" data-app-id="<?= $applicant['application_id'] ?>">
      <label class="fw-bold me-2">Status:</label>
      <select class="form-select form-select-sm w-auto status-select" name="status">
        <option value="Shortlisted" <?= $applicant['status'] == 'Shortlisted' ? 'selected' : '' ?>>Shortlisted</option>
        <option value="Rejected" <?= $applicant['status'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
      </select>
      <button type="submit" class="btn btn-primary btn-sm">Update</button>
      <span class="status-msg text-success small ms-3"></span>
    </form>
<!-- Toggle Note Icon -->
<div class="mt-3">
  <i class="fas fa-note-sticky text-info" style="cursor:pointer;" 
     onclick="toggleNoteBox(<?= $applicant['application_id'] ?>)" 
     title="Add/View Note"></i>

  <!-- Hidden Note Box -->
  <div id="note-box-<?= $applicant['application_id'] ?>" class="mt-2" style="display:none;">
    <label class="fw-bold">Private Note:</label>
    <textarea class="form-control note-textarea" rows="2" data-app-id="<?= $applicant['application_id'] ?>"><?= htmlspecialchars($applicant['note']) ?></textarea>
    <button class="btn btn-success btn-sm mt-1 save-note-btn" data-app-id="<?= $applicant['application_id'] ?>">Save Note</button>
    <span class="note-msg small text-success ms-2"></span>
  </div>
</div>

  </td>
</tr>
<?php endwhile; else: ?>
<tr><td colspan="6" class="text-muted">No applicants found for selected status.</td></tr>
<?php endif; ?>
</tbody>
  </table>

  <a href="dashboard.php" class="btn btn-secondary btn-sm">Back to Dashboard</a>
</div>

<!-- AJAX Scripts -->
<script>
// Save Status
document.querySelectorAll(".application-status-form").forEach(form => {
  form.addEventListener("submit", function (e) {
    e.preventDefault();
    const appId = this.getAttribute("data-app-id");
    const status = this.querySelector(".status-select").value;
    const msgSpan = this.querySelector(".status-msg");

    fetch("update_application_status.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: `application_id=${appId}&status=${status}`
    })
    .then(res => res.json())
    .then(data => {
      msgSpan.textContent = data.message;
      msgSpan.classList.toggle("text-danger", data.status !== "success");
      msgSpan.classList.toggle("text-success", data.status === "success");
    })
    .catch(() => {
      msgSpan.textContent = "Network error.";
      msgSpan.classList.add("text-danger");
    });
  });
});

// Save Notes
document.querySelectorAll(".save-note-btn").forEach(button => {
  button.addEventListener("click", function () {
    const appId = this.getAttribute("data-app-id");
    const textarea = document.querySelector(`.note-textarea[data-app-id="${appId}"]`);
    const note = encodeURIComponent(textarea.value);
    const msgSpan = this.nextElementSibling;

    fetch("update_note.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: `application_id=${appId}&note=${note}`
    })
    .then(res => res.json())
    .then(data => {
      msgSpan.textContent = data.message;
      msgSpan.classList.toggle("text-danger", data.status !== "success");
      msgSpan.classList.toggle("text-success", data.status === "success");
    })
    .catch(() => {
      msgSpan.textContent = "Failed to save note.";
      msgSpan.classList.add("text-danger");
    });
  });
});
function toggleNoteBox(appId) {
  const box = document.getElementById("note-box-" + appId);
  if (box) box.style.display = box.style.display === "none" ? "block" : "none";
}
</script>

<?php include("../includes/footer.php"); ?>
