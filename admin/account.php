<?php
require_once __DIR__ . '/bootstrap.php';
admin_guard();

$user = user_get((int) $_SESSION['admin_user_id']);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_check()) {
        $errors[] = 'CSRF doğrulaması başarısız.';
    } else {
        $current = (string) ($_POST['current_password'] ?? '');
        $new1    = (string) ($_POST['new_password']     ?? '');
        $new2    = (string) ($_POST['new_password2']    ?? '');

        if (!password_verify($current, $user['password_hash'])) {
            $errors[] = 'Mevcut şifreniz hatalı.';
        }
        if (mb_strlen($new1) < 8) {
            $errors[] = 'Yeni şifre en az 8 karakter olmalı.';
        }
        if ($new1 !== $new2) {
            $errors[] = 'Yeni şifre tekrarı eşleşmiyor.';
        }

        if (!$errors) {
            user_set_password((int) $user['id'], password_hash($new1, PASSWORD_BCRYPT));
            $_SESSION['must_change_password'] = 0;
            audit_log('password_change', 'admin_users', (int) $user['id']);
            flash_set('success', 'Şifreniz güncellendi.');
            redirect('/admin/account.php');
        }
    }
}

$pageTitle  = 'Hesabım';
$activePage = 'account';
require __DIR__ . '/partials/header.php';
?>

<div class="card max-w-xl">
  <h2 class="text-lg font-bold mb-1">Şifre Değiştir</h2>
  <p class="text-sm text-slate-500 mb-6">Kullanıcı: <span class="font-semibold"><?= e($user['username']) ?></span></p>

  <?php foreach ($errors as $err): ?>
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 text-red-800 px-4 py-3 text-sm"><?= e($err) ?></div>
  <?php endforeach; ?>

  <form method="post" autocomplete="off">
    <?= csrf_field() ?>
    <div class="field">
      <label for="current_password">Mevcut Şifre</label>
      <input id="current_password" name="current_password" type="password" required>
    </div>
    <div class="field">
      <label for="new_password">Yeni Şifre</label>
      <input id="new_password" name="new_password" type="password" minlength="8" required>
      <span class="help">En az 8 karakter.</span>
    </div>
    <div class="field">
      <label for="new_password2">Yeni Şifre (Tekrar)</label>
      <input id="new_password2" name="new_password2" type="password" minlength="8" required>
    </div>
    <button class="btn btn-primary" type="submit"><span class="material-symbols-outlined text-base">save</span> Kaydet</button>
  </form>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
