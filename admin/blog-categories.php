<?php
require_once __DIR__ . '/bootstrap.php';
admin_guard();

$editId  = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$editing = $editId ? blog_cat_get($editId) : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $action = $_POST['_action'] ?? 'save';

    if ($action === 'delete' && !empty($_POST['id'])) {
        $id = (int)$_POST['id'];
        blog_cat_delete($id);
        audit_log('blog_cat_delete', 'blog_categories', $id);
        flash_set('success', 'Kategori silindi.');
        redirect('/admin/blog-categories.php');
    }

    $slug = trim($_POST['slug'] ?? '');
    if ($slug === '') $slug = blog_slugify($_POST['name'] ?? '');

    $data = [
        'slug'        => $slug,
        'name'        => trim($_POST['name'] ?? ''),
        'description' => trim($_POST['description'] ?? '') ?: null,
        'sort_order'  => (int)($_POST['sort_order'] ?? 0),
        'is_active'   => !empty($_POST['is_active']),
    ];

    if ($editId) {
        blog_cat_update($editId, $data);
        audit_log('blog_cat_update', 'blog_categories', $editId);
        flash_set('success', 'Kategori güncellendi.');
        redirect('/admin/blog-categories.php');
    } else {
        $newId = blog_cat_create($data);
        audit_log('blog_cat_create', 'blog_categories', $newId);
        flash_set('success', 'Kategori eklendi.');
        redirect('/admin/blog-categories.php');
    }
}

$pageTitle  = 'Blog Kategorileri';
$activePage = 'blog-categories';
require __DIR__ . '/partials/header.php';

$all = blog_cat_all();
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
  <div class="lg:col-span-2 card overflow-hidden p-0">
    <table class="w-full">
      <thead class="bg-slate-50 border-b border-slate-200">
        <tr>
          <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-slate-600">Ad</th>
          <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-slate-600">Slug</th>
          <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-slate-600">Sıra</th>
          <th class="text-right px-4 py-3 text-xs font-bold uppercase tracking-wider text-slate-600">İşlem</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        <?php if (!$all): ?>
          <tr><td colspan="4" class="text-center text-slate-500 py-8">Henüz kategori yok.</td></tr>
        <?php else: foreach ($all as $c): ?>
          <tr>
            <td class="px-4 py-3 font-semibold"><?= e($c['name']) ?></td>
            <td class="px-4 py-3 text-sm text-slate-500"><code><?= e($c['slug']) ?></code></td>
            <td class="px-4 py-3"><?= (int)$c['sort_order'] ?></td>
            <td class="px-4 py-3 text-right">
              <a class="btn btn-ghost btn-sm" href="?id=<?= (int)$c['id'] ?>">Düzenle</a>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>

  <div class="lg:col-span-1">
    <form method="post" class="card space-y-3">
      <?= csrf_field() ?>
      <?php if ($editing): ?><input type="hidden" name="id" value="<?= (int)$editing['id'] ?>"><?php endif; ?>
      <h3 class="font-bold"><?= $editing ? 'Kategori Düzenle' : 'Yeni Kategori' ?></h3>
      <div class="field">
        <label>Ad <span class="text-red-500">*</span></label>
        <input type="text" name="name" required value="<?= e($editing['name'] ?? '') ?>">
      </div>
      <div class="field">
        <label>Slug</label>
        <input type="text" name="slug" value="<?= e($editing['slug'] ?? '') ?>" placeholder="otomatik">
      </div>
      <div class="field">
        <label>Açıklama</label>
        <textarea name="description" rows="2"><?= e($editing['description'] ?? '') ?></textarea>
      </div>
      <div class="field">
        <label>Sıralama</label>
        <input type="number" name="sort_order" value="<?= (int)($editing['sort_order'] ?? 0) ?>">
      </div>
      <label class="inline-flex items-center gap-2 text-sm">
        <input type="checkbox" name="is_active" <?= (!$editing || !empty($editing['is_active'])) ? 'checked' : '' ?>>
        Aktif
      </label>
      <div class="flex gap-2 pt-2">
        <button class="btn btn-primary" type="submit"><span class="material-symbols-outlined text-base">save</span> Kaydet</button>
        <?php if ($editing): ?>
        <a class="btn btn-ghost" href="/admin/blog-categories.php">İptal</a>
        <?php endif; ?>
      </div>
    </form>

    <?php if ($editing): ?>
    <form method="post" class="card mt-4 border-red-200 bg-red-50">
      <?= csrf_field() ?>
      <input type="hidden" name="_action" value="delete">
      <input type="hidden" name="id" value="<?= (int)$editing['id'] ?>">
      <button class="btn bg-red-600 text-white hover:bg-red-700 w-full" onclick="return confirm('Kategoriyi silmek istediginizden emin misiniz? Bu kategorideki yazilar kategorisiz kalir.')" type="submit">
        <span class="material-symbols-outlined text-base">delete</span> Sil
      </button>
    </form>
    <?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
