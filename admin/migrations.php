<?php
require_once __DIR__ . '/bootstrap.php';
admin_guard();

migrations_init();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_check()) {
    $action  = $_POST['_action'] ?? '';
    $results = [];

    if ($action === 'run_one' && !empty($_POST['file'])) {
        $path = migrations_resolve_path((string) $_POST['file']);
        if ($path !== null) {
            $results[] = migrations_run_file($path);
            audit_log('migration_run', 'migrations', null, ['file' => basename($path), 'success' => $results[0]['success']]);
        } else {
            flash_set('error', 'Migration dosyasi bulunamadi.');
        }
    } elseif ($action === 'run_all') {
        $results = migrations_run_all();
        audit_log('migrations_run_all', 'migrations', null, ['count' => count($results)]);
    } elseif ($action === 'mark_applied' && !empty($_POST['file'])) {
        $path = migrations_resolve_path((string) $_POST['file']);
        if ($path !== null) {
            migrations_mark_applied(basename($path));
            audit_log('migration_mark_applied', 'migrations', null, ['file' => basename($path)]);
            flash_set('success', 'Migration "uygulanmis" olarak isaretlendi: ' . basename($path));
        } else {
            flash_set('error', 'Migration dosyasi bulunamadi.');
        }
    } elseif ($action === 'rerun' && !empty($_POST['file'])) {
        $path = migrations_resolve_path((string) $_POST['file']);
        if ($path !== null) {
            $results[] = migrations_run_file($path);
            audit_log('migration_rerun', 'migrations', null, ['file' => basename($path), 'success' => $results[0]['success']]);
        } else {
            flash_set('error', 'Migration dosyasi bulunamadi.');
        }
    }

    foreach ($results as $r) {
        $msg = $r['success'] ? 'Başarılı: ' : 'Hata: ';
        $msg .= $r['name'] . ' (' . $r['statements'] . ' statement, ' . $r['elapsed_ms'] . ' ms)';
        if (!$r['success'] && !empty($r['error'])) {
            $msg .= ' — ' . $r['error'];
        }
        flash_set($r['success'] ? 'success' : 'error', $msg);
    }

    redirect('/admin/migrations.php');
}

$files       = migrations_files();
$appliedMap  = migrations_applied_map();
$pendingList = migrations_pending();
$pendingNames = array_map('basename', $pendingList);
$previewName = isset($_GET['preview']) ? basename((string) $_GET['preview']) : '';
$previewPath = $previewName ? migrations_resolve_path($previewName) : null;
$previewSql  = $previewPath ? (string) file_get_contents($previewPath) : '';

$pageTitle  = 'Veritabanı Güncellemeleri';
$activePage = 'migrations';
require __DIR__ . '/partials/header.php';
?>

