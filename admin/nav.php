<?php
require_once __DIR__ . '/bootstrap.php';
admin_guard();

$editId  = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$editing = $editId ? nav_get($editId) : null;
$newType = $_GET['type'] ?? null;       // 'parent' veya 'child'
$newParentId = isset($_GET['parent']) ? (int) $_GET['parent'] : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $action = $_POST['_action'] ?? 'save';

    if ($action === 'delete' && !empty($_POST['id'])) {
        $id = (int) $_POST['id'];
        nav_delete($id);
        audit_log('nav_delete', 'nav_items', $id);
        flash_set('success', 'Menü öğesi silindi.');
        redirect('/admin/nav.php');
    }

    if ($action === 'toggle' && !empty($_POST['id'])) {
        $id = (int) $_POST['id'];
        nav_toggle_active($id);
        audit_log('nav_toggle', 'nav_items', $id);
        flash_set('success', 'Durum güncellendi.');
        redirect('/admin/nav.php');
    }

    if ($action === 'reorder' && !empty($_POST['orders']) && is_array($_POST['orders'])) {
        foreach ($_POST['orders'] as $id => $order) {
            nav_set_sort((int) $id, (int) $order);
        }
        audit_log('nav_reorder', 'nav_items', null, ['count' => count($_POST['orders'])]);
        flash_set('success', 'Sıralama güncellendi.');
        redirect('/admin/nav.php');
    }

    $data = [
        'parent_id'           => !empty($_POST['parent_id']) ? (int) $_POST['parent_id'] : null,
        'label'               => trim($_POST['label'] ?? ''),
        'href'                => trim($_POST['href'] ?? '#'),
        'icon'                => trim($_POST['icon'] ?? ''),
        'description'         => trim($_POST['description'] ?? ''),
        'key_slug'            => trim($_POST['key_slug'] ?? ''),
        'is_dropdown_parent'  => !empty($_POST['is_dropdown_parent']),
        'sort_order'          => (int) ($_POST['sort_order'] ?? 0),
        'is_active'           => !empty($_POST['is_active']),
    ];

    if ($data['label'] === '') {
        flash_set('error', 'Etiket alanı zorunlu.');
    } elseif ($editId) {
        nav_update($editId, $data);
        audit_log('nav_update', 'nav_items', $editId);
        flash_set('success', 'Menü öğesi güncellendi.');
        redirect('/admin/nav.php');
    } else {
        $id = nav_create($data);
        audit_log('nav_create', 'nav_items', $id);
        flash_set('success', 'Menü öğesi eklendi.');
        redirect('/admin/nav.php');
    }
}

$tree     = nav_all_with_children();
$parents  = nav_top_level_options();

$pageTitle  = $editing
    ? 'Menü Düzenle: ' . $editing['label']
    : ($newType ? 'Yeni Menü Öğesi' : 'Header Menüsü');
$activePage = 'nav';
require __DIR__ . '/partials/header.php';
?>

<?php if ($editing || $newType): ?>
<?php
    $isEdit         = (bool) $editing;
    $isChild        = $isEdit ? !empty($editing['parent_id']) : ($newType === 'child');
    $defaultParent  = $isEdit ? (int)($editing['parent_id'] ?? 0) : $newParentId;
