<?php
require_once __DIR__ . '/bootstrap.php';
admin_guard();

if (!empty($_SESSION['must_change_password'])) {
    flash_set('error', 'Güvenliğiniz için lütfen şifrenizi değiştirin.');
    redirect('/admin/account.php');
}

$stats   = leads_stats_summary();
$recent  = leads_list([], 5, 0);

$pageTitle  = 'Panel';
$activePage = 'dashboard';
require __DIR__ . '/partials/header.php';
?>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
  <div class="kpi">
    <div class="v"><?= (int) $stats['total'] ?></div>
    <div class="l">Toplam Lead</div>
  </div>
  <div class="kpi">
    <div class="v text-blue-700"><?= (int) $stats['new'] ?></div>
    <div class="l">Yeni</div>
  </div>
  <div class="kpi">
    <div class="v text-amber-700"><?= (int) $stats['contacted'] ?></div>
    <div class="l">İletişimde</div>
  </div>
  <div class="kpi">
    <div class="v text-emerald-700"><?= (int) $stats['closed'] ?></div>
    <div class="l">Kapanan</div>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
  <div class="kpi">
    <div class="v"><?= (int) $stats['today'] ?></div>
    <div class="l">Bugün</div>
  </div>
  <div class="kpi">
    <div class="v"><?= (int) $stats['last_7days'] ?></div>
    <div class="l">Son 7 Gün</div>
  </div>
  <div class="kpi">
    <div class="v"><?= round(($stats['contacted'] + $stats['closed']) / max(1, $stats['total']) * 100) ?>%</div>
    <div class="l">Geri dönüş oranı</div>
  </div>
</div>

<div class="card">
  <div class="flex items-center justify-between mb-4">
    <h2 class="text-lg font-bold">Son Lead'ler</h2>
    <a href="/admin/leads.php" class="text-sm font-semibold text-slate-700 hover:text-slate-900">Tümünü gör →</a>
  </div>
  <?php if (!$recent): ?>
    <p class="text-sm text-slate-500">Henüz lead yok.</p>
  <?php else: ?>
    <div class="overflow-x-auto">
      <table class="admin-table">
        <thead>
          <tr><th>Tarih</th><th>Ad</th><th>Şirket</th><th>E-posta</th><th>Durum</th><th></th></tr>
        </thead>
        <tbody>
          <?php foreach ($recent as $l): ?>
          <tr>
            <td class="whitespace-nowrap"><?= e(fmt_date($l['created_at'])) ?></td>
            <td class="font-semibold"><?= e($l['name']) ?></td>
            <td><?= e($l['company']) ?></td>
            <td><a href="mailto:<?= e($l['email']) ?>" class="text-slate-700 hover:underline"><?= e($l['email']) ?></a></td>
            <td><span class="badge badge-<?= e($l['status']) ?>"><?= e(ucfirst($l['status'])) ?></span></td>
            <td><a class="btn btn-ghost btn-icon" href="/admin/lead-edit.php?id=<?= (int) $l['id'] ?>" aria-label="Detay"><span class="material-symbols-outlined text-base">visibility</span></a></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
