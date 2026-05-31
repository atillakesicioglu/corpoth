<?php
require_once __DIR__ . '/bootstrap.php';
admin_guard();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $action = $_POST['action'] ?? '';

    if ($action === 'block') {
        $data = [
            'title'      => trim($_POST['title'] ?? ''),
            'image_path' => trim($_POST['image_path'] ?? ''),
            'image_alt'  => trim($_POST['image_alt'] ?? ''),
        ];
        if (!empty($_FILES['image_upload']['name'])) {
            try {
                $u = media_handle_upload($_FILES['image_upload'], (int) $_SESSION['admin_user_id']);
                $data['image_path'] = $u['path'];
            } catch (Throwable $e) {
                flash_set('error', $e->getMessage());
            }
        }
        why_block_update($data);
        flash_set('success', 'Blok güncellendi.');
    } elseif ($action === 'save') {
        $id = (int) ($_POST['id'] ?? 0);
        $d = [
            'icon'        => $_POST['icon'] ?? 'task_alt',
            'title'       => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'sort_order'  => $_POST['sort_order'] ?? 0,
            'active'      => !empty($_POST['active']),
        ];
        if ($id) { value_prop_update($id, $d); }
        else     { value_prop_create($d); }
        flash_set('success', 'Madde kaydedildi.');
    } elseif ($action === 'delete') {
        value_prop_delete((int) $_POST['id']);
        flash_set('success', 'Silindi.');
    }
    redirect('/admin/why.php');
}

$why     = why_block_get();
$rows    = value_props_all();
$editing = !empty($_GET['edit']) ? value_prop_get((int) $_GET['edit']) : null;

$pageTitle  = 'Neden Corpoth?';
$activePage = 'why';
require __DIR__ . '/partials/header.php';
?>

<form method="post" enctype="multipart/form-data" class="card max-w-3xl space-y-4">
  <?= csrf_field() ?>
  <input type="hidden" name="action" value="block">
  <h2 class="font-bold text-lg">"Neden CORPOTH?" Bloğu</h2>
  <div class="field"><label>Başlık</label><input type="text" name="title" value="<?= e($why['title'] ?? '') ?>"></div>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="field"><label>Görsel Yolu</label><input type="text" name="image_path" value="<?= e($why['image_path'] ?? '') ?>"></div>
    <div class="field"><label>Görsel Alt</label><input type="text" name="image_alt" value="<?= e($why['image_alt'] ?? '') ?>"></div>
  </div>
  <div class="field"><label>Görsel Yükle</label><input type="file" name="image_upload" accept="image/*"></div>
  <button class="btn btn-primary" type="submit"><span class="material-symbols-outlined text-base">save</span> Bloğu Kaydet</button>
</form>

<?php
$titleSingle = 'Madde';
$titlePlural = 'Değer Önerileri (Liste)';
$defaultIcon = 'task_alt';
require __DIR__ . '/partials/crud_simple.php';
?>

<?php require __DIR__ . '/partials/footer.php'; ?>
