<?php
require_once __DIR__ . '/bootstrap.php';
admin_guard();

$hero = hero_get();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $data = [
        'eyebrow'            => trim($_POST['eyebrow'] ?? ''),
        'title_html'         => $_POST['title_html'] ?? '',
        'description'        => trim($_POST['description'] ?? ''),
        'image_path'         => trim($_POST['image_path'] ?? ''),
        'image_alt'          => trim($_POST['image_alt'] ?? ''),
        'primary_cta_text'   => trim($_POST['primary_cta_text'] ?? ''),
        'primary_cta_href'   => trim($_POST['primary_cta_href'] ?? ''),
        'secondary_cta_text' => trim($_POST['secondary_cta_text'] ?? ''),
        'secondary_cta_href' => trim($_POST['secondary_cta_href'] ?? ''),
        'badge_value'        => trim($_POST['badge_value'] ?? ''),
        'badge_text'         => trim($_POST['badge_text'] ?? ''),
    ];

    if (!empty($_FILES['image_upload']['name'])) {
        try {
            $u = media_handle_upload($_FILES['image_upload'], (int) $_SESSION['admin_user_id']);
            $data['image_path'] = $u['path'];
        } catch (Throwable $e) {
            flash_set('error', $e->getMessage());
        }
    }

    hero_update($data);
    audit_log('hero_update', 'hero', 1);
    flash_set('success', 'Hero güncellendi.');
    redirect('/admin/hero.php');
}

$pageTitle  = 'Hero';
$activePage = 'hero';
require __DIR__ . '/partials/header.php';
?>
<form method="post" enctype="multipart/form-data" class="card max-w-3xl space-y-4">
  <?= csrf_field() ?>
  <div class="field">
    <label>Üst Etiket (Eyebrow)</label>
    <input type="text" name="eyebrow" value="<?= e($hero['eyebrow'] ?? '') ?>">
  </div>
  <div class="field">
    <label>Başlık (HTML)</label>
    <textarea name="title_html" rows="3"><?= e($hero['title_html'] ?? '') ?></textarea>
    <span class="help">HTML kullanılabilir. Vurgu için <code>&lt;span class="text-primary"&gt;...&lt;/span&gt;</code></span>
  </div>
  <div class="field">
    <label>Açıklama</label>
    <textarea name="description" rows="3"><?= e($hero['description'] ?? '') ?></textarea>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="field">
      <label>Birincil CTA Metni</label>
      <input type="text" name="primary_cta_text" value="<?= e($hero['primary_cta_text'] ?? '') ?>">
    </div>
    <div class="field">
      <label>Birincil CTA Linki</label>
      <input type="text" name="primary_cta_href" value="<?= e($hero['primary_cta_href'] ?? '#contact') ?>">
    </div>
    <div class="field">
      <label>İkincil CTA Metni</label>
      <input type="text" name="secondary_cta_text" value="<?= e($hero['secondary_cta_text'] ?? '') ?>">
    </div>
    <div class="field">
      <label>İkincil CTA Linki</label>
      <input type="text" name="secondary_cta_href" value="<?= e($hero['secondary_cta_href'] ?? '#service') ?>">
    </div>
    <div class="field">
      <label>Rozet Değer (örn. 10dk)</label>
      <input type="text" name="badge_value" value="<?= e($hero['badge_value'] ?? '') ?>">
    </div>
    <div class="field">
      <label>Rozet Metni</label>
      <input type="text" name="badge_text" value="<?= e($hero['badge_text'] ?? '') ?>">
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="field">
      <label>Görsel Yolu</label>
      <input type="text" name="image_path" value="<?= e($hero['image_path'] ?? '') ?>" placeholder="/uploads/...veya /assets/images/...">
    </div>
    <div class="field">
      <label>Görsel Alt Metni</label>
      <input type="text" name="image_alt" value="<?= e($hero['image_alt'] ?? '') ?>">
    </div>
  </div>

  <div class="field">
    <label>Görsel Yükle (opsiyonel)</label>
    <input type="file" name="image_upload" accept="image/*">
    <span class="help">Yüklenen dosya otomatik olarak görsel yoluna atanır.</span>
  </div>

  <?php if (!empty($hero['image_path'])): ?>
  <div>
    <p class="text-xs text-slate-500 mb-1">Mevcut görsel:</p>
    <img src="<?= e($hero['image_path']) ?>" alt="" class="max-h-48 rounded-xl border border-slate-200">
  </div>
  <?php endif; ?>

  <div class="pt-2">
    <button class="btn btn-primary" type="submit"><span class="material-symbols-outlined text-base">save</span> Kaydet</button>
    <a class="btn btn-ghost" href="/" target="_blank"><span class="material-symbols-outlined text-base">visibility</span> Önizle</a>
  </div>
</form>

<?php require __DIR__ . '/partials/footer.php'; ?>
