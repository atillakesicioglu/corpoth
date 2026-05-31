<?php
if (!isset($pageTitle)) $pageTitle = 'Panel';
$activePage = $activePage ?? '';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title><?= e($pageTitle) ?> | Corpoth Admin</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="icon" type="image/png" href="/assets/images/corpoth-logo-icon.png"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<link rel="stylesheet" href="/admin/assets/admin.css"/>
</head>
<body class="bg-slate-100 min-h-screen font-[Inter] text-slate-800">
<div class="flex min-h-screen">
  <?php require __DIR__ . '/sidebar.php'; ?>
  <div class="flex-1 flex flex-col min-w-0">
    <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between sticky top-0 z-30">
      <div class="flex items-center gap-3">
        <button id="sidebar-toggle" class="md:hidden p-2 rounded-lg hover:bg-slate-100" aria-label="Menü">
          <span class="material-symbols-outlined">menu</span>
        </button>
        <h1 class="text-lg font-bold tracking-tight"><?= e($pageTitle) ?></h1>
      </div>
      <div class="flex items-center gap-3">
        <a href="/" target="_blank" class="hidden sm:inline-flex items-center gap-2 text-sm text-slate-600 hover:text-slate-900">
          <span class="material-symbols-outlined text-base">open_in_new</span>
          Siteyi görüntüle
        </a>
        <span class="hidden sm:inline-block text-sm text-slate-500">
          <?= e($_SESSION['admin_username'] ?? '') ?>
        </span>
        <a href="/admin/logout.php" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-slate-900 hover:bg-slate-700 text-white text-sm font-semibold">
          <span class="material-symbols-outlined text-base">logout</span>
          Çıkış
        </a>
      </div>
    </header>

    <main class="flex-1 p-6 md:p-8 space-y-6">
      <?php foreach (flash_all() as $type => $msg):
        $cls = 'bg-blue-50 border-blue-200 text-blue-800';
        if ($type === 'success') $cls = 'bg-emerald-50 border-emerald-200 text-emerald-800';
        if ($type === 'error')   $cls = 'bg-red-50 border-red-200 text-red-800';
      ?>
      <div class="rounded-xl border px-4 py-3 text-sm <?= $cls ?>"><?= e($msg) ?></div>
      <?php endforeach; ?>
