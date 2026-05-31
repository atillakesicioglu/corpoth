<?php
require_once __DIR__ . '/bootstrap.php';
admin_guard();

$editId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$editing = $editId ? page_get_by_id($editId) : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $action = $_POST['_action'] ?? 'save';

    if ($action === 'delete' && !empty($_POST['id'])) {
        $id = (int)$_POST['id'];
        page_delete($id);
        audit_log('page_delete', 'pages', $id);
        flash_set('success', 'Sayfa silindi.');
        redirect('/admin/pages.php');
    }

    $data = [
        'slug'             => trim($_POST['slug'] ?? ''),
        'title'            => trim($_POST['title'] ?? ''),
        'hero_eyebrow'     => trim($_POST['hero_eyebrow'] ?? '') ?: null,
        'hero_subtitle'    => trim($_POST['hero_subtitle'] ?? '') ?: null,
        'hero_image'       => trim($_POST['hero_image'] ?? '') ?: null,
        'content_html'     => $_POST['content_html'] ?? '',
        'meta_title'       => trim($_POST['meta_title'] ?? '') ?: null,
        'meta_description' => trim($_POST['meta_description'] ?? '') ?: null,
        'og_image'         => trim($_POST['og_image'] ?? '') ?: null,
        'is_active'        => !empty($_POST['is_active']),
    ];

    if (!empty($_FILES['hero_image_upload']['name'])) {
        try {
            $u = media_handle_upload($_FILES['hero_image_upload'], (int) $_SESSION['admin_user_id']);
            $data['hero_image'] = $u['path'];
        } catch (Throwable $e) {
            flash_set('error', $e->getMessage());
        }
    }

    if ($editId) {
        page_update($editId, $data);
        audit_log('page_update', 'pages', $editId);
        flash_set('success', 'Sayfa güncellendi.');
        redirect('/admin/pages.php?id=' . $editId);
    } else {
        $newId = page_create($data);
        audit_log('page_create', 'pages', $newId);
        flash_set('success', 'Sayfa eklendi.');
        redirect('/admin/pages.php?id=' . $newId);
    }
}

$pageTitle  = $editing ? 'Sayfa Düzenle: ' . $editing['title'] : 'Sayfa İçerikleri';
$activePage = 'pages';
require __DIR__ . '/partials/header.php';

$all = page_all();
?>

<?php if ($editing || isset($_GET['new'])): ?>
<form method="post" enctype="multipart/form-data" class="card max-w-4xl space-y-4">
  <?= csrf_field() ?>
  <?php if ($editing): ?><input type="hidden" name="id" value="<?= (int)$editing['id'] ?>"><?php endif; ?>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="field">
      <label>Başlık <span class="text-red-500">*</span></label>
      <input type="text" name="title" required value="<?= e($editing['title'] ?? '') ?>">
    </div>
    <div class="field">
      <label>Slug (URL) <span class="text-red-500">*</span></label>
      <input type="text" name="slug" required pattern="[a-z0-9\-]+" value="<?= e($editing['slug'] ?? '') ?>" placeholder="hakkimizda">
      <span class="help">Sadece kucuk harf, rakam ve tire. URL'nin son parcasi.</span>
    </div>
  </div>

  <div class="field">
    <label>Hero Üst Etiketi (eyebrow)</label>
    <input type="text" name="hero_eyebrow" value="<?= e($editing['hero_eyebrow'] ?? '') ?>" placeholder="CORPOTH">
  </div>

  <div class="field">
    <label>Hero Alt Metni</label>
    <textarea name="hero_subtitle" rows="2"><?= e($editing['hero_subtitle'] ?? '') ?></textarea>
  </div>

  <div class="field">
    <label>İçerik (HTML)</label>
    <textarea name="content_html" rows="18" class="font-mono text-sm"><?= e($editing['content_html'] ?? '') ?></textarea>
    <span class="help">HTML kullanabilirsiniz. Etiketler: &lt;h2&gt;, &lt;h3&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;ol&gt;, &lt;li&gt;, &lt;strong&gt;, &lt;a&gt;, &lt;blockquote&gt;, &lt;img&gt;</span>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="field">
      <label>Hero Görsel Yolu</label>
      <input type="text" name="hero_image" value="<?= e($editing['hero_image'] ?? '') ?>" placeholder="/uploads/...">
    </div>
    <div class="field">
      <label>Hero Görsel Yükle</label>
      <input type="file" name="hero_image_upload" accept="image/*">
    </div>
  </div>

  <details class="card">
    <summary class="font-semibold cursor-pointer">SEO Meta</summary>
    <div class="space-y-4 mt-4">
      <div class="field">
        <label>Meta Title</label>
        <input type="text" name="meta_title" value="<?= e($editing['meta_title'] ?? '') ?>" maxlength="190">
      </div>
      <div class="field">
        <label>Meta Description</label>
        <textarea name="meta_description" rows="2" maxlength="500"><?= e($editing['meta_description'] ?? '') ?></textarea>
      </div>
      <div class="field">
        <label>OG Görsel</label>
        <input type="text" name="og_image" value="<?= e($editing['og_image'] ?? '') ?>" placeholder="/uploads/...">
      </div>
    </div>
  </details>

  <label class="inline-flex items-center gap-2 text-sm">
    <input type="checkbox" name="is_active" <?= (!$editing || !empty($editing['is_active'])) ? 'checked' : '' ?>>
    Yayında
  </label>

  <div class="flex gap-2 pt-2">
    <button class="btn btn-primary" type="submit"><span class="material-symbols-outlined text-base">save</span> Kaydet</button>
    <a class="btn btn-ghost" href="/admin/pages.php">İptal</a>
    <?php if ($editing): ?>
    <a class="btn btn-ghost" href="/<?= e($editing['slug']) ?>.php" target="_blank"><span class="material-symbols-outlined text-base">visibility</span> Önizle</a>
    <?php endif; ?>
  </div>
