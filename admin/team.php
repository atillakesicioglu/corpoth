<?php
require_once __DIR__ . '/bootstrap.php';
admin_guard();

$editId  = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$editing = $editId ? team_get($editId) : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $action = $_POST['_action'] ?? 'save';

    if ($action === 'delete' && !empty($_POST['id'])) {
        $id = (int)$_POST['id'];
        team_delete($id);
        audit_log('team_delete', 'team_members', $id);
        flash_set('success', 'Üye silindi.');
        redirect('/admin/team.php');
    }

    $slug = trim($_POST['slug'] ?? '');
    if ($slug === '') {
        $slug = blog_slugify($_POST['full_name'] ?? '');
    }

    $data = [
        'slug'       => $slug,
        'full_name'  => trim($_POST['full_name'] ?? ''),
        'title'      => trim($_POST['title'] ?? '') ?: null,
        'bio'        => trim($_POST['bio'] ?? '') ?: null,
        'bio_long'   => $_POST['bio_long'] ?? null,
        'photo'      => trim($_POST['photo'] ?? '') ?: null,
        'email'      => trim($_POST['email'] ?? '') ?: null,
        'linkedin'   => trim($_POST['linkedin'] ?? '') ?: null,
        'phone'      => trim($_POST['phone'] ?? '') ?: null,
        'sort_order' => (int)($_POST['sort_order'] ?? 0),
        'is_active'  => !empty($_POST['is_active']),
    ];

    if (!empty($_FILES['photo_upload']['name'])) {
        try {
            $u = media_handle_upload($_FILES['photo_upload'], (int) $_SESSION['admin_user_id']);
            $data['photo'] = $u['path'];
        } catch (Throwable $e) {
            flash_set('error', $e->getMessage());
        }
    }

    if ($editId) {
        team_update($editId, $data);
        audit_log('team_update', 'team_members', $editId);
        flash_set('success', 'Üye güncellendi.');
        redirect('/admin/team.php?id=' . $editId);
    } else {
        $newId = team_create($data);
        audit_log('team_create', 'team_members', $newId);
        flash_set('success', 'Üye eklendi.');
        redirect('/admin/team.php?id=' . $newId);
    }
}

$pageTitle  = $editing ? 'Üye Düzenle: ' . $editing['full_name'] : 'Ekip Üyeleri';
$activePage = 'team';
require __DIR__ . '/partials/header.php';

$all = team_all();
?>

<?php if ($editing || isset($_GET['new'])): ?>
<form method="post" enctype="multipart/form-data" class="card max-w-3xl space-y-4">
  <?= csrf_field() ?>
  <?php if ($editing): ?><input type="hidden" name="id" value="<?= (int)$editing['id'] ?>"><?php endif; ?>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="field">
      <label>Ad Soyad <span class="text-red-500">*</span></label>
      <input type="text" name="full_name" required value="<?= e($editing['full_name'] ?? '') ?>">
    </div>
    <div class="field">
      <label>Slug (URL)</label>
      <input type="text" name="slug" value="<?= e($editing['slug'] ?? '') ?>" placeholder="cemal-kaya">
      <span class="help">Boş bırakırsanız ad-soyaddan otomatik üretilir.</span>
    </div>
  </div>

  <div class="field">
    <label>Ünvan / Rol</label>
    <input type="text" name="title" value="<?= e($editing['title'] ?? '') ?>" placeholder="Kurucu, Baş Terapist">
  </div>

  <div class="field">
    <label>Kısa Biyografi (kart üzerinde)</label>
    <textarea name="bio" rows="3" maxlength="500"><?= e($editing['bio'] ?? '') ?></textarea>
  </div>

  <div class="field">
    <label>Detaylı Biyografi (HTML, üye sayfasında)</label>
    <textarea name="bio_long" rows="10" class="font-mono text-sm"><?= e($editing['bio_long'] ?? '') ?></textarea>
    <span class="help">HTML kullanabilirsiniz: &lt;p&gt;, &lt;strong&gt;, &lt;a&gt;, &lt;ul&gt;</span>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="field">
      <label>Fotoğraf Yolu</label>
      <input type="text" name="photo" value="<?= e($editing['photo'] ?? '') ?>" placeholder="/uploads/...">
    </div>
    <div class="field">
      <label>Fotoğraf Yükle</label>
      <input type="file" name="photo_upload" accept="image/*">
    </div>
  </div>

  <?php if (!empty($editing['photo'])): ?>
  <div>
    <p class="text-xs text-slate-500 mb-1">Mevcut fotoğraf:</p>
    <img src="<?= e($editing['photo']) ?>" alt="" class="max-h-40 rounded-xl border border-slate-200">
  </div>
  <?php endif; ?>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="field">
      <label>E-posta</label>
      <input type="email" name="email" value="<?= e($editing['email'] ?? '') ?>">
    </div>
    <div class="field">
      <label>LinkedIn URL</label>
      <input type="url" name="linkedin" value="<?= e($editing['linkedin'] ?? '') ?>" placeholder="https://www.linkedin.com/in/...">
    </div>
    <div class="field">
      <label>Telefon</label>
      <input type="text" name="phone" value="<?= e($editing['phone'] ?? '') ?>">
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="field">
      <label>Sıralama</label>
      <input type="number" name="sort_order" value="<?= (int)($editing['sort_order'] ?? 10) ?>">
    </div>
    <label class="inline-flex items-center gap-2 text-sm pt-7">
      <input type="checkbox" name="is_active" <?= (!$editing || !empty($editing['is_active'])) ? 'checked' : '' ?>>
      Yayında
    </label>
  </div>

  <div class="flex gap-2 pt-2">
    <button class="btn btn-primary" type="submit"><span class="material-symbols-outlined text-base">save</span> Kaydet</button>
    <a class="btn btn-ghost" href="/admin/team.php">İptal</a>
  </div>
