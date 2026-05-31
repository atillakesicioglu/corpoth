<?php
/**
 * Corpoth - Tek seferlik kurulum scripti.
 *
 * Bu dosyayi sunucuya yukledikten sonra https://www.corpoth.com/install.php
 * adresinden bir kez calistirin. Yapacaklari:
 *   1. config.php var mi kontrol eder, yoksa formla olusturur
 *   2. db/schema.sql ve db/seed.sql'i import eder (veritabani bos olmalidir)
 *   3. Admin kullanicisi icin sifre belirletir
 *
 * GUVENLIK: Kurulum tamamlandiktan sonra bu dosyayi mutlaka silin!
 */

if (file_exists(__DIR__ . '/install.lock')) {
    http_response_code(403);
    exit('Kurulum daha once tamamlanmis. Yeni bir kurulum icin install.lock dosyasini silin.');
}

$step      = $_GET['step'] ?? 'check';
$configPath = __DIR__ . '/includes/config.php';
$errors    = [];
$message   = null;

function h($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); }

/* ------------- 1. Config ----------------------------------------------- */
if (!file_exists($configPath)) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['db_host'])) {
        $cfg = [
            'db' => [
                'host'    => trim($_POST['db_host']),
                'name'    => trim($_POST['db_name']),
                'user'    => trim($_POST['db_user']),
                'pass'    => $_POST['db_pass'] ?? '',
                'charset' => 'utf8mb4',
            ],
            'app' => [
                'name'        => 'Corpoth',
                'base_url'    => trim($_POST['base_url'] ?? 'https://www.corpoth.com'),
                'environment' => 'production',
                'timezone'    => 'Europe/Istanbul',
                'debug'       => false,
            ],
            'mail' => [
                'from_email' => trim($_POST['mail_from'] ?? 'no-reply@corpoth.com'),
                'from_name'  => 'Corpoth Web',
            ],
            'security' => [
                'session_name'             => 'corpoth_admin',
                'login_max_attempts'       => 5,
                'login_lockout_minutes'    => 15,
                'csrf_token_lifetime_min'  => 60,
            ],
            'upload' => [
                'max_size_bytes' => 5 * 1024 * 1024,
                'allowed_mimes'  => ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml', 'image/gif'],
                'directory'      => __DIR__ . '/uploads',
                'url_path'       => '/uploads',
            ],
        ];

        // Test baglanti
        try {
            $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $cfg['db']['host'], $cfg['db']['name']);
            new PDO($dsn, $cfg['db']['user'], $cfg['db']['pass']);
        } catch (PDOException $e) {
            $errors[] = 'Veritabanına bağlanılamadı: ' . $e->getMessage();
        }

        if (!$errors) {
            $php = "<?php\nreturn " . var_export($cfg, true) . ";\n";
            if (!file_put_contents($configPath, $php)) {
                $errors[] = 'config.php yazılamadı (klasör yazma izni?).';
            } else {
                header('Location: install.php?step=schema');
                exit;
            }
        }
    }
    ?>
    <!doctype html><html lang="tr"><head><meta charset="utf-8"><title>Kurulum 1/3</title>
    <script src="https://cdn.tailwindcss.com"></script></head>
    <body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
      <div class="max-w-xl w-full bg-white rounded-2xl shadow-lg p-8">
        <h1 class="text-xl font-bold mb-1">Corpoth Kurulum 1/3</h1>
        <p class="text-sm text-slate-500 mb-6">Veritabanı bağlantısı.</p>
        <?php foreach ($errors as $err): ?>
          <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-3 text-sm mb-4"><?= h($err) ?></div>
        <?php endforeach; ?>
        <form method="post" class="space-y-3">
          <div><label class="block text-xs font-bold mb-1">DB Host</label><input class="w-full border rounded-xl px-3 py-2" name="db_host" value="<?= h($_POST['db_host'] ?? 'localhost') ?>"></div>
          <div><label class="block text-xs font-bold mb-1">DB Adı</label><input class="w-full border rounded-xl px-3 py-2" name="db_name" required value="<?= h($_POST['db_name'] ?? '') ?>"></div>
          <div><label class="block text-xs font-bold mb-1">DB Kullanıcı</label><input class="w-full border rounded-xl px-3 py-2" name="db_user" required value="<?= h($_POST['db_user'] ?? '') ?>"></div>
          <div><label class="block text-xs font-bold mb-1">DB Şifre</label><input type="password" class="w-full border rounded-xl px-3 py-2" name="db_pass"></div>
          <div><label class="block text-xs font-bold mb-1">Site URL'si</label><input class="w-full border rounded-xl px-3 py-2" name="base_url" value="<?= h($_POST['base_url'] ?? 'https://www.corpoth.com') ?>"></div>
          <div><label class="block text-xs font-bold mb-1">Mail From (no-reply)</label><input class="w-full border rounded-xl px-3 py-2" name="mail_from" value="<?= h($_POST['mail_from'] ?? 'no-reply@corpoth.com') ?>"></div>
          <button class="bg-slate-900 text-white px-5 py-2.5 rounded-xl font-semibold w-full">Devam Et →</button>
        </form>
      </div>
    </body></html>
    <?php
    exit;
}

/* ------------- 2. Schema + seed import --------------------------------- */
require_once __DIR__ . '/includes/bootstrap.php';

