<?php
/**
 * Ic sayfa hero (banner) partial.
 *
 * Beklenen degiskenler:
 *   $hero_eyebrow         - kucuk ust etiket (opsiyonel)
 *   $hero_title           - buyuk baslik (zorunlu)
 *   $hero_subtitle        - alt metin (opsiyonel)
 *   $hero_image           - arkaplan gorseli path (opsiyonel)
 *   $hero_overlay_opacity - 0-100 (default: 50)
 *   $hero_blur            - 0-30 px (default: 0)
 *   $hero_align           - 'left' (default) | 'center'
 *   $breadcrumbs          - breadcrumb partial'a iletilir
 */
$align    = $hero_align ?? 'left';
$alignCls = $align === 'center' ? 'text-center mx-auto' : '';

$opacity = isset($hero_overlay_opacity) ? max(0, min(100, (int) $hero_overlay_opacity)) : 50;
$blurPx  = isset($hero_blur) ? max(0, min(30, (int) $hero_blur)) : 0;

$hasImage = !empty($hero_image);

$overlayAlpha = $opacity / 100;
$darkText     = !$hasImage;
$titleCls     = $hasImage ? 'text-white' : 'text-on-surface';
$subtitleCls  = $hasImage ? 'text-white/85' : 'text-secondary';
$eyebrowCls   = $hasImage ? 'text-white/80' : 'text-primary';
?>
<section class="page-hero <?= $hasImage ? 'page-hero-image' : '' ?>" <?= $hasImage ? 'style="--hero-blur: ' . (int)$blurPx . 'px; --hero-overlay-alpha: ' . $overlayAlpha . ';"' : '' ?>>
  <?php if ($hasImage): ?>
    <div class="page-hero-img" style="background-image: url('<?= e($hero_image) ?>');" aria-hidden="true"></div>
    <div class="page-hero-overlay" aria-hidden="true"></div>
  <?php else: ?>
    <div class="page-hero-bg" aria-hidden="true"></div>
  <?php endif; ?>

  <div class="max-w-screen-2xl mx-auto px-6 md:px-12 page-hero-inner relative z-10">
    <?php if (!empty($breadcrumbs)) require __DIR__ . '/breadcrumb.php'; ?>

    <div class="max-w-3xl <?= $alignCls ?> mt-6" data-animate="fade-up">
      <?php if (!empty($hero_eyebrow)): ?>
      <span class="<?= $eyebrowCls ?> font-label tracking-[0.12em] text-xs font-bold mb-3 block"><?= e($hero_eyebrow) ?></span>
      <?php endif; ?>

      <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold tracking-tight leading-[1.15] mb-4 <?= $titleCls ?>">
        <?= safe_html($hero_title ?? '') ?>
      </h1>

      <?php if (!empty($hero_subtitle)): ?>
      <p class="text-lg md:text-xl leading-relaxed <?= $subtitleCls ?>">
        <?= safe_html($hero_subtitle) ?>
      </p>
      <?php endif; ?>
    </div>
  </div>
</section>
