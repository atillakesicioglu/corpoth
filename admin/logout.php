<?php
require_once __DIR__ . '/bootstrap.php';

if (is_admin_logged_in()) {
    audit_log('logout', 'admin_users', $_SESSION['admin_user_id'] ?? null);
}

$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
session_destroy();
redirect('/admin/index.php');
