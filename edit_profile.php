
<?php include("../includes/header.php"); ?>
<?php include("../includes/db.php"); ?>

<?php
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'seeker') {
    echo "<div class='container py-4'><div class='alert alert-danger'>Access denied.</div></div>";
    include("../includes/footer.php");
    exit;
}

$seeker_id = $_SESSION['user_id'];
$success = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $education = mysqli_real_escape_string($conn, $_POST['education']);
    $experience = mysqli_real_escape_string($conn, $_POST['experience']);
    
    $resume = "";
    if (!empty($_FILES['resume']['name'])) {
        $resume = time() . "_" . basename($_FILES["resume"]["name"]);
        $target = "../uploads/" . $resume;
        move_uploaded_file($_FILES["resume"]["tmp_name"], $target);
    }

    // Check if profile exists
    $check = mysqli_query($conn, "SELECT * FROM seekers WHERE user_id = $seeker_id");
    if (mysqli_num_rows($check) > 0) {
        $update_sql = "UPDATE seekers SET full_name='$full_name', education='$education', experience='$experience'";
        if ($resume) $update_sql .= ", resume='$resume'";
        $update_sql .= " WHERE user_id = $seeker_id";
        mysqli_query($conn, $update_sql);
        $success = "Profile updated successfully.";
    } else {
        $insert_sql = "INSERT INTO seekers (user_id, full_name, education, experience, resume)
                       VALUES ($seeker_id, '$full_name', '$education', '$experience', '$resume')";
        mysqli_query($conn, $insert_sql);
        $success = "Profile created successfully.";
    }
}

// Load existing data
$profile = mysqli_query($conn, "SELECT * FROM seekers WHERE user_id = $seeker_id LIMIT 1");
$row = mysqli_fetch_assoc($profile);
?>

<div class="container py-4">
  <h2>Edit Your Profile</h2>

  <?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label>Full Name</label>
      <input type="text" name="full_name" class="form-control" required value="<?= htmlspecialchars($row['full_name'] ?? '') ?>">
    </div>
    <div class="mb-3">
      <label>Education</label>
      <textarea name="education" class="form-control" rows="3"><?= htmlspecialchars($row['education'] ?? '') ?></textarea>
    </div>
    <div class="mb-3">
      <label>Experience</label>
      <textarea name="experience" class="form-control" rows="3"><?= htmlspecialchars($row['experience'] ?? '') ?></textarea>
    </div>
    <div class="mb-3">
      <label>Resume (PDF, DOC)</label>
      <input type="file" name="resume" class="form-control">
      <?php if (!empty($row['resume'])): ?>
        <p class="mt-2"><a href="../uploads/<?= $row['resume'] ?>" target="_blank">View Current Resume</a></p>
      <?php endif; ?>
    </div>
    <button type="submit" class="btn btn-primary">Save Profile</button>
  </form>
</div>

<?php include("../includes/footer.php"); ?>