<?php
/**
 * Ortak CRUD ekrani (icon + title + description + sort + active).
 * Beklenen degiskenler:
 *   $resource         - 'audience', 'benefit', 'value_prop' vb. (tekil)
 *   $resourcePlural   - 'audiences', 'benefits', 'value_props' (URL slug)
 *   $titleSingle      - 'Hedef Kitle'
 *   $titlePlural      - 'Hedef Kitleler'
 *   $defaultIcon      - Material Symbols ad (orn. 'groups')
 *   $rows             - listelenecek tum kayitlar
 *   $editing          - duzenlenecek kayit (varsa)
 *   $iconColumn       - boolean (ikon kolonunu gosterir)
 *   $hasIcon          - boolean (form ikon alani gosterir)
 *
 * Form action POST'u ayni sayfaya yapar.
 */
$hasIcon = $hasIcon ?? true;
?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
  <div class="card lg:col-span-2">
    <div class="flex items-center justify-between mb-4">
      <h2 class="font-bold text-lg"><?= e($titlePlural) ?></h2>
      <a class="btn btn-primary" href="?"><span class="material-symbols-outlined text-base">add</span> Yeni</a>
    </div>
    <?php if (!$rows): ?>
      <p class="text-sm text-slate-500">Henüz kayıt yok.</p>
    <?php else: ?>
      <div class="overflow-x-auto">
        <table class="admin-table">
          <thead>
            <tr>
              <?php if ($hasIcon): ?><th>İkon</th><?php endif; ?>
              <th>Başlık</th>
              <th>Açıklama</th>
              <th>Sıra</th>
              <th>Durum</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $r): ?>
            <tr>
              <?php if ($hasIcon): ?><td><span class="material-symbols-outlined text-2xl text-slate-700"><?= e($r['icon']) ?></span></td><?php endif; ?>
              <td class="font-semibold"><?= e($r['title']) ?></td>
              <td class="text-sm text-slate-600"><?= e(str_excerpt($r['description'], 120)) ?></td>
              <td><?= (int) $r['sort_order'] ?></td>
              <td>
                <?php if ($r['active']): ?>
                  <span class="badge badge-closed">Aktif</span>
                <?php else: ?>
                  <span class="badge badge-spam">Pasif</span>
                <?php endif; ?>
              </td>
              <td class="whitespace-nowrap">
                <a class="btn btn-ghost btn-icon" href="?edit=<?= (int) $r['id'] ?>" title="Düzenle"><span class="material-symbols-outlined text-base">edit</span></a>
                <form method="post" class="inline" data-confirm="Silinecek, emin misiniz?">
                  <?= csrf_field() ?>
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
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

  <div class="card">
    <h3 class="font-bold mb-3"><?= !empty($editing['id']) ? e($titleSingle) . ' #' . (int) $editing['id'] . ' Düzenle' : 'Yeni ' . e($titleSingle) ?></h3>
    <form method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="action" value="save">
      <?php if (!empty($editing['id'])): ?><input type="hidden" name="id" value="<?= (int) $editing['id'] ?>"><?php endif; ?>
      <?php if ($hasIcon): ?>
      <div class="field">
        <label>İkon (Material Symbols)</label>
        <input type="text" name="icon" value="<?= e($editing['icon'] ?? $defaultIcon) ?>" required>
        <span class="help"><a class="text-slate-700 underline" href="https://fonts.google.com/icons" target="_blank">İkon kataloğu</a></span>
      </div>
      <?php endif; ?>
      <div class="field">
        <label>Başlık</label>
        <input type="text" name="title" value="<?= e($editing['title'] ?? '') ?>" required>
      </div>
      <div class="field">
        <label>Açıklama</label>
        <textarea name="description" rows="4" required><?= e($editing['description'] ?? '') ?></textarea>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div class="field">
          <label>Sıra</label>
          <input type="number" name="sort_order" value="<?= (int) ($editing['sort_order'] ?? 0) ?>">
        </div>
        <div class="field">
          <label>Durum</label>
          <label class="inline-flex items-center gap-2 mt-2"><input type="checkbox" name="active" <?= !isset($editing['active']) || $editing['active'] ? 'checked' : '' ?>> Aktif</label>
        </div>
      </div>
      <div class="pt-2 flex gap-2">
        <button class="btn btn-primary" type="submit"><span class="material-symbols-outlined text-base">save</span> Kaydet</button>
        <?php if (!empty($editing['id'])): ?>
          <a class="btn btn-ghost" href="?">İptal</a>
        <?php endif; ?>
      </div>
    </form>
  </div>
</div>
