<?php
session_start();

// Log logout action for audit (optional)
$admin_username = $_SESSION['admin_username'] ?? 'Unknown';
error_log('Admin logout: ' . $admin_username . ' at ' . date('Y-m-d H:i:s'));

// Unset all admin session keys
foreach (['admin_logged_in','admin_id','admin_username','login_time'] as $k) {
    if (isset($_SESSION[$k])) {
        unset($_SESSION[$k]);
    }
}

// Destroy session cookie and session
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params['path'], $params['domain'], $params['secure'], $params['httponly']
    );
}
session_destroy();

// Redirect to login with success message
header('Location: login.php?logout=success');
exit;