?>
<form method="post" class="card max-w-3xl space-y-4">
  <?= csrf_field() ?>
  <?php if ($isEdit): ?><input type="hidden" name="id" value="<?= (int)$editing['id'] ?>"><?php endif; ?>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="field md:col-span-2">
      <label>Tür</label>
      <div class="flex gap-3 text-sm">
        <label class="inline-flex items-center gap-2 cursor-pointer">
          <input type="radio" name="_level" value="parent" <?= !$isChild ? 'checked' : '' ?> onclick="document.getElementById('parent_select').classList.add('opacity-40','pointer-events-none')">
          Üst seviye (top-level)
        </label>
        <label class="inline-flex items-center gap-2 cursor-pointer">
          <input type="radio" name="_level" value="child" <?= $isChild ? 'checked' : '' ?> onclick="document.getElementById('parent_select').classList.remove('opacity-40','pointer-events-none')">
          Alt menü öğesi (child)
        </label>
      </div>
    </div>

    <div class="field md:col-span-2 transition-opacity <?= !$isChild ? 'opacity-40 pointer-events-none' : '' ?>" id="parent_select">
      <label>Bağlı olduğu üst öğe</label>
      <select name="parent_id">
        <option value="">— (üst seviye) —</option>
        <?php foreach ($parents as $p): if ($isEdit && (int)$p['id'] === (int)$editing['id']) continue; ?>
        <option value="<?= (int)$p['id'] ?>" <?= (int)$p['id'] === (int)$defaultParent ? 'selected' : '' ?>><?= e($p['label']) ?></option>
        <?php endforeach; ?>
      </select>
      <span class="help">Bir üst öğe seçilirse bu öğe onun dropdown'unda görünür.</span>
    </div>

    <div class="field">
      <label>Etiket <span class="text-red-500">*</span></label>
      <input type="text" name="label" required value="<?= e($editing['label'] ?? '') ?>" placeholder="Hakkımızda">
    </div>
    <div class="field">
      <label>Bağlantı (URL)</label>
      <input type="text" name="href" value="<?= e($editing['href'] ?? '#') ?>" placeholder="/hakkimizda.php">
      <span class="help">Sadece dropdown başlığıysa # bırak ve "tıklanmaz" işaretini aç.</span>
    </div>

    <div class="field">
      <label>Material İkon</label>
      <input type="text" name="icon" value="<?= e($editing['icon'] ?? '') ?>" placeholder="corporate_fare">
      <span class="help">Sadece alt menülerde gösterilir. <a href="https://fonts.google.com/icons" target="_blank" class="text-primary underline">İkon adı bul</a></span>
    </div>
    <div class="field">
      <label>Anahtar (key_slug)</label>
      <input type="text" name="key_slug" value="<?= e($editing['key_slug'] ?? '') ?>" placeholder="about">
      <span class="help">Aktif sayfa eşleştirmesi için. Genelde dokunmaya gerek yok.</span>
    </div>

    <div class="field md:col-span-2">
      <label>Açıklama (alt yazı)</label>
      <input type="text" name="description" value="<?= e($editing['description'] ?? '') ?>" placeholder="Misyonumuz, vizyonumuz, değerlerimiz">
      <span class="help">Sadece dropdown alt menülerinde gösterilir.</span>
    </div>

    <div class="field">
      <label>Sıralama</label>
      <input type="number" name="sort_order" value="<?= (int)($editing['sort_order'] ?? 0) ?>" min="0" step="10">
      <span class="help">Küçük olan üstte. 10, 20, 30... şeklinde girmek pratik.</span>
    </div>

    <div class="field">
      <label class="invisible">.</label>
      <div class="flex flex-col gap-2 pt-2">
        <label class="inline-flex items-center gap-2 text-sm">
          <input type="checkbox" name="is_dropdown_parent" <?= !empty($editing['is_dropdown_parent']) ? 'checked' : '' ?>>
          <span>Tıklanmaz (sadece dropdown başlığı)</span>
        </label>
        <label class="inline-flex items-center gap-2 text-sm">
          <input type="checkbox" name="is_active" <?= (!$isEdit || !empty($editing['is_active'])) ? 'checked' : '' ?>>
          <span>Aktif</span>
        </label>
      </div>
    </div>
  </div>

  <div class="flex gap-2 pt-2 border-t border-slate-200">
    <button class="btn btn-primary" type="submit">
      <span class="material-symbols-outlined text-base">save</span> Kaydet
    </button>
    <a class="btn btn-ghost" href="/admin/nav.php">İptal</a>
  </div>
</form>

<?php if ($isEdit): ?>
<form method="post" class="card max-w-3xl mt-6 border-red-200 bg-red-50">
  <?= csrf_field() ?>
  <input type="hidden" name="_action" value="delete">
  <input type="hidden" name="id" value="<?= (int)$editing['id'] ?>">
  <h3 class="font-bold text-red-800 mb-2">Tehlikeli bölge</h3>
  <p class="text-sm text-red-700 mb-3">
    Bu öğeyi silmek geri alınamaz. Üst seviye bir öğeyi silersen tüm alt menü öğeleri de silinir.
  </p>
  <button class="btn bg-red-600 text-white hover:bg-red-700" onclick="return confirm('Menu ogesini silmek istediginizden emin misiniz?')" type="submit">
    <span class="material-symbols-outlined text-base">delete</span> Sil
  </button>
</form>
<?php endif; ?>

<?php else: ?>

<div class="flex flex-wrap gap-2 items-center justify-between">
  <p class="text-sm text-slate-600">Header menüsündeki öğeleri (Anasayfa, Hizmet, Kurumsal &gt; Hakkımızda, vb.) buradan yönet.</p>
  <div class="flex gap-2">
    <a href="/admin/nav.php?type=parent" class="btn btn-primary">
      <span class="material-symbols-outlined text-base">add</span> Yeni Üst Öğe
    </a>
  </div>
</div>

<?php if (empty($tree)): ?>
<div class="card max-w-2xl text-center py-10">
  <span class="material-symbols-outlined text-4xl text-slate-300 mb-2">menu</span>
  <p class="text-slate-600 mb-4">Henüz menü öğesi yok. <strong>nav_items</strong> tablosu boş veya migration uygulanmamış olabilir.</p>
  <a href="/admin/migrations.php" class="btn btn-primary">
    <span class="material-symbols-outlined text-base">storage</span> Migration sayfasına git
  </a>
</div>
<?php else: ?>

