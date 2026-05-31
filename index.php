<?php
require_once __DIR__ . '/includes/bootstrap.php';

// Anasayfa icin SEO meta'larini settings'tan al
$page_title       = setting('site_title');
$page_description = setting('site_description');
$page_canonical   = setting('canonical_url');

require __DIR__ . '/includes/render/head.php';
?>
<body class="bg-surface text-on-surface font-body selection:bg-primary-fixed selection:text-primary">
<a href="#main" class="skip-link">İçeriğe geç</a>
<?php require __DIR__ . '/includes/render/nav.php'; ?>

<main id="main" class="pt-20 overflow-x-hidden">
  <?php require __DIR__ . '/includes/render/hero.php'; ?>
  <?php require __DIR__ . '/includes/render/service.php'; ?>
  <?php require __DIR__ . '/includes/render/who.php'; ?>
  <?php require __DIR__ . '/includes/render/benefits.php'; ?>
  <?php require __DIR__ . '/includes/render/process.php'; ?>
  <?php require __DIR__ . '/includes/render/why.php'; ?>
  <?php require __DIR__ . '/includes/render/scenarios.php'; ?>
  <?php require __DIR__ . '/includes/render/testimonials.php'; ?>
  <?php require __DIR__ . '/includes/render/faq.php'; ?>
  <?php require __DIR__ . '/includes/render/references.php'; ?>
  <?php require __DIR__ . '/includes/render/contact.php'; ?>
</main>

<?php require __DIR__ . '/includes/render/footer.php'; ?>
<?php require __DIR__ . '/includes/render/cookie_banner.php'; ?>

<?php
$mainV = @filemtime(__DIR__ . '/assets/js/main.js') ?: time();
$animV = @filemtime(__DIR__ . '/assets/js/animations.js') ?: $mainV;
?>
<script src="/assets/js/main.js?v=<?= $mainV ?>" defer></script>
<script src="/assets/js/animations.js?v=<?= $animV ?>" defer></script>
</body>
</html>
