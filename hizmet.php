<?php
require_once __DIR__ . '/includes/bootstrap.php';

$page = page_get('hizmet-detay');

$page_title       = $page['meta_title']       ?? 'Hizmetimiz | CORPOTH';
$page_description = $page['meta_description'] ?? 'Ofis içinde, kıyafet üstü 10 dakikalık profesyonel kurumsal omurga terapisi. Sürec, hijyen ve uygulama detayları.';
$page_canonical   = rtrim(setting('canonical_url', ''), '/') . '/hizmet.php';
$page_breadcrumb  = [['label' => 'Hizmet']];
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
  $hero_eyebrow  = $page['hero_eyebrow']  ?? 'Hizmet';
  $hero_title    = $page['title']         ?? 'Kurumsal Omurga Terapisi';
  $hero_subtitle = $page['hero_subtitle'] ?? 'Çalışanlarınızın masasından kalkmadan, ofise gelen profesyonel terapi hizmeti.';
  $hero_image    = $page['hero_image']    ?? null;
  $breadcrumbs   = [['label' => 'Hizmet']];
  require __DIR__ . '/includes/render/page_hero.php';
  ?>

  <?php require __DIR__ . '/includes/render/service.php'; ?>
  <?php require __DIR__ . '/includes/render/who.php'; ?>
  <?php require __DIR__ . '/includes/render/benefits.php'; ?>
  <?php require __DIR__ . '/includes/render/process.php'; ?>
  <?php require __DIR__ . '/includes/render/why.php'; ?>
  <?php require __DIR__ . '/includes/render/scenarios.php'; ?>

  <?php if (!empty($page['content_html'])): ?>
  <section class="py-16 md:py-20 px-6 md:px-12 bg-surface-container-low">
    <div class="max-w-3xl mx-auto" data-animate="fade-up">
      <article class="blog-prose">
        <?= safe_html($page['content_html']) ?>
      </article>
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
