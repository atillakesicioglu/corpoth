<?php
require_once __DIR__ . '/bootstrap.php';
admin_guard();

$editId  = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$editing = $editId ? blog_get($editId) : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $action = $_POST['_action'] ?? 'save';

    if ($action === 'delete' && !empty($_POST['id'])) {
        $id = (int)$_POST['id'];
        blog_delete($id);
        audit_log('blog_post_delete', 'blog_posts', $id);
        flash_set('success', 'Yazı silindi.');
        redirect('/admin/blog-posts.php');
    }

    $slug = trim($_POST['slug'] ?? '');
    if ($slug === '') $slug = blog_slugify($_POST['title'] ?? '');

    $publishedAt = trim($_POST['published_at'] ?? '');
    $status = ($_POST['status'] ?? 'draft') === 'published' ? 'published' : 'draft';
    if ($status === 'published' && $publishedAt === '') {
        $publishedAt = date('Y-m-d H:i:s');
    }

    $data = [
        'slug'             => $slug,
        'title'            => trim($_POST['title'] ?? ''),
        'excerpt'          => trim($_POST['excerpt'] ?? '') ?: null,
        'content_html'     => $_POST['content_html'] ?? '',
        'cover_image'      => trim($_POST['cover_image'] ?? '') ?: null,
        'category_id'      => !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null,
        'author_name'      => trim($_POST['author_name'] ?? '') ?: null,
        'tags'             => trim($_POST['tags'] ?? '') ?: null,
        'status'           => $status,
        'published_at'     => $publishedAt ?: null,
        'meta_title'       => trim($_POST['meta_title'] ?? '') ?: null,
        'meta_description' => trim($_POST['meta_description'] ?? '') ?: null,
        'og_image'         => trim($_POST['og_image'] ?? '') ?: null,
    ];

    if (!empty($_FILES['cover_image_upload']['name'])) {
        try {
            $u = media_handle_upload($_FILES['cover_image_upload'], (int) $_SESSION['admin_user_id']);
            $data['cover_image'] = $u['path'];
        } catch (Throwable $e) {
            flash_set('error', $e->getMessage());
        }
    }

    if ($editId) {
        blog_update($editId, $data);
        audit_log('blog_post_update', 'blog_posts', $editId);
        flash_set('success', 'Yazı güncellendi.');
        redirect('/admin/blog-posts.php?id=' . $editId);
    } else {
        $newId = blog_create($data);
        audit_log('blog_post_create', 'blog_posts', $newId);
        flash_set('success', 'Yazı eklendi.');
        redirect('/admin/blog-posts.php?id=' . $newId);
    }
}

$pageTitle  = $editing ? 'Yazı Düzenle: ' . $editing['title'] : 'Blog Yazıları';
$activePage = 'blog-posts';
require __DIR__ . '/partials/header.php';

$cats = blog_cat_all();
$all  = $editing ? null : blog_admin_list();
?>

