<?php
require_once __DIR__ . '/bootstrap.php';
admin_guard();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $action = $_POST['action'] ?? '';
    if ($action === 'save') {
        $id = (int) ($_POST['id'] ?? 0);
        $d = [
            'title'        => $_POST['title'] ?? '',
            'description'  => $_POST['description'] ?? null,
            'image_path'   => $_POST['image_path'] ?? null,
            'image_alt'    => $_POST['image_alt'] ?? null,
            'is_text_card' => !empty($_POST['is_text_card']),
            'icon'         => $_POST['icon'] ?? null,
            'sort_order'   => $_POST['sort_order'] ?? 0,
            'active'       => !empty($_POST['active']),
        ];
        if (!empty($_FILES['image_upload']['name'])) {
            try {
                $u = media_handle_upload($_FILES['image_upload'], (int) $_SESSION['admin_user_id']);
                $d['image_path'] = $u['path'];
            } catch (Throwable $e) {
                flash_set('error', $e->getMessage());
            }
        }
        if ($id) { scenario_update($id, $d); audit_log('scenario_update', 'scenarios', $id); }
        else     { scenario_create($d);      audit_log('scenario_create', 'scenarios'); }
        flash_set('success', 'Kaydedildi.');
    } elseif ($action === 'delete') {
        scenario_delete((int) $_POST['id']);
        flash_set('success', 'Silindi.');
    }
    redirect('/admin/scenarios.php');
}

$rows    = scenarios_all();
$editing = !empty($_GET['edit']) ? scenario_get((int) $_GET['edit']) : null;

$pageTitle  = 'Senaryolar';
$activePage = 'scenarios';
require __DIR__ . '/partials/header.php';
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
  <div class="card lg:col-span-2">
    <div class="flex items-center justify-between mb-4">
      <h2 class="font-bold text-lg">Kullanım Senaryoları</h2>
      <a class="btn btn-primary" href="?"><span class="material-symbols-outlined text-base">add</span> Yeni</a>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <?php foreach ($rows as $r): ?>
      <div class="rounded-xl border border-slate-200 overflow-hidden">
        <?php if ($r['is_text_card']): ?>
        <div class="h-32 primary-gradient flex items-center justify-center text-white">
          <span class="material-symbols-outlined text-3xl"><?= e($r['icon'] ?: 'spa') ?></span>
        </div>
        <?php else: ?>
        <div class="h-32 bg-slate-100" style="background-size:cover;background-position:center;background-image:url('<?= e($r['image_path']) ?>')"></div>
        <?php endif; ?>
        <div class="p-3">
          <div class="font-semibold text-sm"><?= e($r['title']) ?></div>
          <div class="text-xs text-slate-500 mt-1">Sıra: <?= (int) $r['sort_order'] ?> · <?= $r['active'] ? 'Aktif' : 'Pasif' ?></div>
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
      </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="card">
    <h3 class="font-bold mb-3"><?= !empty($editing['id']) ? 'Senaryo Düzenle' : 'Yeni Senaryo' ?></h3>
    <form method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="save">
      <?php if (!empty($editing['id'])): ?><input type="hidden" name="id" value="<?= (int) $editing['id'] ?>"><?php endif; ?>
      <div class="field"><label>Başlık</label><input type="text" name="title" value="<?= e($editing['title'] ?? '') ?>" required></div>
      <div class="field"><label><input type="checkbox" name="is_text_card" <?= !empty($editing['is_text_card']) ? 'checked' : '' ?>> Bu kart metin/ikon kartı (görsel yerine)</label></div>
      <div class="field"><label>Açıklama (sadece metin kart)</label><textarea name="description" rows="3"><?= e($editing['description'] ?? '') ?></textarea></div>
      <div class="field"><label>İkon (sadece metin kart)</label><input type="text" name="icon" value="<?= e($editing['icon'] ?? '') ?>" placeholder="spa"></div>
      <div class="field"><label>Görsel Yolu</label><input type="text" name="image_path" value="<?= e($editing['image_path'] ?? '') ?>"></div>
      <div class="field"><label>Görsel Alt</label><input type="text" name="image_alt" value="<?= e($editing['image_alt'] ?? '') ?>"></div>
      <div class="field"><label>Görsel Yükle</label><input type="file" name="image_upload" accept="image/*"></div>
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
