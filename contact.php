<?php include("includes/header.php"); ?>
<?php include("includes/db.php"); ?>

<div class="container py-4">
  <h2>Contact Us</h2>
  <p class="text-muted">Have a question or feedback? Send us a message below.</p>

  <?php
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $name = mysqli_real_escape_string($conn, $_POST['name']);
      $email = mysqli_real_escape_string($conn, $_POST['email']);
      $subject = mysqli_real_escape_string($conn, $_POST['subject']);
      $message = mysqli_real_escape_string($conn, $_POST['message']);

      $sql = "INSERT INTO contact_messages (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";
      if (mysqli_query($conn, $sql)) {
          echo "<div class='alert alert-success'>Your message has been sent successfully!</div>";
      } else {
          echo "<div class='alert alert-danger'>There was a problem. Please try again later.</div>";
      }
  }
  ?>

  <form method="POST" class="mt-4">
    <div class="mb-3">
      <label>Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Subject</label>
      <input type="text" name="subject" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Message</label>
      <textarea name="message" class="form-control" rows="5" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Send Message</button>
  </form>
</div>

<?php include("includes/footer.php"); ?>
