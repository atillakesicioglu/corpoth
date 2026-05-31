<?php
require_once __DIR__ . '/bootstrap.php';
admin_guard();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $action = $_POST['action'] ?? '';
    if ($action === 'save') {
        $id = (int) ($_POST['id'] ?? 0);
        $d = [
            'step_number' => $_POST['step_number'] ?? 1,
            'title'       => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'sort_order'  => $_POST['sort_order'] ?? 0,
            'active'      => !empty($_POST['active']),
        ];
        if ($id) { process_update($id, $d); audit_log('process_update', 'process_steps', $id); }
        else     { process_create($d);      audit_log('process_create', 'process_steps'); }
        flash_set('success', 'Kaydedildi.');
    } elseif ($action === 'delete') {
        process_delete((int) $_POST['id']);
        flash_set('success', 'Silindi.');
    }
    redirect('/admin/process.php');
}

$rows    = process_all();
$editing = !empty($_GET['edit']) ? process_get((int) $_GET['edit']) : null;

$pageTitle  = 'Süreç Adımları';
$activePage = 'process';
require __DIR__ . '/partials/header.php';
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
  <div class="card lg:col-span-2">
    <div class="flex items-center justify-between mb-4">
      <h2 class="font-bold text-lg">Süreç Adımları</h2>
      <a class="btn btn-primary" href="?"><span class="material-symbols-outlined text-base">add</span> Yeni</a>
    </div>
    <div class="overflow-x-auto">
      <table class="admin-table">
        <thead><tr><th>Adım</th><th>Başlık</th><th>Açıklama</th><th>Sıra</th><th>Durum</th><th></th></tr></thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
          <tr>
            <td class="font-bold text-primary"><?= (int) $r['step_number'] ?></td>
            <td class="font-semibold"><?= e($r['title']) ?></td>
            <td class="text-sm text-slate-600"><?= e(str_excerpt($r['description'], 120)) ?></td>
            <td><?= (int) $r['sort_order'] ?></td>
            <td><?= $r['active'] ? '<span class="badge badge-closed">Aktif</span>' : '<span class="badge badge-spam">Pasif</span>' ?></td>
            <td class="whitespace-nowrap">
              <a class="btn btn-ghost btn-icon" href="?edit=<?= (int) $r['id'] ?>"><span class="material-symbols-outlined text-base">edit</span></a>
              <form method="post" class="inline" data-confirm="Silinecek?">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
                <button class="btn btn-danger btn-icon" type="submit"><span class="material-symbols-outlined text-base">delete</span></button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="card">
    <h3 class="font-bold mb-3"><?= !empty($editing['id']) ? 'Adım Düzenle' : 'Yeni Adım' ?></h3>
    <form method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="save">
      <?php if (!empty($editing['id'])): ?><input type="hidden" name="id" value="<?= (int) $editing['id'] ?>"><?php endif; ?>
      <div class="field"><label>Adım Numarası</label><input type="number" name="step_number" value="<?= (int) ($editing['step_number'] ?? 1) ?>" min="1" max="20" required></div>
      <div class="field"><label>Başlık</label><input type="text" name="title" value="<?= e($editing['title'] ?? '') ?>" required></div>
      <div class="field"><label>Açıklama</label><textarea name="description" rows="3" required><?= e($editing['description'] ?? '') ?></textarea></div>
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
