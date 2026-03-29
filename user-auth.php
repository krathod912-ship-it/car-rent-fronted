<?php
// User authentication - require login for protected pages
session_start();

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: login.php?redirect=' . urlencode($_SERVER['REQUEST_URI'] ?? 'user-dashboard.php'));
    exit;
}

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_email'])) {
    session_destroy();
    header('Location: login.php?error=invalid_session');
    exit;
}
