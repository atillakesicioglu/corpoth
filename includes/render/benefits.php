<?php
$benefits = benefits_active();
$stats    = stats_active();
?>
<section class="py-14 md:py-20 px-6 md:px-12 bg-surface-container-low" id="benefits">
  <div class="max-w-screen-2xl mx-auto">
    <div class="flex flex-col lg:flex-row gap-16">
      <div class="lg:w-1/3" data-animate="slide-right">
        <h2 class="text-3xl md:text-4xl font-bold tracking-tight mb-8">Kurumsal Faydalar</h2>
        <p class="text-lg text-secondary leading-relaxed">Küçük bir mola, büyük bir değişim yaratır. Corpoth'un etkileri sadece fiziksel değil, zihinsel ve kurumsaldır.</p>
        <?php if ($stats): ?>
        <div class="mt-12 space-y-4">
          <?php foreach ($stats as $s): ?>
          <div class="flex items-center gap-4 text-primary font-bold">
            <span class="material-symbols-outlined"><?= e($s['icon']) ?></span>
            <?php if (!empty($s['count_to'])): ?>
              <span class="counter" data-count-to="<?= (int) $s['count_to'] ?>" data-count-prefix="<?= attr($s['count_prefix'] ?? '') ?>" data-count-suffix="<?= attr($s['count_suffix'] ?? '') ?>"><?= e($s['value']) ?></span>
              <span><?= e($s['label']) ?></span>
            <?php else: ?>
              <span><?= e($s['value']) ?> <?= e($s['label']) ?></span>
            <?php endif; ?>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
      <div class="lg:w-2/3 grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php foreach ($benefits as $i => $b): ?>
        <div class="bg-surface-container-lowest p-7 md:p-8 rounded-xl border border-outline-variant/10" data-animate="fade-up" data-animate-delay="<?= 80 * $i ?>">
          <span class="material-symbols-outlined text-primary text-4xl mb-6"><?= e($b['icon']) ?></span>
          <h4 class="text-lg font-bold mb-3 text-on-surface"><?= e($b['title']) ?></h4>
          <p class="text-sm text-secondary"><?= e($b['description']) ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
