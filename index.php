<?php
// Start the session if it hasn't been started yet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include the configuration file to get BASE_URL constant
include 'config/constant.php';

// Check if user is already logged in
if (isset($_SESSION['access_token']) && isset($_SESSION['emp_role_id'])) {
    // Redirect to appropriate dashboard based on role
    $roleId = $_SESSION['emp_role_id'];
    if ($roleId == 1 || $roleId == 2) {
        header("Location: " . BASE_URL . "dashboard-admin");
    } else {
        header("Location: " . BASE_URL . "dashboard-user");
    }
} else {
    // Redirect to login page
    header("Location: " . BASE_URL . "login");
}
exit();
?>