</form>

<?php if ($editing): ?>
<form method="post" class="card max-w-4xl mt-6 border-red-200 bg-red-50">
  <?= csrf_field() ?>
  <input type="hidden" name="_action" value="delete">
  <input type="hidden" name="id" value="<?= (int)$editing['id'] ?>">
  <h3 class="font-bold text-red-800 mb-2">Tehlikeli bölge</h3>
  <p class="text-sm text-red-700 mb-3">Bu sayfayı silmek geri alınamaz.</p>
  <button class="btn bg-red-600 text-white hover:bg-red-700" onclick="return confirm('Sayfayi silmek istediginizden emin misiniz?')" type="submit">
    <span class="material-symbols-outlined text-base">delete</span> Sayfayı Sil
  </button>
</form>
<?php endif; ?>

<?php else: ?>

<div class="flex justify-between items-center">
  <p class="text-sm text-slate-600">Statik sayfaların metinlerini buradan düzenleyin (Hakkımızda, Hizmet detay vs.).</p>
  <a href="/admin/pages.php?new=1" class="btn btn-primary"><span class="material-symbols-outlined text-base">add</span> Yeni Sayfa</a>
</div>

<div class="card overflow-hidden p-0">
  <table class="w-full">
    <thead class="bg-slate-50 border-b border-slate-200">
      <tr>
        <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-slate-600">Başlık</th>
        <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-slate-600">Slug</th>
        <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-slate-600">Durum</th>
        <th class="text-right px-4 py-3 text-xs font-bold uppercase tracking-wider text-slate-600">İşlem</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-slate-100">
      <?php if (!$all): ?>
        <tr><td colspan="4" class="text-center text-slate-500 py-8">Henüz sayfa yok.</td></tr>
      <?php else: foreach ($all as $p): ?>
      <tr>
        <td class="px-4 py-3 font-semibold"><?= e($p['title']) ?></td>
        <td class="px-4 py-3 text-sm text-slate-500"><code><?= e($p['slug']) ?></code></td>
        <td class="px-4 py-3">
          <?php if ($p['is_active']): ?>
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">Aktif</span>
          <?php else: ?>
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-200 text-slate-600 text-xs font-semibold">Pasif</span>
          <?php endif; ?>
        </td>
        <td class="px-4 py-3 text-right">
          <a class="btn btn-ghost btn-sm" href="/admin/pages.php?id=<?= (int)$p['id'] ?>"><span class="material-symbols-outlined text-base">edit</span> Düzenle</a>
        </td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>

<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php'; ?>
