<?php
session_start();
require_once "../includes/db.php";

// Check if user is an employer
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'employer') {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Access denied."]);
    exit;
}

// Validate POST data
if (!isset($_POST['application_id'], $_POST['status'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
    exit;
}

$application_id = intval($_POST['application_id']);
$status = $_POST['status'];
$allowed_statuses = ['Shortlisted', 'Rejected'];

// Validate status
if (!in_array($status, $allowed_statuses)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid status value."]);
    exit;
}

// Verify the application belongs to a job posted by this employer
$employer_id = $_SESSION['user_id'];
$query = "SELECT a.id 
          FROM applications a
          JOIN jobs j ON a.job_id = j.id
          WHERE a.id = ? AND j.employer_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $application_id, $employer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Unauthorized access."]);
    exit;
}

// Update the application status
$update = $conn->prepare("UPDATE applications SET status = ? WHERE id = ?");
$update->bind_param("si", $status, $application_id);

if ($update->execute()) {
    echo json_encode(["status" => "success", "message" => "Status updated to $status"]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Failed to update status."]);
}
?>
