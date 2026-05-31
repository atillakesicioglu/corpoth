<?php
require_once __DIR__ . '/bootstrap.php';
admin_guard();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $action = $_POST['action'] ?? '';
    if ($action === 'save') {
        $id = (int) ($_POST['id'] ?? 0);
        $d = [
            'question'   => $_POST['question'] ?? '',
            'answer'     => $_POST['answer'] ?? '',
            'sort_order' => $_POST['sort_order'] ?? 0,
            'active'     => !empty($_POST['active']),
        ];
        if ($id) { faq_update($id, $d); audit_log('faq_update', 'faq', $id); }
        else     { faq_create($d);      audit_log('faq_create', 'faq'); }
        flash_set('success', 'Kaydedildi.');
    } elseif ($action === 'delete') {
        faq_delete((int) $_POST['id']);
        flash_set('success', 'Silindi.');
    }
    redirect('/admin/faq.php');
}

$rows    = faq_all();
$editing = !empty($_GET['edit']) ? faq_get((int) $_GET['edit']) : null;

$pageTitle  = 'SSS';
$activePage = 'faq';
require __DIR__ . '/partials/header.php';
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
  <div class="card lg:col-span-2">
    <div class="flex items-center justify-between mb-4">
      <h2 class="font-bold text-lg">Sıkça Sorulan Sorular</h2>
      <a class="btn btn-primary" href="?"><span class="material-symbols-outlined text-base">add</span> Yeni</a>
    </div>
    <div class="overflow-x-auto">
      <table class="admin-table">
        <thead><tr><th>Soru</th><th>Sıra</th><th>Durum</th><th></th></tr></thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
          <tr>
            <td>
              <div class="font-semibold"><?= e($r['question']) ?></div>
              <div class="text-xs text-slate-500 mt-1"><?= e(str_excerpt($r['answer'], 140)) ?></div>
            </td>
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
    <h3 class="font-bold mb-3"><?= !empty($editing['id']) ? 'SSS Düzenle' : 'Yeni SSS' ?></h3>
    <form method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="save">
      <?php if (!empty($editing['id'])): ?><input type="hidden" name="id" value="<?= (int) $editing['id'] ?>"><?php endif; ?>
      <div class="field"><label>Soru</label><input type="text" name="question" value="<?= e($editing['question'] ?? '') ?>" required></div>
      <div class="field"><label>Cevap</label><textarea name="answer" rows="6" required><?= e($editing['answer'] ?? '') ?></textarea></div>
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
