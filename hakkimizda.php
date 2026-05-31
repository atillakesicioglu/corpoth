<?php
require_once __DIR__ . '/includes/bootstrap.php';

$page = page_get('hakkimizda');

$page_title       = $page['meta_title']       ?? 'Hakkımızda | CORPOTH';
$page_description = $page['meta_description'] ?? 'CORPOTH hakkında: misyonumuz, vizyonumuz ve değerlerimiz.';
$page_canonical   = rtrim(setting('canonical_url', ''), '/') . '/hakkimizda.php';
$page_og_image    = $page['og_image'] ?? null;
$page_breadcrumb  = [['label' => 'Hakkımızda']];
$page_jsonld      = [
    '@type'       => 'AboutPage',
    'name'        => 'Hakkımızda',
    'url'         => $page_canonical,
    'description' => $page_description,
];
$current_page     = 'about';
$is_subpage       = true;

require __DIR__ . '/includes/render/head.php';
?>
<body class="bg-surface text-on-surface font-body">
<a href="#main" class="skip-link">İçeriğe geç</a>
<?php require __DIR__ . '/includes/render/nav.php'; ?>

<main id="main" class="pt-20">
  <?php
  $hero_eyebrow  = $page['hero_eyebrow']  ?? 'CORPOTH';
  $hero_title    = $page['title']         ?? 'Hakkımızda';
  $hero_subtitle = $page['hero_subtitle'] ?? 'Kurumsal bedensel esenlik konusunda Türkiye''nin önde gelen markalarına hizmet veriyoruz.';
  $hero_image    = $page['hero_image']    ?? null;
  $breadcrumbs   = [['label' => 'Hakkımızda']];
  require __DIR__ . '/includes/render/page_hero.php';
  ?>

  <?php if (!empty($page['content_html'])): ?>
  <section class="py-16 md:py-24 px-6 md:px-12">
    <div class="max-w-3xl mx-auto" data-animate="fade-up">
      <article class="blog-prose">
        <?= safe_html($page['content_html']) ?>
      </article>
    </div>
  </section>
  <?php endif; ?>

  <!-- Stats serit (mevcut stats verilerini kullan) -->
  <?php
  $stats = function_exists('stats_active') ? stats_active() : [];
  if ($stats):
  ?>
  <section class="py-16 px-6 md:px-12 bg-primary text-on-primary">
    <div class="max-w-screen-2xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
      <?php foreach ($stats as $i => $s): ?>
      <div data-animate="fade-up" data-animate-delay="<?= 80 * $i ?>">
        <span class="material-symbols-outlined text-4xl mb-2 opacity-70"><?= e($s['icon'] ?? 'trending_up') ?></span>
        <div class="text-4xl md:text-5xl font-bold tracking-tight" data-counter data-count-to="<?= e($s['count_to'] ?? '') ?>" data-count-prefix="<?= e($s['count_prefix'] ?? '') ?>" data-count-suffix="<?= e($s['count_suffix'] ?? '') ?>"><?= e($s['value']) ?></div>
        <p class="text-primary-fixed/85 mt-1 text-sm md:text-base"><?= e($s['label']) ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>

  <!-- Ekip onizleme + ekip sayfasi linki -->
  <?php $teamPreview = function_exists('team_active') ? team_active() : []; ?>
  <?php if ($teamPreview): ?>
  <section class="py-20 md:py-28 px-6 md:px-12">
    <div class="max-w-screen-2xl mx-auto">
      <div class="text-center mb-12" data-animate="fade-up">
        <h2 class="text-3xl md:text-4xl font-bold tracking-tight mb-4">Ekibimiz</h2>
        <p class="text-secondary max-w-2xl mx-auto">Sertifikalı uzman terapistlerimiz, yılların kurumsal deneyimi ve hastane standartlarındaki yaklaşımlarıyla hizmet veriyor.</p>
      </div>
      <?php $team_members = array_slice($teamPreview, 0, 4); require __DIR__ . '/includes/render/team_grid.php'; ?>
      <div class="text-center mt-4">
        <a href="/ekip.php" class="inline-flex items-center gap-2 text-primary font-semibold hover:gap-3 transition-all">
          Tüm ekibi gör <span class="material-symbols-outlined">arrow_forward</span>
        </a>
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