<div class="space-y-6">

  <div class="rounded-xl border border-slate-200 bg-white p-5">
    <div class="flex items-start gap-4">
      <span class="material-symbols-outlined text-3xl text-slate-700">storage</span>
      <div class="flex-1">
        <h2 class="text-base font-bold mb-1">DB Migration Yöneticisi</h2>
        <p class="text-sm text-slate-600">
          Geliştirmelerle birlikte gelen yeni tabloları, alan değişikliklerini ve başlangıç verilerini
          buradan tek tıkla uygulayabilirsin. Migration dosyaları
          <code class="px-1 bg-slate-100 rounded">db/migrations/</code> klasörü altındadır ve sırayla çalıştırılır.
        </p>
      </div>
    </div>
  </div>

  <?php
    $totalCount   = count($files);
    $appliedCount = 0;
    foreach ($appliedMap as $row) if ((int)$row['success'] === 1) $appliedCount++;
    $pendingCount = count($pendingList);
    $failedCount  = 0;
    foreach ($appliedMap as $row) if ((int)$row['success'] === 0) $failedCount++;
  ?>

  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="rounded-xl border border-slate-200 bg-white p-4">
      <p class="text-xs font-medium uppercase text-slate-500 tracking-wide">Toplam Dosya</p>
      <p class="text-2xl font-bold mt-1"><?= (int)$totalCount ?></p>
    </div>
    <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4">
      <p class="text-xs font-medium uppercase text-emerald-700 tracking-wide">Uygulanmış</p>
      <p class="text-2xl font-bold mt-1 text-emerald-700"><?= (int)$appliedCount ?></p>
    </div>
    <div class="rounded-xl border border-amber-200 bg-amber-50 p-4">
      <p class="text-xs font-medium uppercase text-amber-700 tracking-wide">Bekleyen</p>
      <p class="text-2xl font-bold mt-1 text-amber-700"><?= (int)$pendingCount ?></p>
    </div>
    <div class="rounded-xl border border-red-200 bg-red-50 p-4">
      <p class="text-xs font-medium uppercase text-red-700 tracking-wide">Hatalı</p>
      <p class="text-2xl font-bold mt-1 text-red-700"><?= (int)$failedCount ?></p>
    </div>
  </div>

  <?php if ($pendingCount > 0): ?>
  <div class="rounded-xl border border-amber-200 bg-amber-50 p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
    <div class="flex items-start gap-3">
      <span class="material-symbols-outlined text-amber-700">bolt</span>
      <div>
        <p class="text-sm font-semibold text-amber-900">
          <?= (int)$pendingCount ?> bekleyen migration var
        </p>
        <p class="text-xs text-amber-800 mt-0.5">
          Hepsini sırayla uygulamak için aşağıdaki butonu kullan. İlk hatada durur.
        </p>
      </div>
    </div>
    <form method="post" onsubmit="return confirm('Tüm bekleyen migration\'lar uygulansın mı?');">
      <?= csrf_field() ?>
      <input type="hidden" name="_action" value="run_all">
      <button class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold">
        <span class="material-symbols-outlined text-base">play_arrow</span>
        Bekleyenleri Uygula (<?= (int)$pendingCount ?>)
      </button>
    </form>
  </div>
  <?php else: ?>
  <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 flex items-center gap-3">
    <span class="material-symbols-outlined text-emerald-700">check_circle</span>
    <p class="text-sm text-emerald-900">
      Veritabanı güncel. Bekleyen migration yok.
    </p>
  </div>
  <?php endif; ?>

  <div class="rounded-xl border border-slate-200 bg-white overflow-hidden">
    <div class="px-5 py-3 border-b border-slate-200 flex items-center justify-between">
      <h3 class="text-sm font-bold tracking-tight">Migration Dosyaları</h3>
      <span class="text-xs text-slate-500">db/migrations/</span>
    </div>

    <?php if (empty($files)): ?>
    <div class="p-6 text-sm text-slate-500">
      Hiç migration dosyası bulunamadı. <code class="px-1 bg-slate-100 rounded">db/migrations/</code> klasörünü kontrol et.
    </div>
    <?php else: ?>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
          <tr>
            <th class="text-left px-5 py-3">Dosya</th>
            <th class="text-left px-3 py-3">Durum</th>
            <th class="text-left px-3 py-3">Uygulanma</th>
            <th class="text-left px-3 py-3">Süre</th>
            <th class="text-right px-5 py-3">İşlem</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
        <?php foreach ($files as $f):
          $name    = basename($f);
          $row     = $appliedMap[$name] ?? null;
          $applied = $row !== null && (int)$row['success'] === 1;
          $failed  = $row !== null && (int)$row['success'] === 0;
          $isPending = !$applied;
        ?>
          <tr class="<?= $failed ? 'bg-red-50/30' : '' ?>">
            <td class="px-5 py-3">
              <div class="font-mono text-[13px] font-medium text-slate-900"><?= e($name) ?></div>
              <?php if ($failed && !empty($row['error_message'])): ?>
                <div class="mt-1 text-xs text-red-700 max-w-xl truncate" title="<?= e($row['error_message']) ?>">
                  <span class="material-symbols-outlined text-[14px] align-text-bottom">error</span>
                  <?= e($row['error_message']) ?>
                </div>
              <?php endif; ?>
            </td>
            <td class="px-3 py-3">
              <?php if ($applied): ?>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                  <span class="material-symbols-outlined text-[14px]">check</span>Uygulanmış
                </span>
              <?php elseif ($failed): ?>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                  <span class="material-symbols-outlined text-[14px]">error</span>Hatalı
                </span>
              <?php else: ?>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                  <span class="material-symbols-outlined text-[14px]">schedule</span>Bekliyor
                </span>
              <?php endif; ?>
            </td>
            <td class="px-3 py-3 text-xs text-slate-600">
              <?php if ($row): ?>
                <?= e($row['applied_at']) ?>
              <?php else: ?>
                <span class="text-slate-400">—</span>
              <?php endif; ?>
            </td>
            <td class="px-3 py-3 text-xs text-slate-600">
              <?php if ($row && (int)$row['elapsed_ms'] > 0): ?>
                <?= (int)$row['elapsed_ms'] ?> ms
                <?php if ((int)$row['statements_count'] > 0): ?>
                  <span class="text-slate-400">/ <?= (int)$row['statements_count'] ?> stmt</span>
                <?php endif; ?>
              <?php else: ?>
                <span class="text-slate-400">—</span>
              <?php endif; ?>
            </td>
            <td class="px-5 py-3 text-right">
              <div class="inline-flex items-center gap-1.5">
                <a href="?preview=<?= urlencode($name) ?>#preview"
                   class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 text-xs font-medium text-slate-700">
                  <span class="material-symbols-outlined text-[14px]">visibility</span>Önizle
                </a>

                <?php if ($isPending): ?>
                <form method="post" class="inline" onsubmit="return confirm('Migration uygulansin mi?\n\n<?= e($name) ?>');">
                  <?= csrf_field() ?>
                  <input type="hidden" name="_action" value="run_one">
                  <input type="hidden" name="file" value="<?= e($name) ?>">
                  <button class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold">
                    <span class="material-symbols-outlined text-[14px]">play_arrow</span>Uygula
                  </button>
                </form>
                <form method="post" class="inline" onsubmit="return confirm('Bu migration zaten elle uygulanmissa, sadece kayda gecirilsin mi (calistirilmadan)?\n\n<?= e($name) ?>');">
                  <?= csrf_field() ?>
                  <input type="hidden" name="_action" value="mark_applied">
                  <input type="hidden" name="file" value="<?= e($name) ?>">
                  <button class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 text-xs font-medium text-slate-700" title="Migration'i calistirmadan 'uygulandi' olarak isaretle">
                    <span class="material-symbols-outlined text-[14px]">task_alt</span>İşaretle
                  </button>
                </form>
                <?php elseif ($failed): ?>
                <form method="post" class="inline" onsubmit="return confirm('Migration tekrar denensin mi?\n\n<?= e($name) ?>');">
                  <?= csrf_field() ?>
                  <input type="hidden" name="_action" value="rerun">
                  <input type="hidden" name="file" value="<?= e($name) ?>">
                  <button class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold">
                    <span class="material-symbols-outlined text-[14px]">refresh</span>Tekrar
                  </button>
                </form>
                <?php else: /* applied */ ?>
                <form method="post" class="inline" onsubmit="return confirm('Bu migration tekrar calistirilsin mi? (Idempotent dosyalarda zararsizdir.)\n\n<?= e($name) ?>');">
                  <?= csrf_field() ?>
                  <input type="hidden" name="_action" value="rerun">
                  <input type="hidden" name="file" value="<?= e($name) ?>">
                  <button class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 text-xs font-medium text-slate-700" title="Idempotent dosyalarda guvenlidir">
                    <span class="material-symbols-outlined text-[14px]">refresh</span>Tekrar
                  </button>
                </form>
                <?php endif; ?>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>

  <?php if ($previewPath): ?>
  <div id="preview" class="rounded-xl border border-slate-200 bg-white overflow-hidden">
    <div class="px-5 py-3 border-b border-slate-200 flex items-center justify-between gap-3">
      <div class="flex items-center gap-2 min-w-0">
        <span class="material-symbols-outlined text-slate-700">description</span>
        <span class="font-mono text-sm font-semibold truncate"><?= e(basename($previewPath)) ?></span>
        <?php
          $isPreviewPending = in_array(basename($previewPath), $pendingNames, true);
        ?>
        <?php if ($isPreviewPending): ?>
          <span class="ml-2 px-2 py-0.5 rounded-full text-[11px] font-medium bg-amber-100 text-amber-800">Bekliyor</span>
        <?php else: ?>
          <span class="ml-2 px-2 py-0.5 rounded-full text-[11px] font-medium bg-emerald-100 text-emerald-800">Uygulanmış</span>
        <?php endif; ?>
      </div>
      <a href="/admin/migrations.php" class="text-xs text-slate-500 hover:text-slate-900">Önizlemeyi kapat</a>
    </div>
    <pre class="px-5 py-4 overflow-x-auto text-xs leading-relaxed bg-slate-950 text-slate-100"><code><?= e($previewSql) ?></code></pre>
  </div>
  <?php endif; ?>

  <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
    <h3 class="text-sm font-bold mb-2 flex items-center gap-2">
      <span class="material-symbols-outlined text-base">tips_and_updates</span>
      Nasıl Çalışır?
    </h3>
    <ol class="list-decimal pl-5 text-sm text-slate-700 space-y-1.5">
      <li>Geliştirme sırasında DB'ye yapılan her değişiklik (yeni tablo, kolon, ya da seed) <code class="px-1 bg-white border rounded">db/migrations/NNNN_aciklama.sql</code> dosyasına yazılır.</li>
      <li>Yeni dosyalar deploy ile sunucuya gelir; bu sayfada otomatik olarak <strong>Bekleyen</strong> olarak listelenir.</li>
      <li><strong>Bekleyenleri Uygula</strong> butonuyla hepsi sırayla çalışır; ilk hatada durur ve hatalı migration <strong>Hatalı</strong> olarak gösterilir.</li>
      <li>Migration dosyaları <em>idempotent</em> (CREATE IF NOT EXISTS / INSERT IGNORE) yazılır; tekrar çalıştırmak güvenlidir.</li>
      <li>Bir migration'i phpMyAdmin'den manuel uyguladıysan, <strong>İşaretle</strong> ile sisteme bildirebilirsin.</li>
    </ol>
  </div>

</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
