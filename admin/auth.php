<?php
// Include this file at the top of any admin page that needs protection.
session_start();

// If not logged in as admin, redirect to login page
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Verify required session variables exist
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_username'])) {
    session_destroy();
    header('Location: login.php?error=invalid_session');
    exit;
}

// Session timeout check (30 minutes of inactivity)
$timeout = 30 * 60; // 30 minutes
if (isset($_SESSION['login_time'])) {
    if (time() - $_SESSION['login_time'] > $timeout) {
        session_destroy();
        header('Location: login.php?expired=1');
        exit;
    }
    // Update last activity time on each page load
    $_SESSION['login_time'] = time();
} else {
    // If login_time is not set for some reason, set it now
    $_SESSION['login_time'] = time();
}

// Security headers to prevent clickjacking and other attacks
if (!headers_sent()) {
    header('X-Frame-Options: DENY');
    header('X-Content-Type-Options: nosniff');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
}
