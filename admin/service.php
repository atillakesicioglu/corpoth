<?php
require_once __DIR__ . '/bootstrap.php';
admin_guard();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $action = $_POST['action'] ?? '';

    if ($action === 'block') {
        $data = [
            'title'       => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'image_path'  => trim($_POST['image_path'] ?? ''),
            'image_alt'   => trim($_POST['image_alt'] ?? ''),
        ];
        if (!empty($_FILES['image_upload']['name'])) {
            try {
                $u = media_handle_upload($_FILES['image_upload'], (int) $_SESSION['admin_user_id']);
                $data['image_path'] = $u['path'];
            } catch (Throwable $e) {
                flash_set('error', $e->getMessage());
            }
        }
        service_block_update($data);
        audit_log('service_block_update', 'service_block', 1);
        flash_set('success', 'Hizmet bloğu güncellendi.');
    } elseif ($action === 'save') {
        $id = (int) ($_POST['id'] ?? 0);
        $d = [
            'icon'        => $_POST['icon'] ?? 'check_circle',
            'title'       => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'sort_order'  => $_POST['sort_order'] ?? 0,
            'active'      => !empty($_POST['active']),
        ];
        if ($id) { service_feature_update($id, $d); audit_log('service_feature_update', 'service_features', $id); }
        else     { service_feature_create($d);      audit_log('service_feature_create', 'service_features'); }
        flash_set('success', 'Özellik kaydedildi.');
    } elseif ($action === 'delete') {
        service_feature_delete((int) $_POST['id']);
        audit_log('service_feature_delete', 'service_features', (int) $_POST['id']);
        flash_set('success', 'Silindi.');
    }
    redirect('/admin/service.php' . (!empty($_POST['return_edit']) ? '?edit=' . (int) $_POST['return_edit'] : ''));
}

$svc     = service_block_get();
$rows    = service_features_all();
$editing = !empty($_GET['edit']) ? service_feature_get((int) $_GET['edit']) : null;

$pageTitle  = 'Hizmet & Özellikler';
$activePage = 'service';
require __DIR__ . '/partials/header.php';
?>

<form method="post" enctype="multipart/form-data" class="card max-w-3xl space-y-4">
  <?= csrf_field() ?>
  <input type="hidden" name="action" value="block">
  <h2 class="font-bold text-lg">"Kurumsal Omurga Terapisi Nedir?" Bloğu</h2>
  <div class="field"><label>Başlık</label><input type="text" name="title" value="<?= e($svc['title'] ?? '') ?>"></div>
  <div class="field"><label>Açıklama</label><textarea name="description" rows="4"><?= e($svc['description'] ?? '') ?></textarea></div>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="field"><label>Görsel Yolu</label><input type="text" name="image_path" value="<?= e($svc['image_path'] ?? '') ?>"></div>
    <div class="field"><label>Görsel Alt</label><input type="text" name="image_alt" value="<?= e($svc['image_alt'] ?? '') ?>"></div>
  </div>
  <div class="field"><label>Görsel Yükle</label><input type="file" name="image_upload" accept="image/*"></div>
  <button class="btn btn-primary" type="submit"><span class="material-symbols-outlined text-base">save</span> Bloğu Kaydet</button>
</form>

<?php
$titleSingle = 'Özellik';
$titlePlural = 'Hizmet Özellikleri (4 kart)';
$defaultIcon = 'check_circle';
require __DIR__ . '/partials/crud_simple.php';
?>

<?php require __DIR__ . '/partials/footer.php'; ?>
