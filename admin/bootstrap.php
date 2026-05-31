<?php
/**
 * Admin bootstrap - tum admin sayfalarinin ilk satirinda require edilir.
 */

require_once __DIR__ . '/../includes/bootstrap.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    $sessName = $GLOBALS['CORPOTH_CONFIG']['security']['session_name'] ?? 'corpoth_admin';
    session_name($sessName);
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => !empty($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    session_start();
}

/** Login disindaki tum admin sayfalari icin koruma */
function admin_guard(): void
{
    if (!is_admin_logged_in()) {
        $next = $_SERVER['REQUEST_URI'] ?? '/admin/dashboard.php';
        redirect('/admin/index.php?next=' . urlencode($next));
    }
}
