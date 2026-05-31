<?php
$svc      = service_block_get();
$features = service_features_active();
?>
<section class="bg-surface-container-low py-24 md:py-32 px-6 md:px-12" id="service">
  <div class="max-w-screen-2xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-24 items-center">
      <?php if (!empty($svc['image_path'])): ?>
      <div class="order-2 lg:order-1" data-animate="slide-right">
        <img alt="<?= e($svc['image_alt'] ?? '') ?>" class="rounded-xl editorial-shadow w-full h-[400px] lg:h-[500px] object-cover" src="<?= e($svc['image_path']) ?>"/>
      </div>
      <?php endif; ?>
      <div class="order-1 lg:order-2" data-animate="slide-left">
        <h2 class="text-3xl md:text-4xl font-bold mb-8 tracking-tight"><?= e($svc['title'] ?? '') ?></h2>
        <p class="text-lg text-secondary leading-relaxed mb-12">
          <?= e($svc['description'] ?? '') ?>
        </p>
        <?php if ($features): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
          <?php foreach ($features as $i => $f): ?>
          <div class="flex flex-col gap-4" data-animate="fade-up" data-animate-delay="<?= 100 * $i ?>">
            <span class="material-symbols-outlined text-primary text-3xl"><?= e($f['icon']) ?></span>
            <h4 class="font-bold"><?= e($f['title']) ?></h4>
            <p class="text-sm text-secondary"><?= e($f['description']) ?></p>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
