<?php
require_once __DIR__ . '/bootstrap.php';
admin_guard();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $action = $_POST['action'] ?? '';
    if ($action === 'save') {
        $id = (int) ($_POST['id'] ?? 0);
        $d = [
            'icon'        => $_POST['icon'] ?? 'groups',
            'title'       => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'sort_order'  => $_POST['sort_order'] ?? 0,
            'active'      => !empty($_POST['active']),
        ];
        if ($id) {
            audience_update($id, $d);
            audit_log('audience_update', 'audiences', $id);
        } else {
            audience_create($d);
            audit_log('audience_create', 'audiences');
        }
        flash_set('success', 'Kaydedildi.');
    } elseif ($action === 'delete') {
        audience_delete((int) $_POST['id']);
        flash_set('success', 'Silindi.');
    }
    redirect('/admin/audiences.php');
}

$rows    = audiences_all();
$editing = !empty($_GET['edit']) ? audience_get((int) $_GET['edit']) : null;

$pageTitle      = 'Kimler İçin?';
$activePage     = 'audiences';
$titleSingle    = 'Hedef Kitle';
$titlePlural    = 'Hedef Kitleler';
$defaultIcon    = 'groups';

require __DIR__ . '/partials/header.php';
require __DIR__ . '/partials/crud_simple.php';
require __DIR__ . '/partials/footer.php';
