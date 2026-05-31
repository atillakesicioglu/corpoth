<?php
/**
 * Ic sayfa hero (banner) partial.
 *
 * Beklenen degiskenler:
 *   $hero_eyebrow      - kucuk ust etiket (opsiyonel)
 *   $hero_title        - buyuk baslik (zorunlu)
 *   $hero_subtitle     - alt metin (opsiyonel)
 *   $hero_image        - arkaplan / yan gorsel path (opsiyonel)
 *   $hero_align        - 'left' (default) | 'center'
 *   $breadcrumbs       - breadcrumb partial'a iletilir
 */
$align = $hero_align ?? 'left';
$alignCls = $align === 'center' ? 'text-center mx-auto' : '';
?>
<section class="page-hero">
  <div class="page-hero-bg" aria-hidden="true"></div>
  <div class="max-w-screen-2xl mx-auto px-6 md:px-12 pt-32 md:pt-36 pb-12 md:pb-16 relative">
    <?php if (!empty($breadcrumbs)) require __DIR__ . '/breadcrumb.php'; ?>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center mt-6">
      <div class="lg:col-span-<?= !empty($hero_image) ? '7' : '12' ?> max-w-3xl <?= $alignCls ?>" data-animate="fade-up">
        <?php if (!empty($hero_eyebrow)): ?>
        <span class="text-primary font-label uppercase tracking-[0.2em] text-xs font-bold mb-4 block"><?= e($hero_eyebrow) ?></span>
        <?php endif; ?>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight text-on-surface leading-[1.1] mb-6">
          <?= safe_html($hero_title ?? '') ?>
        </h1>
        <?php if (!empty($hero_subtitle)): ?>
        <p class="text-lg md:text-xl text-secondary leading-relaxed">
          <?= safe_html($hero_subtitle) ?>
        </p>
        <?php endif; ?>
      </div>
      <?php if (!empty($hero_image)): ?>
      <div class="lg:col-span-5" data-animate="fade-in">
        <div class="rounded-xl overflow-hidden editorial-shadow">
          <img src="<?= e($hero_image) ?>" alt="<?= e($hero_image_alt ?? $hero_title ?? '') ?>" class="w-full h-[280px] md:h-[360px] lg:h-[420px] object-cover"/>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>
