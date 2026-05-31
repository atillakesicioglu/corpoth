<?php
require_once __DIR__ . '/includes/bootstrap.php';

$page_title       = 'Sıkça Sorulan Sorular | CORPOTH';
$page_description = 'CORPOTH hizmeti hakkında en sık sorulan sorular: hijyen, fiyatlandırma, süreç, kapsam ve daha fazlası.';
$page_canonical   = rtrim(setting('canonical_url', ''), '/') . '/sss.php';
$page_breadcrumb  = [['label' => 'SSS']];
$current_page     = 'faq';
$is_subpage       = true;
$include_faq_schema = true;

require __DIR__ . '/includes/render/head.php';
?>
<body class="bg-surface text-on-surface font-body">
<a href="#main" class="skip-link">İçeriğe geç</a>
<?php require __DIR__ . '/includes/render/nav.php'; ?>

<main id="main" class="pt-20">
  <?php
  $hero_eyebrow  = 'BİLGİ';
  $hero_title    = 'Sıkça Sorulan Sorular';
  $hero_subtitle = 'En çok merak edilen konular bir arada. Aradığınızı bulamazsanız bize iletin.';
  $breadcrumbs   = [['label' => 'SSS']];
  require __DIR__ . '/includes/render/page_hero.php';
  ?>

  <?php require __DIR__ . '/includes/render/faq.php'; ?>

  <?php require __DIR__ . '/includes/render/cta_band.php'; ?>
</main>

<?php require __DIR__ . '/includes/render/footer.php'; ?>
<?php require __DIR__ . '/includes/render/cookie_banner.php'; ?>
<?php $mainV = @filemtime(__DIR__ . '/assets/js/main.js') ?: time(); $animV = @filemtime(__DIR__ . '/assets/js/animations.js') ?: $mainV; ?>
<script src="/assets/js/main.js?v=<?= $mainV ?>" defer></script>
<script src="/assets/js/animations.js?v=<?= $animV ?>" defer></script>
</body>
</html>
