<?php
require_once __DIR__ . '/bootstrap.php';
admin_guard();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $action = $_POST['action'] ?? '';
    if ($action === 'save') {
        $id = (int) ($_POST['id'] ?? 0);
        $d = [
            'name'       => $_POST['name'] ?? '',
            'role'       => $_POST['role'] ?? null,
            'company'    => $_POST['company'] ?? null,
            'content'    => $_POST['content'] ?? '',
            'photo_path' => $_POST['photo_path'] ?? null,
            'rating'     => $_POST['rating'] ?? 5,
            'sort_order' => $_POST['sort_order'] ?? 0,
            'active'     => !empty($_POST['active']),
        ];
        if (!empty($_FILES['photo_upload']['name'])) {
            try {
                $u = media_handle_upload($_FILES['photo_upload'], (int) $_SESSION['admin_user_id']);
                $d['photo_path'] = $u['path'];
            } catch (Throwable $e) {
                flash_set('error', $e->getMessage());
            }
        }
        if ($id) { testimonial_update($id, $d); audit_log('testimonial_update', 'testimonials', $id); }
        else     { testimonial_create($d);      audit_log('testimonial_create', 'testimonials'); }
        flash_set('success', 'Kaydedildi.');
    } elseif ($action === 'delete') {
        testimonial_delete((int) $_POST['id']);
        flash_set('success', 'Silindi.');
    }
    redirect('/admin/testimonials.php');
}

$rows    = testimonials_all();
$editing = !empty($_GET['edit']) ? testimonial_get((int) $_GET['edit']) : null;

$pageTitle  = 'Yorumlar';
$activePage = 'testimonials';
require __DIR__ . '/partials/header.php';
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
  <div class="card lg:col-span-2">
    <div class="flex items-center justify-between mb-4">
      <h2 class="font-bold text-lg">Müşteri Yorumları</h2>
      <a class="btn btn-primary" href="?"><span class="material-symbols-outlined text-base">add</span> Yeni</a>
    </div>
    <?php foreach ($rows as $r): ?>
    <div class="border border-slate-200 rounded-xl p-4 mb-3 flex gap-4">
      <?php if (!empty($r['photo_path'])): ?>
        <img src="<?= e($r['photo_path']) ?>" alt="<?= e($r['name']) ?>" class="w-12 h-12 rounded-full object-cover">
      <?php else: ?>
        <div class="w-12 h-12 rounded-full bg-slate-200 flex items-center justify-center font-bold text-slate-600"><?= e(mb_substr($r['name'], 0, 1)) ?></div>
      <?php endif; ?>
      <div class="flex-1">
        <div class="flex items-center justify-between gap-2">
          <div>
            <div class="font-semibold"><?= e($r['name']) ?></div>
            <div class="text-xs text-slate-500"><?= e(trim(($r['role'] ?? '') . ($r['role'] && $r['company'] ? ' · ' : '') . ($r['company'] ?? ''))) ?></div>
          </div>
          <div class="text-amber-500 flex items-center"><?php for ($i = 0; $i < (int) $r['rating']; $i++): ?>★<?php endfor; ?></div>
        </div>
        <p class="text-sm text-slate-700 mt-2"><?= e(str_excerpt($r['content'], 200)) ?></p>
        <div class="flex gap-2 mt-3 items-center">
          <span class="text-xs text-slate-500">Sıra: <?= (int) $r['sort_order'] ?> · <?= $r['active'] ? 'Aktif' : 'Pasif' ?></span>
          <a class="btn btn-ghost btn-icon ml-auto" href="?edit=<?= (int) $r['id'] ?>"><span class="material-symbols-outlined text-base">edit</span></a>
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

  <div class="card">
    <h3 class="font-bold mb-3"><?= !empty($editing['id']) ? 'Yorumu Düzenle' : 'Yeni Yorum' ?></h3>
    <form method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="save">
      <?php if (!empty($editing['id'])): ?><input type="hidden" name="id" value="<?= (int) $editing['id'] ?>"><?php endif; ?>
      <div class="field"><label>Ad Soyad</label><input type="text" name="name" value="<?= e($editing['name'] ?? '') ?>" required></div>
      <div class="field"><label>Pozisyon / Ünvan</label><input type="text" name="role" value="<?= e($editing['role'] ?? '') ?>"></div>
      <div class="field"><label>Şirket</label><input type="text" name="company" value="<?= e($editing['company'] ?? '') ?>"></div>
      <div class="field"><label>Yorum Metni</label><textarea name="content" rows="5" required><?= e($editing['content'] ?? '') ?></textarea></div>
      <div class="field"><label>Foto URL</label><input type="text" name="photo_path" value="<?= e($editing['photo_path'] ?? '') ?>"></div>
      <div class="field"><label>Foto Yükle</label><input type="file" name="photo_upload" accept="image/*"></div>
      <div class="grid grid-cols-3 gap-2">
        <div class="field"><label>Yıldız (1-5)</label><input type="number" name="rating" min="1" max="5" value="<?= (int) ($editing['rating'] ?? 5) ?>"></div>
        <div class="field"><label>Sıra</label><input type="number" name="sort_order" value="<?= (int) ($editing['sort_order'] ?? 0) ?>"></div>
        <div class="field"><label>Durum</label><label class="inline-flex items-center gap-2 mt-2"><input type="checkbox" name="active" <?= !isset($editing['active']) || $editing['active'] ? 'checked' : '' ?>> Aktif</label></div>
      </div>
      <button class="btn btn-primary" type="submit"><span class="material-symbols-outlined text-base">save</span> Kaydet</button>
      <?php if (!empty($editing['id'])): ?><a class="btn btn-ghost" href="?">İptal</a><?php endif; ?>
    </form>
  </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
