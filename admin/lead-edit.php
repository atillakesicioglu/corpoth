<?php
require_once __DIR__ . '/bootstrap.php';
admin_guard();

$id   = (int) ($_GET['id'] ?? 0);
$lead = lead_get($id);
if (!$lead) {
    flash_set('error', 'Lead bulunamadı.');
    redirect('/admin/leads.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    lead_update_status($id, (string) $_POST['status'], (string) ($_POST['notes'] ?? ''));
    audit_log('lead_update', 'leads', $id);
    flash_set('success', 'Lead güncellendi.');
    redirect('/admin/lead-edit.php?id=' . $id);
}

$pageTitle  = 'Lead #' . $id;
$activePage = 'leads';
require __DIR__ . '/partials/header.php';
?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
  <div class="card lg:col-span-2 space-y-4">
    <div class="flex items-center justify-between">
      <h2 class="text-lg font-bold"><?= e($lead['name']) ?></h2>
      <span class="badge badge-<?= e($lead['status']) ?>"><?= e($lead['status']) ?></span>
    </div>
    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
      <div><dt class="text-xs uppercase text-slate-500 font-bold">Tarih</dt><dd><?= e(fmt_date($lead['created_at'])) ?></dd></div>
      <div><dt class="text-xs uppercase text-slate-500 font-bold">E-posta</dt><dd><a class="text-slate-700 hover:underline" href="mailto:<?= e($lead['email']) ?>"><?= e($lead['email']) ?></a></dd></div>
      <div><dt class="text-xs uppercase text-slate-500 font-bold">Telefon</dt><dd><?= e($lead['phone'] ?: '—') ?></dd></div>
      <div><dt class="text-xs uppercase text-slate-500 font-bold">Şirket</dt><dd><?= e($lead['company']) ?></dd></div>
      <div><dt class="text-xs uppercase text-slate-500 font-bold">Çalışan Sayısı</dt><dd><?= e($lead['employees_range'] ?: '—') ?></dd></div>
      <div><dt class="text-xs uppercase text-slate-500 font-bold">Pozisyon</dt><dd><?= e($lead['position'] ?: '—') ?></dd></div>
      <div class="md:col-span-2"><dt class="text-xs uppercase text-slate-500 font-bold mb-1">Mesaj</dt><dd class="bg-slate-50 rounded-xl p-3 whitespace-pre-line"><?= e($lead['message'] ?: '—') ?></dd></div>
      <div><dt class="text-xs uppercase text-slate-500 font-bold">IP</dt><dd class="text-xs text-slate-500"><?= e($lead['ip']) ?></dd></div>
      <div><dt class="text-xs uppercase text-slate-500 font-bold">Referer</dt><dd class="text-xs text-slate-500 truncate"><?= e($lead['referer'] ?: '—') ?></dd></div>
    </dl>
  </div>

  <div class="card space-y-3">
    <h3 class="font-bold">Durum / Notlar</h3>
    <form method="post">
      <?= csrf_field() ?>
      <div class="field">
        <label>Durum</label>
        <select name="status">
          <?php foreach (['new' => 'Yeni', 'contacted' => 'İletişimde', 'closed' => 'Kapanan', 'spam' => 'Spam'] as $k => $v): ?>
            <option value="<?= $k ?>" <?= $lead['status'] === $k ? 'selected' : '' ?>><?= $v ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="field">
        <label>Dahili Notlar</label>
        <textarea name="notes" rows="6"><?= e($lead['notes'] ?? '') ?></textarea>
      </div>
      <button class="btn btn-primary" type="submit"><span class="material-symbols-outlined text-base">save</span> Kaydet</button>
      <a class="btn btn-ghost" href="/admin/leads.php">Geri</a>
    </form>
    <hr class="my-4 border-slate-200">
    <a class="btn btn-ghost w-full justify-center" href="mailto:<?= e($lead['email']) ?>?subject=<?= eu('Corpoth — Teklif görüşmesi (' . $lead['company'] . ')') ?>">
      <span class="material-symbols-outlined text-base">mail</span> E-posta gönder
    </a>
    <?php if ($lead['phone']): ?>
    <a class="btn btn-ghost w-full justify-center" href="<?= e(wa_link($lead['phone'])) ?>" target="_blank">
      <span class="material-symbols-outlined text-base">chat</span> WhatsApp
    </a>
    <?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
