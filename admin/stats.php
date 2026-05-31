<?php
require_once __DIR__ . '/bootstrap.php';
admin_guard();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $action = $_POST['action'] ?? '';
    if ($action === 'save') {
        $id = (int) ($_POST['id'] ?? 0);
        $d = [
            'icon'         => $_POST['icon'] ?? 'trending_up',
            'value'        => $_POST['value'] ?? '',
            'label'        => $_POST['label'] ?? '',
            'count_to'     => $_POST['count_to'] ?? null,
            'count_prefix' => $_POST['count_prefix'] ?? null,
            'count_suffix' => $_POST['count_suffix'] ?? null,
            'sort_order'   => $_POST['sort_order'] ?? 0,
            'active'       => !empty($_POST['active']),
        ];
        if ($id) { stat_update($id, $d); audit_log('stat_update', 'stats', $id); }
        else     { stat_create($d);      audit_log('stat_create', 'stats'); }
        flash_set('success', 'Kaydedildi.');
    } elseif ($action === 'delete') {
        stat_delete((int) $_POST['id']);
        flash_set('success', 'Silindi.');
    }
    redirect('/admin/stats.php');
}

$rows    = stats_all();
$editing = !empty($_GET['edit']) ? stat_get((int) $_GET['edit']) : null;

$pageTitle  = 'İstatistikler';
$activePage = 'stats';
require __DIR__ . '/partials/header.php';
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
  <div class="card lg:col-span-2">
    <div class="flex items-center justify-between mb-4">
      <h2 class="font-bold text-lg">İstatistikler</h2>
      <a class="btn btn-primary" href="?"><span class="material-symbols-outlined text-base">add</span> Yeni</a>
    </div>
    <div class="overflow-x-auto">
      <table class="admin-table">
        <thead><tr><th>İkon</th><th>Değer</th><th>Etiket</th><th>Animasyon Hedefi</th><th>Sıra</th><th>Durum</th><th></th></tr></thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
          <tr>
            <td><span class="material-symbols-outlined"><?= e($r['icon']) ?></span></td>
            <td class="font-bold text-primary"><?= e($r['value']) ?></td>
            <td><?= e($r['label']) ?></td>
            <td class="text-sm text-slate-500"><?= $r['count_to'] !== null ? e(($r['count_prefix'] ?? '') . $r['count_to'] . ($r['count_suffix'] ?? '')) : '—' ?></td>
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
    <h3 class="font-bold mb-3"><?= !empty($editing['id']) ? 'İstatistik #' . (int) $editing['id'] . ' Düzenle' : 'Yeni İstatistik' ?></h3>
    <form method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="save">
      <?php if (!empty($editing['id'])): ?><input type="hidden" name="id" value="<?= (int) $editing['id'] ?>"><?php endif; ?>
      <div class="field"><label>İkon</label><input type="text" name="icon" value="<?= e($editing['icon'] ?? 'trending_up') ?>"></div>
      <div class="field"><label>Görüntülenen Değer</label><input type="text" name="value" value="<?= e($editing['value'] ?? '') ?>" placeholder="%25"></div>
      <div class="field"><label>Etiket</label><input type="text" name="label" value="<?= e($editing['label'] ?? '') ?>"></div>
      <div class="grid grid-cols-3 gap-2">
        <div class="field"><label>Önek</label><input type="text" name="count_prefix" value="<?= e($editing['count_prefix'] ?? '') ?>"></div>
        <div class="field"><label>Sayı Hedefi</label><input type="number" name="count_to" value="<?= e($editing['count_to'] ?? '') ?>"></div>
        <div class="field"><label>Sonek</label><input type="text" name="count_suffix" value="<?= e($editing['count_suffix'] ?? '') ?>"></div>
      </div>
      <span class="help">Animasyonlu sayaç için "Sayı Hedefi"ni girin (ör. 25). Boş bırakılırsa düz metin gösterilir.</span>
      <div class="grid grid-cols-2 gap-3 mt-3">
        <div class="field"><label>Sıra</label><input type="number" name="sort_order" value="<?= (int) ($editing['sort_order'] ?? 0) ?>"></div>
        <div class="field"><label>Durum</label><label class="inline-flex items-center gap-2 mt-2"><input type="checkbox" name="active" <?= !isset($editing['active']) || $editing['active'] ? 'checked' : '' ?>> Aktif</label></div>
      </div>
      <button class="btn btn-primary" type="submit"><span class="material-symbols-outlined text-base">save</span> Kaydet</button>
      <?php if (!empty($editing['id'])): ?><a class="btn btn-ghost" href="?">İptal</a><?php endif; ?>
    </form>
  </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
