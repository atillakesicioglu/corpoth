<?php
require_once __DIR__ . '/bootstrap.php';
admin_guard();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $values = [];
    foreach ($_POST as $k => $v) {
        if (str_starts_with((string) $k, 'set_')) {
            $values[substr($k, 4)] = is_string($v) ? $v : '';
        }
    }
    if ($values) {
        settings_update($values);
        audit_log('settings_update', 'settings', null, array_keys($values));
        flash_set('success', 'Ayarlar kaydedildi.');
    }
    redirect('/admin/settings.php');
}

$pageTitle  = 'Genel Ayarlar';
$activePage = 'settings';
require __DIR__ . '/partials/header.php';
?>

<form method="post" class="space-y-4 max-w-3xl">
  <?= csrf_field() ?>

  <div class="card space-y-4">
    <h2 class="font-bold text-lg">SEO</h2>
    <div class="field"><label>Site Başlığı</label><input type="text" name="set_site_title" value="<?= e(setting('site_title')) ?>"></div>
    <div class="field"><label>Meta Description</label><textarea name="set_site_description" rows="3"><?= e(setting('site_description')) ?></textarea></div>
    <div class="field"><label>Keywords</label><input type="text" name="set_site_keywords" value="<?= e(setting('site_keywords')) ?>"></div>
    <div class="field"><label>Canonical URL</label><input type="url" name="set_canonical_url" value="<?= e(setting('canonical_url')) ?>"></div>
    <div class="field"><label>OG Görsel Yolu</label><input type="text" name="set_og_image" value="<?= e(setting('og_image')) ?>"></div>
  </div>

  <div class="card space-y-4">
    <h2 class="font-bold text-lg">Analitik</h2>
    <div class="field"><label>Google Analytics 4 ID</label><input type="text" name="set_ga_id" value="<?= e(setting('ga_id')) ?>" placeholder="G-XXXXXXXXXX"></div>
    <div class="field"><label>Microsoft Clarity ID</label><input type="text" name="set_clarity_id" value="<?= e(setting('clarity_id')) ?>"></div>
  </div>

  <div class="card space-y-4">
    <h2 class="font-bold text-lg">Çerez Bildirimi</h2>
    <div class="field"><label>Çerez Bildirim Metni (HTML)</label><textarea name="set_cookie_text" rows="3"><?= e(setting('cookie_text')) ?></textarea></div>
  </div>

  <div class="card space-y-4">
    <h2 class="font-bold text-lg">KVKK / Gizlilik</h2>
    <div class="field"><label>KVKK HTML</label><textarea name="set_kvkk_html" rows="10" class="font-mono text-xs"><?= e(setting('kvkk_html')) ?></textarea><span class="help">HTML kullanılabilir. KVKK metni kvkk.php sayfasında gösterilir.</span></div>
    <div class="field"><label>Gizlilik HTML</label><textarea name="set_privacy_html" rows="10" class="font-mono text-xs"><?= e(setting('privacy_html')) ?></textarea><span class="help">gizlilik.php sayfasında gösterilir.</span></div>
  </div>

  <div class="sticky bottom-0 bg-slate-100 py-3">
    <button class="btn btn-primary" type="submit"><span class="material-symbols-outlined text-base">save</span> Tüm Ayarları Kaydet</button>
  </div>
</form>

<?php require __DIR__ . '/partials/footer.php'; ?>