</form>

<?php if ($editing): ?>
<form method="post" class="card max-w-3xl mt-6 border-red-200 bg-red-50">
  <?= csrf_field() ?>
  <input type="hidden" name="_action" value="delete">
  <input type="hidden" name="id" value="<?= (int)$editing['id'] ?>">
  <h3 class="font-bold text-red-800 mb-2">Tehlikeli bölge</h3>
  <button class="btn bg-red-600 text-white hover:bg-red-700" onclick="return confirm('Üyeyi silmek istediginizden emin misiniz?')" type="submit">
    <span class="material-symbols-outlined text-base">delete</span> Üyeyi Sil
  </button>
</form>
<?php endif; ?>

<?php else: ?>

<div class="flex justify-between items-center">
  <p class="text-sm text-slate-600">Ekip üyelerini yönetin. Anasayfa ve <code>/ekip</code> sayfasında listelenir.</p>
  <a href="/admin/team.php?new=1" class="btn btn-primary"><span class="material-symbols-outlined text-base">add</span> Yeni Üye</a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
  <?php foreach ($all as $m): ?>
  <a href="/admin/team.php?id=<?= (int)$m['id'] ?>" class="card hover:border-blue-300 transition-colors flex gap-4 items-center !p-4">
    <?php if (!empty($m['photo'])): ?>
      <img src="<?= e($m['photo']) ?>" alt="" class="w-16 h-16 rounded-full object-cover border border-slate-200"/>
    <?php else: ?>
      <div class="w-16 h-16 rounded-full bg-slate-100 inline-flex items-center justify-center text-slate-400">
        <span class="material-symbols-outlined">person</span>
      </div>
    <?php endif; ?>
    <div class="min-w-0 flex-1">
      <p class="font-bold truncate"><?= e($m['full_name']) ?></p>
      <p class="text-xs text-slate-500 truncate"><?= e($m['title'] ?? '—') ?></p>
      <div class="mt-1 flex gap-2">
        <span class="text-[11px] text-slate-400">Sıra: <?= (int)$m['sort_order'] ?></span>
        <?php if ($m['is_active']): ?>
          <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-700 text-[10px] font-semibold">AKTİF</span>
        <?php else: ?>
          <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-slate-200 text-slate-600 text-[10px] font-semibold">PASİF</span>
        <?php endif; ?>
      </div>
    </div>
  </a>
  <?php endforeach; ?>
  <?php if (!$all): ?>
    <div class="text-slate-500 italic">Henüz üye yok. Yeni üye ekleyin.</div>
  <?php endif; ?>
</div>

<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php'; ?>
