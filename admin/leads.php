<?php
require_once __DIR__ . '/bootstrap.php';
admin_guard();

// Toplu islemler / silme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $action = $_POST['action'] ?? '';
    $id     = (int) ($_POST['id'] ?? 0);

    if ($action === 'set_status' && $id) {
        lead_update_status($id, (string) ($_POST['status'] ?? 'new'));
        audit_log('lead_status', 'leads', $id, $_POST['status'] ?? '');
        flash_set('success', 'Durum güncellendi.');
    } elseif ($action === 'delete' && $id) {
        lead_delete($id);
        audit_log('lead_delete', 'leads', $id);
        flash_set('success', 'Lead silindi.');
    } elseif ($action === 'export_csv') {
        $rows = leads_list($_POST, 5000, 0);
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="corpoth-leads-' . date('Y-m-d') . '.csv"');
        $out = fopen('php://output', 'w');
        fputs($out, "\xEF\xBB\xBF"); // BOM
        fputcsv($out, ['ID','Tarih','Ad','E-posta','Telefon','Sirket','Calisan','Pozisyon','Durum','Mesaj']);
        foreach ($rows as $r) {
            fputcsv($out, [$r['id'], $r['created_at'], $r['name'], $r['email'], $r['phone'], $r['company'], $r['employees_range'], $r['position'], $r['status'], $r['message']]);
        }
        fclose($out);
        exit;
    }
    redirect('/admin/leads.php?' . http_build_query(array_intersect_key($_GET, array_flip(['status', 'search']))));
}

$filters = [
    'status'    => $_GET['status']    ?? '',
    'search'    => $_GET['search']    ?? '',
    'date_from' => $_GET['date_from'] ?? '',
    'date_to'   => $_GET['date_to']   ?? '',
];
$leads = leads_list($filters, 200, 0);
$total = leads_count($filters);

$pageTitle  = 'Lead\'ler';
$activePage = 'leads';
require __DIR__ . '/partials/header.php';
?>

<div class="card">
  <form method="get" class="grid grid-cols-1 md:grid-cols-5 gap-3 items-end">
    <div class="field md:col-span-2">
      <label>Arama</label>
      <input type="text" name="search" value="<?= e($filters['search']) ?>" placeholder="Ad, e-posta, şirket">
    </div>
    <div class="field">
      <label>Durum</label>
      <select name="status">
        <option value="">Hepsi</option>
        <?php foreach (['new' => 'Yeni', 'contacted' => 'İletişimde', 'closed' => 'Kapanan', 'spam' => 'Spam'] as $k => $v): ?>
          <option value="<?= $k ?>" <?= $filters['status'] === $k ? 'selected' : '' ?>><?= $v ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="field">
      <label>Başlangıç</label>
      <input type="date" name="date_from" value="<?= e($filters['date_from']) ?>">
    </div>
    <div class="field">
      <label>Bitiş</label>
      <input type="date" name="date_to" value="<?= e($filters['date_to']) ?>">
    </div>
    <div class="md:col-span-5 flex gap-2 justify-end">
      <button class="btn btn-primary" type="submit"><span class="material-symbols-outlined text-base">search</span> Filtrele</button>
      <a href="/admin/leads.php" class="btn btn-ghost">Sıfırla</a>
      <form method="post" class="inline">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="export_csv">
        <?php foreach ($filters as $k => $v): ?><input type="hidden" name="<?= e($k) ?>" value="<?= e($v) ?>"><?php endforeach; ?>
        <button class="btn btn-ghost" type="submit"><span class="material-symbols-outlined text-base">download</span> CSV</button>
      </form>
    </div>
  </form>
</div>

<div class="card">
  <div class="flex items-center justify-between mb-3">
    <h2 class="font-bold">Sonuçlar (<?= $total ?>)</h2>
  </div>
  <?php if (!$leads): ?>
    <p class="text-sm text-slate-500">Kayıt bulunamadı.</p>
  <?php else: ?>
  <div class="overflow-x-auto">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Tarih</th><th>Ad / Şirket</th><th>İletişim</th><th>Detay</th><th>Durum</th><th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($leads as $l): ?>
        <tr>
          <td class="whitespace-nowrap text-slate-600"><?= e(fmt_date($l['created_at'])) ?></td>
          <td>
            <div class="font-semibold"><?= e($l['name']) ?></div>
            <div class="text-xs text-slate-500"><?= e($l['company']) ?></div>
            <?php if ($l['position']): ?><div class="text-xs text-slate-400"><?= e($l['position']) ?></div><?php endif; ?>
          </td>
          <td>
            <a href="mailto:<?= e($l['email']) ?>" class="text-slate-700 hover:underline block"><?= e($l['email']) ?></a>
            <?php if ($l['phone']): ?><a href="tel:<?= e(tel_link($l['phone'])) ?>" class="text-xs text-slate-500 hover:text-slate-700"><?= e($l['phone']) ?></a><?php endif; ?>
          </td>
          <td>
            <?php if ($l['employees_range']): ?><div class="text-xs text-slate-500">Çalışan: <?= e($l['employees_range']) ?></div><?php endif; ?>
            <?php if ($l['message']): ?><div class="text-xs text-slate-700 mt-1 max-w-sm"><?= e(str_excerpt($l['message'], 140)) ?></div><?php endif; ?>
          </td>
          <td>
            <form method="post" class="inline-flex items-center gap-1">
              <?= csrf_field() ?>
              <input type="hidden" name="action" value="set_status">
              <input type="hidden" name="id" value="<?= (int) $l['id'] ?>">
              <select name="status" onchange="this.form.submit()" class="text-xs border border-slate-200 rounded-lg px-2 py-1 bg-white">
                <?php foreach (['new' => 'Yeni', 'contacted' => 'İletişimde', 'closed' => 'Kapanan', 'spam' => 'Spam'] as $k => $v): ?>
                  <option value="<?= $k ?>" <?= $l['status'] === $k ? 'selected' : '' ?>><?= $v ?></option>
                <?php endforeach; ?>
              </select>
            </form>
          </td>
          <td class="whitespace-nowrap">
            <a class="btn btn-ghost btn-icon" href="/admin/lead-edit.php?id=<?= (int) $l['id'] ?>" title="Detay"><span class="material-symbols-outlined text-base">visibility</span></a>
            <form method="post" class="inline" data-confirm="Lead silinecek, emin misiniz?">
              <?= csrf_field() ?>
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= (int) $l['id'] ?>">
              <button class="btn btn-danger btn-icon" type="submit" title="Sil"><span class="material-symbols-outlined text-base">delete</span></button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
