<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Sanitize input (for form processing)
function clean_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// ✅ Flash message system
function set_flash($key, $message) {
    $_SESSION['flash'][$key] = $message;
}

function get_flash($key) {
    if (isset($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]); // Remove after displaying
        return $msg;
    }
    return '';
}

// ✅ Display flash messages (use in layout)
function display_flash($key, $type = 'success') {
    $msg = get_flash($key);
    if ($msg) {
        echo "<div class='alert alert-$type'>$msg</div>";
    }
}

// ✅ Role-based access check
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function is_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function is_employer() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'employer';
}

function is_seeker() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'seeker';
}
?>
