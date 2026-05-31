<?php
require_once __DIR__ . '/includes/bootstrap.php';

$page = page_get('ne-yapiyoruz');

$page_title       = $page['meta_title']       ?? 'Ne yapıyoruz? | CORPOTH';
$page_description = $page['meta_description'] ?? 'Ofis içinde kıyafet üstü kurumsal omurga terapisi: süreç, hijyen, faydalar ve uygulama detayları.';
$page_canonical   = rtrim(setting('canonical_url', ''), '/') . '/ne-yapiyoruz.php';
$page_breadcrumb  = [['label' => 'Ne yapıyoruz?']];
$page_jsonld      = [
    '@type'       => 'Service',
    'serviceType' => 'Kurumsal Omurga Terapisi',
    'name'        => 'CORPOTH Kurumsal Omurga Terapisi',
    'description' => $page_description,
    'provider'    => ['@id' => rtrim(setting('canonical_url', ''), '/') . '/#organization'],
    'url'         => $page_canonical,
];
$current_page     = 'service';
$is_subpage       = true;

require __DIR__ . '/includes/render/head.php';
?>
<body class="bg-surface text-on-surface font-body">
<a href="#main" class="skip-link">İçeriğe geç</a>
<?php require __DIR__ . '/includes/render/nav.php'; ?>

<main id="main" class="pt-20">
  <?php
  extract(page_hero_load('ne-yapiyoruz', [
    'hero_eyebrow'  => 'Ne yapıyoruz?',
    'hero_title'    => 'Ne yapıyoruz?',
    'hero_subtitle' => 'Ofis içinde, kıyafet üstü ve 10 dakikada uygulanan kurumsal omurga terapisi ile ekiplerinize bedensel esenlik standardı getiriyoruz.',
  ]));
  $breadcrumbs = [['label' => 'Ne yapıyoruz?']];
  require __DIR__ . '/includes/render/page_hero.php';
  ?>

  <?php require __DIR__ . '/includes/render/service.php'; ?>
  <?php require __DIR__ . '/includes/render/who.php'; ?>
  <?php require __DIR__ . '/includes/render/benefits.php'; ?>
  <?php require __DIR__ . '/includes/render/process.php'; ?>
  <?php require __DIR__ . '/includes/render/why.php'; ?>
  <?php require __DIR__ . '/includes/render/scenarios.php'; ?>

  <?php require __DIR__ . '/includes/render/cta_band.php'; ?>
</main>

<?php require __DIR__ . '/includes/render/footer.php'; ?>
<?php require __DIR__ . '/includes/render/cookie_banner.php'; ?>
<?php $mainV = @filemtime(__DIR__ . '/assets/js/main.js') ?: time(); $animV = @filemtime(__DIR__ . '/assets/js/animations.js') ?: $mainV; ?>
<script src="/assets/js/main.js?v=<?= $mainV ?>" defer></script>
<script src="/assets/js/animations.js?v=<?= $animV ?>" defer></script>
</body>
</html>
