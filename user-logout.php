<?php
session_start();

$email = $_SESSION['user_email'] ?? 'Unknown';
error_log('User logout: ' . $email . ' at ' . date('Y-m-d H:i:s'));

foreach (['user_logged_in', 'user_id', 'user_email', 'user_name', 'user_login_time'] as $k) {
    if (isset($_SESSION[$k])) {
        unset($_SESSION[$k]);
    }
}

$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
session_destroy();

header('Location: index.php?logout=success');
exit;
