<?php
require_once __DIR__ . '/bootstrap.php';
admin_guard();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $action = $_POST['action'] ?? '';
    if ($action === 'save') {
        $id = (int) ($_POST['id'] ?? 0);
        $d = [
            'icon'        => $_POST['icon'] ?? 'psychology',
            'title'       => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'sort_order'  => $_POST['sort_order'] ?? 0,
            'active'      => !empty($_POST['active']),
        ];
        if ($id) { benefit_update($id, $d); audit_log('benefit_update', 'benefits', $id); }
        else     { benefit_create($d);      audit_log('benefit_create', 'benefits'); }
        flash_set('success', 'Kaydedildi.');
    } elseif ($action === 'delete') {
        benefit_delete((int) $_POST['id']);
        flash_set('success', 'Silindi.');
    }
    redirect('/admin/benefits.php');
}

$rows    = benefits_all();
$editing = !empty($_GET['edit']) ? benefit_get((int) $_GET['edit']) : null;

$pageTitle    = 'Faydalar';
$activePage   = 'benefits';
$titleSingle  = 'Fayda';
$titlePlural  = 'Faydalar';
$defaultIcon  = 'psychology';

require __DIR__ . '/partials/header.php';
require __DIR__ . '/partials/crud_simple.php';
require __DIR__ . '/partials/footer.php';
