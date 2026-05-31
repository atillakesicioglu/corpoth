<?php
require_once __DIR__ . '/bootstrap.php';

if (is_admin_logged_in()) {
    redirect('/admin/dashboard.php');
}

$error = null;
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if (!csrf_check()) {
        $error = 'Oturum doğrulaması başarısız oldu, lütfen sayfayı yenileyin.';
    } else {
        $cfgSec  = $GLOBALS['CORPOTH_CONFIG']['security'];
        $maxFail = $cfgSec['login_max_attempts']    ?? 5;
        $lockMin = $cfgSec['login_lockout_minutes'] ?? 15;
        $ip      = client_ip();

        if (login_attempt_recent_failures($ip, $lockMin) >= $maxFail) {
            $error = "Çok fazla başarısız deneme. Lütfen $lockMin dakika sonra tekrar deneyin.";
        } elseif ($username === '' || $password === '') {
            $error = 'Kullanıcı adı ve şifre zorunlu.';
        } else {
            $user = user_find_by_username($username);
            $valid = $user && password_verify($password, $user['password_hash']);

            login_attempt_record($ip, $username, $valid);

            if ($valid) {
                session_regenerate_id(true);
                $_SESSION['admin_user_id']  = (int) $user['id'];
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['must_change_password'] = (int) $user['must_change_password'];
                user_record_login((int) $user['id']);
                audit_log('login', 'admin_users', (int) $user['id']);

                $next = $_GET['next'] ?? '/admin/dashboard.php';
                if (!preg_match('#^/admin/#', $next)) $next = '/admin/dashboard.php';
                redirect($next);
            } else {
                $error = 'Kullanıcı adı veya şifre hatalı.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title>Giriş | Corpoth Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="icon" type="image/png" href="/assets/images/corpoth-logo-icon.png"/>
<style>body{font-family:Inter,system-ui,sans-serif;background:#f5f3f3}</style>
</head>
<body class="min-h-screen flex items-center justify-center px-4">
  <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
    <div class="flex flex-col items-center mb-8">
      <img src="/assets/images/corpoth-logo.png" alt="CORPOTH" class="h-12 mb-3"/>
      <h1 class="text-xl font-bold text-slate-800">Yönetim Paneli</h1>
      <p class="text-sm text-slate-500">Lütfen giriş yapın</p>
    </div>

    <?php if ($error): ?>
    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 text-red-800 px-4 py-3 text-sm"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="post" class="space-y-4" autocomplete="off">
      <?= csrf_field() ?>
      <div>
        <label class="block text-sm font-semibold mb-2 text-slate-700" for="username">Kullanıcı Adı</label>
        <input id="username" name="username" type="text" required value="<?= e($username) ?>" class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 focus:border-slate-900 focus:ring-2 focus:ring-slate-200 outline-none"/>
      </div>
      <div>
        <label class="block text-sm font-semibold mb-2 text-slate-700" for="password">Şifre</label>
        <input id="password" name="password" type="password" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 focus:border-slate-900 focus:ring-2 focus:ring-slate-200 outline-none"/>
      </div>
      <button type="submit" class="w-full rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold py-3 transition-colors">
        Giriş Yap
      </button>
    </form>

    <p class="text-center text-xs text-slate-400 mt-6">© <?= date('Y') ?> CORPOTH</p>
  </div>
</body>
</html>
