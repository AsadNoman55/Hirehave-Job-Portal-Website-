<?php
session_start();
require_once "../includes/db.php";

// Check if user is an employer
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'employer') {
    echo json_encode(["status" => "error", "message" => "Unauthorized access."]);
    exit;
}

$employer_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $application_id = intval($_POST['application_id'] ?? 0);
    $note = trim($_POST['note'] ?? '');

    if ($application_id <= 0) {
        echo json_encode(["status" => "error", "message" => "Invalid application ID."]);
        exit;
    }

    // Verify the application belongs to the employer
    $check_sql = "SELECT a.id FROM applications a
                  JOIN jobs j ON a.job_id = j.id
                  WHERE a.id = ? AND j.employer_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $application_id, $employer_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["status" => "error", "message" => "Application not found or access denied."]);
        exit;
    }

    // Update note
    $update_sql = "UPDATE applications SET note = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $note, $application_id);

    if ($update_stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Note saved."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to save note."]);
    }

    $update_stmt->close();
    $check_stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>