<?php if ($editing || isset($_GET['new'])): ?>
<form method="post" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-7xl">
  <?= csrf_field() ?>
  <?php if ($editing): ?><input type="hidden" name="id" value="<?= (int)$editing['id'] ?>"><?php endif; ?>

  <!-- Sol: Ana icerik -->
  <div class="lg:col-span-2 space-y-4">
    <div class="card space-y-4">
      <div class="field">
        <label>Başlık <span class="text-red-500">*</span></label>
        <input type="text" name="title" required value="<?= e($editing['title'] ?? '') ?>">
      </div>
      <div class="field">
        <label>Slug (URL)</label>
        <input type="text" name="slug" value="<?= e($editing['slug'] ?? '') ?>" placeholder="otomatik">
        <span class="help">URL: <code>/blog/{slug}</code>. Boş bırakırsanız başlıktan üretilir.</span>
      </div>
      <div class="field">
        <label>Özet</label>
        <textarea name="excerpt" rows="3" maxlength="500"><?= e($editing['excerpt'] ?? '') ?></textarea>
        <span class="help">Liste sayfasında ve detay üst kısmında görünür. 140-160 karakter idealdir.</span>
      </div>
      <div class="field">
        <label>İçerik (HTML) <span class="text-red-500">*</span></label>
        <textarea name="content_html" rows="22" required class="font-mono text-sm"><?= e($editing['content_html'] ?? '') ?></textarea>
        <span class="help">HTML kullanın: &lt;h2&gt;, &lt;h3&gt;, &lt;p&gt;, &lt;strong&gt;, &lt;em&gt;, &lt;a&gt;, &lt;ul&gt;, &lt;ol&gt;, &lt;li&gt;, &lt;blockquote&gt;, &lt;img src="..."&gt;</span>
      </div>
    </div>

    <details class="card">
      <summary class="font-semibold cursor-pointer">SEO Meta</summary>
      <div class="space-y-3 mt-4">
        <div class="field">
          <label>Meta Title</label>
          <input type="text" name="meta_title" maxlength="190" value="<?= e($editing['meta_title'] ?? '') ?>">
        </div>
        <div class="field">
          <label>Meta Description</label>
          <textarea name="meta_description" rows="2" maxlength="500"><?= e($editing['meta_description'] ?? '') ?></textarea>
        </div>
        <div class="field">
          <label>OG Image (paylaşım görseli)</label>
          <input type="text" name="og_image" value="<?= e($editing['og_image'] ?? '') ?>" placeholder="/uploads/...">
        </div>
      </div>
    </details>
  </div>

  <!-- Sag: Yan panel -->
  <div class="lg:col-span-1 space-y-4">
    <div class="card space-y-3">
      <h3 class="font-bold">Yayın</h3>
      <div class="field">
        <label>Durum</label>
        <select name="status">
          <?php $cur = $editing['status'] ?? 'draft'; ?>
          <option value="draft"     <?= $cur === 'draft' ? 'selected' : '' ?>>Taslak</option>
          <option value="published" <?= $cur === 'published' ? 'selected' : '' ?>>Yayınlanmış</option>
        </select>
      </div>
      <div class="field">
        <label>Yayın tarihi</label>
        <input type="datetime-local" name="published_at" value="<?= !empty($editing['published_at']) ? e(date('Y-m-d\TH:i', strtotime($editing['published_at']))) : '' ?>">
        <span class="help">Boş bırakılırsa yayın anında otomatik atanır.</span>
      </div>
      <div class="flex gap-2 pt-2">
        <button class="btn btn-primary flex-1" type="submit"><span class="material-symbols-outlined text-base">save</span> Kaydet</button>
        <?php if ($editing): ?>
        <a class="btn btn-ghost" href="/blog/<?= e($editing['slug']) ?>" target="_blank"><span class="material-symbols-outlined text-base">visibility</span></a>
        <?php endif; ?>
      </div>
      <a class="btn btn-ghost btn-sm w-full" href="/admin/blog-posts.php">Listeye dön</a>
    </div>

    <div class="card space-y-3">
      <h3 class="font-bold">Kategori & Yazar</h3>
      <div class="field">
        <label>Kategori</label>
        <select name="category_id">
          <option value="">— Yok —</option>
          <?php foreach ($cats as $c): ?>
          <option value="<?= (int)$c['id'] ?>" <?= (!empty($editing['category_id']) && (int)$editing['category_id'] === (int)$c['id']) ? 'selected' : '' ?>><?= e($c['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="field">
        <label>Yazar Adı</label>
        <input type="text" name="author_name" value="<?= e($editing['author_name'] ?? '') ?>" placeholder="Cemal Kaya">
      </div>
      <div class="field">
        <label>Etiketler</label>
        <input type="text" name="tags" value="<?= e($editing['tags'] ?? '') ?>" placeholder="omurga, ofis, ergonomi">
        <span class="help">Virgülle ayırın.</span>
      </div>
    </div>

    <div class="card space-y-3">
      <h3 class="font-bold">Kapak Görseli</h3>
      <?php if (!empty($editing['cover_image'])): ?>
      <img src="<?= e($editing['cover_image']) ?>" alt="" class="rounded-lg border border-slate-200 w-full">
      <?php endif; ?>
      <div class="field">
        <label>Görsel Yolu</label>
        <input type="text" name="cover_image" value="<?= e($editing['cover_image'] ?? '') ?>" placeholder="/uploads/...">
      </div>
      <div class="field">
        <label>Görsel Yükle</label>
        <input type="file" name="cover_image_upload" accept="image/*">
      </div>
    </div>

    <?php if ($editing): ?>
    <div class="card text-xs text-slate-500 space-y-1">
      <p>Görüntülenme: <strong><?= (int)$editing['view_count'] ?></strong></p>
      <p>Oluşturma: <?= e(fmt_date($editing['created_at'])) ?></p>
      <p>Güncellenme: <?= e(fmt_date($editing['updated_at'])) ?></p>
    </div>
    <?php endif; ?>
  </div>
</form>

<?php if ($editing): ?>
<form method="post" class="card max-w-7xl mt-6 border-red-200 bg-red-50">
  <?= csrf_field() ?>
  <input type="hidden" name="_action" value="delete">
  <input type="hidden" name="id" value="<?= (int)$editing['id'] ?>">
  <h3 class="font-bold text-red-800 mb-2">Tehlikeli bölge</h3>
  <button class="btn bg-red-600 text-white hover:bg-red-700" onclick="return confirm('Yaziyi silmek istediginizden emin misiniz?')" type="submit">
    <span class="material-symbols-outlined text-base">delete</span> Yazıyı Sil
  </button>
</form>
<?php endif; ?>

<?php else: ?>

<div class="flex justify-between items-center">
  <p class="text-sm text-slate-600">Blog yazılarını yönetin. Taslak veya yayınlanmış olarak kaydedebilirsiniz.</p>
  <a href="/admin/blog-posts.php?new=1" class="btn btn-primary"><span class="material-symbols-outlined text-base">add</span> Yeni Yazı</a>
</div>

<div class="card overflow-hidden p-0">
  <table class="w-full">
    <thead class="bg-slate-50 border-b border-slate-200">
      <tr>
        <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-slate-600">Başlık</th>
        <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-slate-600">Kategori</th>
        <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-slate-600">Durum</th>
        <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-slate-600">Yayın</th>
        <th class="text-left px-4 py-3 text-xs font-bold uppercase tracking-wider text-slate-600">Görüntülenme</th>
        <th class="text-right px-4 py-3 text-xs font-bold uppercase tracking-wider text-slate-600">İşlem</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-slate-100">
      <?php if (!$all): ?>
        <tr><td colspan="6" class="text-center text-slate-500 py-8">Henüz yazı yok. Yeni yazı ekleyin.</td></tr>
      <?php else: foreach ($all as $p): ?>
      <tr>
        <td class="px-4 py-3">
          <div class="font-semibold"><?= e($p['title']) ?></div>
          <div class="text-xs text-slate-500"><code>/blog/<?= e($p['slug']) ?></code></div>
        </td>
        <td class="px-4 py-3 text-sm"><?= e($p['category_name'] ?? '—') ?></td>
        <td class="px-4 py-3">
          <?php if ($p['status'] === 'published'): ?>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold">Yayında</span>
          <?php else: ?>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-xs font-semibold">Taslak</span>
          <?php endif; ?>
        </td>
        <td class="px-4 py-3 text-sm text-slate-500"><?= $p['published_at'] ? e(fmt_date($p['published_at'], 'd.m.Y')) : '—' ?></td>
        <td class="px-4 py-3 text-sm"><?= (int)$p['view_count'] ?></td>
        <td class="px-4 py-3 text-right">
          <a class="btn btn-ghost btn-sm" href="/admin/blog-posts.php?id=<?= (int)$p['id'] ?>"><span class="material-symbols-outlined text-base">edit</span></a>
        </td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>

<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php'; ?>
