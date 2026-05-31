<?php
require_once __DIR__ . '/includes/bootstrap.php';

$page_title       = 'Referanslar | CORPOTH';
$page_description = 'CORPOTH ile çalışan kurumlar; çalışan deneyimini bedensel esenlik perspektifinden zenginleştiren marka çevremiz.';
$page_canonical   = rtrim(setting('canonical_url', ''), '/') . '/referanslar.php';
$page_breadcrumb  = [['label' => 'Referanslar']];
$current_page     = 'references';
$is_subpage       = true;

require __DIR__ . '/includes/render/head.php';

$logos  = function_exists('references_active') ? references_active() : [];
$testis = function_exists('testimonials_active') ? testimonials_active() : [];
?>
<body class="bg-surface text-on-surface font-body">
<a href="#main" class="skip-link">İçeriğe geç</a>
<?php require __DIR__ . '/includes/render/nav.php'; ?>

<main id="main" class="pt-20">
  <?php
  $hero_eyebrow  = 'REFERANSLAR';
  $hero_title    = 'Birlikte çalıştığımız markalar';
  $hero_subtitle = 'Türkiye''nin önde gelen kurumlarına bedensel esenlik standardı sunuyoruz.';
  $breadcrumbs   = [['label' => 'Referanslar']];
  require __DIR__ . '/includes/render/page_hero.php';
  ?>

  <?php if ($logos): ?>
  <section class="py-16 md:py-24 px-6 md:px-12">
    <div class="max-w-screen-2xl mx-auto">
      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5 md:gap-6">
        <?php foreach ($logos as $i => $l): ?>
        <div class="bg-white rounded-xl border border-outline-variant/15 p-6 flex items-center justify-center min-h-[120px] hover:-translate-y-1 transition-transform" data-animate="fade-up" data-animate-delay="<?= 50 * $i ?>">
          <?php if (!empty($l['logo_path'])): ?>
            <img src="<?= e($l['logo_path']) ?>" alt="<?= e($l['name']) ?>" class="max-h-12 max-w-full object-contain"/>
          <?php else: ?>
            <span class="text-secondary font-bold text-sm tracking-wide"><?= e($l['name']) ?></span>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <?php if ($testis): ?>
  <section class="py-16 md:py-24 px-6 md:px-12 bg-surface-container-low">
    <div class="max-w-screen-2xl mx-auto">
      <div class="text-center mb-12" data-animate="fade-up">
        <h2 class="text-3xl md:text-4xl font-bold tracking-tight mb-4">Müşterilerimiz ne diyor?</h2>
        <p class="text-secondary max-w-2xl mx-auto">Birlikte çalıştığımız ekiplerden gelen geri bildirimler.</p>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
        <?php foreach ($testis as $i => $t): ?>
        <article class="bg-white rounded-xl border border-outline-variant/15 p-6 md:p-8" data-animate="fade-up" data-animate-delay="<?= 80 * $i ?>">
          <div class="flex gap-1 mb-4 text-amber-400">
            <?php for ($s = 0; $s < (int)($t['rating'] ?? 5); $s++): ?>
            <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1; font-size:1.1rem">star</span>
            <?php endfor; ?>
          </div>
          <p class="text-on-surface leading-relaxed mb-5">"<?= e($t['content']) ?>"</p>
          <div class="flex items-center gap-3 pt-4 border-t border-outline-variant/15">
            <?php if (!empty($t['photo_path'])): ?>
            <img src="<?= e($t['photo_path']) ?>" alt="<?= e($t['name']) ?>" class="w-11 h-11 rounded-full object-cover"/>
            <?php else: ?>
            <div class="w-11 h-11 rounded-full bg-primary-fixed text-primary inline-flex items-center justify-center font-bold"><?= e(mb_substr($t['name'] ?? '?', 0, 1)) ?></div>
            <?php endif; ?>
            <div>
              <p class="font-bold text-sm"><?= e($t['name']) ?></p>
              <p class="text-xs text-secondary"><?= e(trim(($t['role'] ?? '') . (!empty($t['company']) ? ' • ' . $t['company'] : ''), ' •')) ?></p>
            </div>
          </div>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <?php require __DIR__ . '/includes/render/cta_band.php'; ?>
</main>

<?php require __DIR__ . '/includes/render/footer.php'; ?>
<?php require __DIR__ . '/includes/render/cookie_banner.php'; ?>
<?php $mainV = @filemtime(__DIR__ . '/assets/js/main.js') ?: time(); $animV = @filemtime(__DIR__ . '/assets/js/animations.js') ?: $mainV; ?>
<script src="/assets/js/main.js?v=<?= $mainV ?>" defer></script>
<script src="/assets/js/animations.js?v=<?= $animV ?>" defer></script>
</body>
</html>
