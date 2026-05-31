<?php
$hero = hero_get();
?>
<section class="relative min-h-[min(921px,90vh)] flex items-center px-6 md:px-12 max-w-screen-2xl mx-auto py-20" id="home">
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
    <div class="lg:col-span-7 z-10" data-animate="fade-up">
      <span class="text-primary font-label uppercase tracking-[0.2em] text-xs font-bold mb-6 block"><?= e($hero['eyebrow'] ?? '') ?></span>
      <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold tracking-tight text-on-surface leading-[1.1] mb-8">
        <?= safe_html($hero['title_html'] ?? '') ?>
      </h1>
      <p class="text-lg md:text-xl text-secondary max-w-xl leading-relaxed mb-10">
        <?= e($hero['description'] ?? '') ?>
      </p>
      <div class="flex flex-col sm:flex-row gap-4">
        <?php if (!empty($hero['primary_cta_text'])): ?>
        <a href="<?= e($hero['primary_cta_href'] ?: '#contact') ?>" class="primary-gradient text-on-primary px-8 py-4 rounded-xl font-label text-sm uppercase tracking-widest font-bold shadow-lg transition-transform hover:-translate-y-1 text-center no-underline inline-flex items-center justify-center">
          <?= e($hero['primary_cta_text']) ?>
        </a>
        <?php endif; ?>
        <?php if (!empty($hero['secondary_cta_text'])): ?>
        <a href="<?= e($hero['secondary_cta_href'] ?: '#service') ?>" class="bg-surface-container-high text-on-surface px-8 py-4 rounded-xl font-label text-sm uppercase tracking-widest font-bold hover:bg-surface-container-highest transition-colors text-center no-underline inline-flex items-center justify-center">
          <?= e($hero['secondary_cta_text']) ?>
        </a>
        <?php endif; ?>
      </div>
    </div>
    <div class="lg:col-span-5 relative" data-animate="fade-in">
      <?php if (!empty($hero['image_path'])): ?>
      <div class="rounded-xl overflow-hidden editorial-shadow lg:translate-x-12">
        <img alt="<?= e($hero['image_alt'] ?? '') ?>" class="w-full h-[420px] md:h-[500px] lg:h-[600px] object-cover" src="<?= e($hero['image_path']) ?>"/>
      </div>
      <?php endif; ?>
      <?php if (!empty($hero['badge_value'])): ?>
      <div class="absolute -bottom-6 -left-2 sm:-left-6 bg-surface-container-high p-6 sm:p-8 rounded-xl editorial-shadow max-w-[260px] sm:max-w-[280px]" data-animate="fade-up" data-animate-delay="200">
        <p class="text-primary font-bold text-3xl sm:text-4xl mb-1"><?= e($hero['badge_value']) ?></p>
        <p class="text-sm font-medium text-secondary"><?= e($hero['badge_text'] ?? '') ?></p>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>
