<?php
require_once 'config.php';

// Start the session only if it hasn't been started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Your existing authentication functions
function isLoggedIn() {
    return isset($_SESSION['user_id']); // Example check
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

// Redirect if user doesn't have admin privileges
function requireAdmin() {
    requireLogin();
    if (isset($_SESSION['user_role']) && $_SESSION['user_role'] !== 'admin') {
        header("Location: dashboard.php");
        exit();
    }
}
?>