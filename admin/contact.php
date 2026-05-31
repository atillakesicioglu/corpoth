<?php
require_once __DIR__ . '/bootstrap.php';
admin_guard();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $values = [];
    foreach ($_POST as $k => $v) {
        if (str_starts_with((string) $k, 'set_')) {
            $values[substr($k, 4)] = is_string($v) ? trim($v) : '';
        }
    }
    if ($values) {
        settings_update($values);
        audit_log('settings_update', 'settings', null, array_keys($values));
        flash_set('success', 'İletişim bilgileri kaydedildi.');
    }
    redirect('/admin/contact.php');
}

$pageTitle  = 'İletişim Bilgileri';
$activePage = 'contact';
require __DIR__ . '/partials/header.php';
?>

<form method="post" class="card max-w-2xl space-y-4">
  <?= csrf_field() ?>
  <h2 class="font-bold text-lg">İletişim Bilgileri</h2>
  <p class="text-sm text-slate-500">Site genelinde (header, footer, iletişim bölümü) görünen iletişim bilgileri.</p>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="field"><label>Telefon (uluslararası format)</label><input type="text" name="set_contact_phone" value="<?= e(setting('contact_phone')) ?>" placeholder="+905322212323"></div>
    <div class="field"><label>Telefon (görünen)</label><input type="text" name="set_contact_phone_label" value="<?= e(setting('contact_phone_label')) ?>"></div>
    <div class="field"><label>WhatsApp Numarası</label><input type="text" name="set_contact_whatsapp" value="<?= e(setting('contact_whatsapp')) ?>"></div>
    <div class="field"><label>E-posta</label><input type="email" name="set_contact_email" value="<?= e(setting('contact_email')) ?>"></div>
    <div class="field"><label>Web Sitesi</label><input type="url" name="set_contact_website" value="<?= e(setting('contact_website')) ?>"></div>
    <div class="field"><label>LinkedIn URL</label><input type="url" name="set_contact_linkedin" value="<?= e(setting('contact_linkedin')) ?>"></div>
  </div>

  <div class="field">
    <label>Adres</label>
    <textarea name="set_contact_address" rows="3"><?= e(setting('contact_address')) ?></textarea>
  </div>

  <div class="field">
    <label>Footer Hakkımızda Metni</label>
    <textarea name="set_footer_about" rows="3"><?= e(setting('footer_about')) ?></textarea>
  </div>

  <div class="field">
    <label>Lead Bildirim E-postası</label>
    <input type="email" name="set_lead_notify_email" value="<?= e(setting('lead_notify_email')) ?>">
    <span class="help">Form gönderildiğinde bildirim bu adrese düşer.</span>
  </div>

  <button class="btn btn-primary" type="submit"><span class="material-symbols-outlined text-base">save</span> Kaydet</button>
</form>

<?php require __DIR__ . '/partials/footer.php'; ?>