<form method="post" class="space-y-4">
  <?= csrf_field() ?>
  <input type="hidden" name="_action" value="reorder">

  <?php foreach ($tree as $top): ?>
  <div class="card p-0 overflow-hidden">
    <div class="px-5 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between gap-3">
      <div class="flex items-center gap-3 min-w-0">
        <input type="number" name="orders[<?= (int)$top['id'] ?>]" value="<?= (int)$top['sort_order'] ?>" class="w-16 text-sm" title="Sıralama" step="10">
        <span class="font-semibold text-slate-900 truncate">
          <?= e($top['label']) ?>
        </span>
        <?php if ((int)$top['is_dropdown_parent'] === 1): ?>
          <span class="ml-1 px-2 py-0.5 rounded-full bg-violet-100 text-violet-700 text-[11px] font-semibold">Dropdown</span>
        <?php endif; ?>
        <?php if ((int)$top['is_active'] !== 1): ?>
          <span class="ml-1 px-2 py-0.5 rounded-full bg-slate-200 text-slate-600 text-[11px] font-semibold">Pasif</span>
        <?php endif; ?>
        <code class="text-xs text-slate-500 truncate hidden md:inline">→ <?= e($top['href']) ?></code>
      </div>
      <div class="flex items-center gap-1 shrink-0">
        <a href="/admin/nav.php?type=child&parent=<?= (int)$top['id'] ?>" class="btn btn-ghost btn-sm" title="Bu menüye alt öğe ekle">
          <span class="material-symbols-outlined text-base">add</span>
          <span class="hidden md:inline">Alt öğe</span>
        </a>
        <a href="/admin/nav.php?id=<?= (int)$top['id'] ?>" class="btn btn-ghost btn-sm">
          <span class="material-symbols-outlined text-base">edit</span>
          <span class="hidden md:inline">Düzenle</span>
        </a>
      </div>
    </div>

    <?php if (!empty($top['children'])): ?>
    <ul class="divide-y divide-slate-100">
      <?php foreach ($top['children'] as $ch): ?>
      <li class="px-5 py-2.5 flex items-center gap-3">
        <input type="number" name="orders[<?= (int)$ch['id'] ?>]" value="<?= (int)$ch['sort_order'] ?>" class="w-16 text-sm" title="Sıralama" step="10">

        <?php if (!empty($ch['icon'])): ?>
          <span class="material-symbols-outlined text-slate-500"><?= e($ch['icon']) ?></span>
        <?php endif; ?>

        <div class="flex-1 min-w-0">
          <div class="font-medium text-sm">
            <?= e($ch['label']) ?>
            <?php if ((int)$ch['is_active'] !== 1): ?>
              <span class="ml-1 px-1.5 py-0.5 rounded-full bg-slate-200 text-slate-600 text-[10px] font-semibold">Pasif</span>
            <?php endif; ?>
          </div>
          <div class="text-xs text-slate-500 flex items-center gap-2 truncate">
            <code><?= e($ch['href']) ?></code>
            <?php if (!empty($ch['description'])): ?>
              <span class="text-slate-300">·</span>
              <span class="truncate"><?= e($ch['description']) ?></span>
            <?php endif; ?>
          </div>
        </div>

        <a href="/admin/nav.php?id=<?= (int)$ch['id'] ?>" class="btn btn-ghost btn-sm">
          <span class="material-symbols-outlined text-base">edit</span>
        </a>
      </li>
      <?php endforeach; ?>
    </ul>
    <?php else: ?>
    <div class="px-5 py-3 text-xs text-slate-500 italic">
      Henüz alt öğe yok.
      <a href="/admin/nav.php?type=child&parent=<?= (int)$top['id'] ?>" class="text-primary underline ml-1">Ekle</a>
    </div>
    <?php endif; ?>
  </div>
  <?php endforeach; ?>

  <div class="flex justify-end pt-2">
    <button type="submit" class="btn btn-primary">
      <span class="material-symbols-outlined text-base">save</span> Sıralamayı Kaydet
    </button>
  </div>
</form>

<?php endif; ?>

<div class="card max-w-3xl bg-slate-50 border-slate-200 mt-6">
  <h3 class="text-sm font-bold mb-2 flex items-center gap-2">
    <span class="material-symbols-outlined text-base">tips_and_updates</span>
    İpuçları
  </h3>
  <ul class="list-disc pl-5 text-sm text-slate-700 space-y-1">
    <li>Bir öğe <strong>dropdown başlığı</strong> ise URL'i <code>#</code> bırak ve "Tıklanmaz" kutusunu işaretle.</li>
    <li>Alt menü öğelerine <strong>Material ikon</strong> ekleyebilirsin (örn. <code>corporate_fare</code>, <code>groups</code>, <code>article</code>).</li>
    <li>Sıralama numarası <strong>küçük olan üstte</strong> görünür (10, 20, 30, ...).</li>
    <li>Pasif yapılan öğeler hem header'da hem mobil menüde gizlenir; silmeden geçici kapatabilirsin.</li>
    <li>Üst seviye bir öğeyi silersen, ona bağlı tüm alt öğeler de silinir (ON DELETE CASCADE).</li>
  </ul>
</div>

<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php'; ?>
