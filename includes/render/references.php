<?php
$refs = references_active();
if (!$refs) return;
$refsDoubled = array_merge($refs, $refs);
?>
<section class="py-12 md:py-16 bg-surface-container-low border-y border-outline-variant/10" id="references" aria-label="Referanslarımız">
  <div class="max-w-screen-2xl mx-auto px-6 md:px-12 mb-8 text-center">
    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-secondary">Referanslarımız</p>
  </div>
  <div class="overflow-hidden">
    <div class="refs-track">
      <?php foreach ($refsDoubled as $r): ?>
      <div class="ref-logo-pill" title="<?= attr($r['name']) ?>">
        <?php if (!empty($r['logo_path'])): ?>
          <img src="<?= e($r['logo_path']) ?>" alt="<?= e($r['name']) ?>" loading="lazy" onerror="this.style.display='none';this.nextElementSibling.style.display='flex';"/>
          <span style="display:none"><?= e($r['name']) ?></span>
        <?php else: ?>
          <span><?= e($r['name']) ?></span>
        <?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
