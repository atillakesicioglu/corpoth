<?php
require_once __DIR__ . '/bootstrap.php';
admin_guard();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $action = $_POST['action'] ?? '';

    if ($action === 'upload' && !empty($_FILES['file']['name'])) {
        try {
            media_handle_upload($_FILES['file'], (int) $_SESSION['admin_user_id']);
            flash_set('success', 'Yükleme başarılı.');
        } catch (Throwable $e) {
            flash_set('error', $e->getMessage());
        }
    } elseif ($action === 'delete') {
        media_delete((int) $_POST['id']);
        flash_set('success', 'Dosya silindi.');
    }

    redirect('/admin/media.php');
}

$rows = media_list(200, 0);

$pageTitle  = 'Medya';
$activePage = 'media';
require __DIR__ . '/partials/header.php';
?>

<div class="card">
  <h2 class="font-bold text-lg mb-4">Yeni Yükle</h2>
  <form method="post" enctype="multipart/form-data" class="flex flex-col md:flex-row gap-3 items-start md:items-center">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="upload">
    <input type="file" name="file" accept="image/*" required class="text-sm">
    <button class="btn btn-primary" type="submit"><span class="material-symbols-outlined text-base">upload</span> Yükle</button>
    <span class="text-xs text-slate-500">Maks. <?= round(($GLOBALS['CORPOTH_CONFIG']['upload']['max_size_bytes'] ?? 5242880) / 1048576, 1) ?> MB. Görseller (JPG/PNG/WebP/GIF/SVG).</span>
  </form>
</div>

<div class="card">
  <h2 class="font-bold text-lg mb-4">Yüklenen Dosyalar (<?= count($rows) ?>)</h2>
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
    <?php foreach ($rows as $m): ?>
    <div class="border border-slate-200 rounded-xl p-2">
      <div class="aspect-square bg-slate-50 rounded-lg overflow-hidden flex items-center justify-center">
        <img src="<?= e($m['path']) ?>" alt="<?= e($m['filename']) ?>" class="max-w-full max-h-full object-contain" loading="lazy">
      </div>
      <div class="text-xs text-slate-700 mt-2 truncate" title="<?= e($m['filename']) ?>"><?= e($m['filename']) ?></div>
      <div class="text-[10px] text-slate-500"><?= round(($m['size'] ?? 0) / 1024) ?> KB <?php if ($m['width']): ?>· <?= (int) $m['width'] ?>×<?= (int) $m['height'] ?><?php endif; ?></div>
      <div class="flex items-center gap-1 mt-2">
        <button type="button" class="btn btn-ghost text-[11px] px-2 py-1" data-copy="<?= e($m['path']) ?>" title="Yolu kopyala">URL</button>
        <a href="<?= e($m['path']) ?>" target="_blank" class="btn btn-ghost btn-icon" title="Aç"><span class="material-symbols-outlined text-base">open_in_new</span></a>
        <form method="post" class="inline" data-confirm="Silinecek?">
          <?= csrf_field() ?>
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="id" value="<?= (int) $m['id'] ?>">
          <button class="btn btn-danger btn-icon" type="submit"><span class="material-symbols-outlined text-base">delete</span></button>
        </form>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php if (!$rows): ?>
    <p class="text-sm text-slate-500">Henüz yüklenmiş dosya yok.</p>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