if ($step === 'schema') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $sql = file_get_contents(__DIR__ . '/db/schema.sql');
            db()->exec($sql);
            $sql = file_get_contents(__DIR__ . '/db/seed.sql');
            // seed icindeki admin password placeholder'i atlamak icin
            // bir sonraki adim yine asil hash'i yazacak. Yine de import edilir.
            db()->exec($sql);
            header('Location: install.php?step=admin');
            exit;
        } catch (Throwable $e) {
            $errors[] = 'Import hatası: ' . $e->getMessage();
        }
    }
    ?>
    <!doctype html><html lang="tr"><head><meta charset="utf-8"><title>Kurulum 2/3</title>
    <script src="https://cdn.tailwindcss.com"></script></head>
    <body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
      <div class="max-w-xl w-full bg-white rounded-2xl shadow-lg p-8">
        <h1 class="text-xl font-bold mb-1">Corpoth Kurulum 2/3</h1>
        <p class="text-sm text-slate-500 mb-6">Veritabanı tabloları ve başlangıç verileri içeri aktarılacak. Bu adım mevcut tabloları siler!</p>
        <?php foreach ($errors as $err): ?>
          <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-3 text-sm mb-4"><?= h($err) ?></div>
        <?php endforeach; ?>
        <form method="post"><button class="bg-slate-900 text-white px-5 py-2.5 rounded-xl font-semibold w-full">Şemayı İçeri Aktar</button></form>
      </div>
    </body></html>
    <?php
    exit;
}

/* ------------- 3. Admin sifresi ---------------------------------------- */
if ($step === 'admin') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? 'admin');
        $email    = trim($_POST['email'] ?? '');
        $pass1    = $_POST['password']  ?? '';
        $pass2    = $_POST['password2'] ?? '';

        if ($username === '' || mb_strlen($username) < 3) $errors[] = 'Kullanıcı adı en az 3 karakter olmalı.';
        if (mb_strlen($pass1) < 8) $errors[] = 'Şifre en az 8 karakter olmalı.';
        if ($pass1 !== $pass2) $errors[] = 'Şifreler eşleşmiyor.';

        if (!$errors) {
            $hash = password_hash($pass1, PASSWORD_BCRYPT);
            // Mevcut admin'i guncelle veya yeni olustur
            $stmt = db()->prepare('SELECT id FROM admin_users WHERE username = ?');
            $stmt->execute([$username]);
            $row = $stmt->fetch();
            if ($row) {
                db()->prepare('UPDATE admin_users SET password_hash = ?, email = ?, must_change_password = 0 WHERE id = ?')
                   ->execute([$hash, $email, (int) $row['id']]);
            } else {
                db()->prepare('INSERT INTO admin_users (username, password_hash, email, must_change_password) VALUES (?, ?, ?, 0)')
                   ->execute([$username, $hash, $email]);
            }
            file_put_contents(__DIR__ . '/install.lock', date('c'));
            header('Location: install.php?step=done');
            exit;
        }
    }
    ?>
    <!doctype html><html lang="tr"><head><meta charset="utf-8"><title>Kurulum 3/3</title>
    <script src="https://cdn.tailwindcss.com"></script></head>
    <body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
      <div class="max-w-xl w-full bg-white rounded-2xl shadow-lg p-8">
        <h1 class="text-xl font-bold mb-1">Corpoth Kurulum 3/3</h1>
        <p class="text-sm text-slate-500 mb-6">Admin kullanıcısı ve şifresini belirleyin.</p>
        <?php foreach ($errors as $err): ?>
          <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-3 text-sm mb-4"><?= h($err) ?></div>
        <?php endforeach; ?>
        <form method="post" class="space-y-3" autocomplete="off">
          <div><label class="block text-xs font-bold mb-1">Kullanıcı Adı</label><input class="w-full border rounded-xl px-3 py-2" name="username" required value="admin"></div>
          <div><label class="block text-xs font-bold mb-1">E-posta</label><input type="email" class="w-full border rounded-xl px-3 py-2" name="email" required></div>
          <div><label class="block text-xs font-bold mb-1">Şifre (min 8 karakter)</label><input type="password" class="w-full border rounded-xl px-3 py-2" name="password" minlength="8" required></div>
          <div><label class="block text-xs font-bold mb-1">Şifre Tekrar</label><input type="password" class="w-full border rounded-xl px-3 py-2" name="password2" minlength="8" required></div>
          <button class="bg-slate-900 text-white px-5 py-2.5 rounded-xl font-semibold w-full">Kurulumu Tamamla</button>
        </form>
      </div>
    </body></html>
    <?php
    exit;
}

if ($step === 'done') {
    ?>
    <!doctype html><html lang="tr"><head><meta charset="utf-8"><title>Kurulum tamamlandi</title>
    <script src="https://cdn.tailwindcss.com"></script></head>
    <body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
      <div class="max-w-xl w-full bg-white rounded-2xl shadow-lg p-8 text-center">
        <h1 class="text-2xl font-bold mb-2">Kurulum tamamlandı.</h1>
        <p class="text-slate-600 mb-6">Şimdi admin paneline giriş yapabilirsiniz.</p>
        <p class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-xl p-3 mb-6">
          GÜVENLİK: <code>install.php</code> dosyasını sunucudan SİLİN. <code>install.lock</code> dosyası mevcut olduğu sürece tekrar çalışmaz, ama yine de silinmesi tavsiye edilir.
        </p>
        <a href="/admin/" class="inline-block bg-slate-900 text-white px-6 py-3 rounded-xl font-semibold">Admin Paneline Git →</a>
      </div>
    </body></html>
    <?php
    exit;
}

header('Location: install.php?step=schema');
exit;
