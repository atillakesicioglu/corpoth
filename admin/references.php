<?php
require_once __DIR__ . '/bootstrap.php';
admin_guard();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $action = $_POST['action'] ?? '';
    if ($action === 'save') {
        $id = (int) ($_POST['id'] ?? 0);
        $d = [
            'name'       => $_POST['name'] ?? '',
            'logo_path'  => $_POST['logo_path'] ?? null,
            'url'        => $_POST['url'] ?? null,
            'sort_order' => $_POST['sort_order'] ?? 0,
            'active'     => !empty($_POST['active']),
        ];
        if (!empty($_FILES['logo_upload']['name'])) {
            try {
                $u = media_handle_upload($_FILES['logo_upload'], (int) $_SESSION['admin_user_id']);
                $d['logo_path'] = $u['path'];
            } catch (Throwable $e) {
                flash_set('error', $e->getMessage());
            }
        }
        if ($id) { reference_update($id, $d); audit_log('reference_update', 'references_logos', $id); }
        else     { reference_create($d);      audit_log('reference_create', 'references_logos'); }
        flash_set('success', 'Kaydedildi.');
    } elseif ($action === 'delete') {
        reference_delete((int) $_POST['id']);
        flash_set('success', 'Silindi.');
    }
    redirect('/admin/references.php');
}

$rows    = references_all();
$editing = !empty($_GET['edit']) ? reference_get((int) $_GET['edit']) : null;

$pageTitle  = 'Referanslar';
$activePage = 'references';
require __DIR__ . '/partials/header.php';
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
  <div class="card lg:col-span-2">
    <div class="flex items-center justify-between mb-4">
      <h2 class="font-bold text-lg">Referans Logoları</h2>
      <a class="btn btn-primary" href="?"><span class="material-symbols-outlined text-base">add</span> Yeni</a>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
      <?php foreach ($rows as $r): ?>
      <div class="rounded-xl border border-slate-200 p-4 flex flex-col items-center text-center">
        <div class="h-16 flex items-center justify-center w-full bg-slate-50 rounded-lg mb-2">
          <?php if (!empty($r['logo_path'])): ?>
            <img src="<?= e($r['logo_path']) ?>" alt="<?= e($r['name']) ?>" class="max-h-12 max-w-[120px] object-contain">
          <?php else: ?>
            <span class="text-xs text-slate-500"><?= e($r['name']) ?></span>
          <?php endif; ?>
        </div>
        <div class="text-sm font-semibold"><?= e($r['name']) ?></div>
        <div class="text-xs text-slate-500">Sıra: <?= (int) $r['sort_order'] ?> · <?= $r['active'] ? 'Aktif' : 'Pasif' ?></div>
        <div class="flex gap-2 mt-3">
          <a class="btn btn-ghost btn-icon" href="?edit=<?= (int) $r['id'] ?>"><span class="material-symbols-outlined text-base">edit</span></a>
          <form method="post" class="inline" data-confirm="Silinecek?">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
            <button class="btn btn-danger btn-icon" type="submit"><span class="material-symbols-outlined text-base">delete</span></button>
          </form>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="card">
    <h3 class="font-bold mb-3"><?= !empty($editing['id']) ? 'Referansı Düzenle' : 'Yeni Referans' ?></h3>
    <form method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="save">
      <?php if (!empty($editing['id'])): ?><input type="hidden" name="id" value="<?= (int) $editing['id'] ?>"><?php endif; ?>
      <div class="field"><label>Marka Adı</label><input type="text" name="name" value="<?= e($editing['name'] ?? '') ?>" required></div>
      <div class="field"><label>Logo URL</label><input type="text" name="logo_path" value="<?= e($editing['logo_path'] ?? '') ?>"></div>
      <div class="field"><label>Logo Yükle</label><input type="file" name="logo_upload" accept="image/*"></div>
      <div class="field"><label>Web Sitesi (opsiyonel)</label><input type="text" name="url" value="<?= e($editing['url'] ?? '') ?>"></div>
      <div class="grid grid-cols-2 gap-3">
        <div class="field"><label>Sıra</label><input type="number" name="sort_order" value="<?= (int) ($editing['sort_order'] ?? 0) ?>"></div>
        <div class="field"><label>Durum</label><label class="inline-flex items-center gap-2 mt-2"><input type="checkbox" name="active" <?= !isset($editing['active']) || $editing['active'] ? 'checked' : '' ?>> Aktif</label></div>
      </div>
      <button class="btn btn-primary" type="submit"><span class="material-symbols-outlined text-base">save</span> Kaydet</button>
      <?php if (!empty($editing['id'])): ?><a class="btn btn-ghost" href="?">İptal</a><?php endif; ?>
    </form>
  </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
