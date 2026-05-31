<?php
require_once __DIR__ . '/includes/bootstrap.php';

$page_title       = 'Ekip | CORPOTH';
$page_description = 'CORPOTH ekibi: kurucumuz Cemal Kaya ve uzman terapistlerimiz. Yılların kurumsal deneyimi, hastane standartlarındaki yaklaşım.';
$page_canonical   = rtrim(setting('canonical_url', ''), '/') . '/ekip.php';
$page_breadcrumb  = [['label' => 'Ekip']];
$current_page     = 'team';
$is_subpage       = true;

require __DIR__ . '/includes/render/head.php';
?>
<body class="bg-surface text-on-surface font-body">
<a href="#main" class="skip-link">İçeriğe geç</a>
<?php require __DIR__ . '/includes/render/nav.php'; ?>

<main id="main" class="pt-20">
  <?php
  extract(page_hero_load('ekip', [
    'hero_eyebrow'  => 'EKİBİMİZ',
    'hero_title'    => 'Uzman ekibimizle tanışın',
    'hero_subtitle' => 'Sertifikalı terapistlerimiz; kurumsal deneyim, manuel terapi uzmanlığı ve yenilikçi yaklaşımlarıyla şirketinize değer katar.',
  ]));
  $breadcrumbs = [['label' => 'Ekip']];
  require __DIR__ . '/includes/render/page_hero.php';
  ?>

  <?php require __DIR__ . '/includes/render/team_grid.php'; ?>

  <!-- Boyle bir ekibe katilmak ister misiniz? -->
  <section class="py-12 md:py-16 px-6 md:px-12 bg-surface-container-low">
    <div class="max-w-3xl mx-auto text-center" data-animate="fade-up">
      <span class="text-primary font-label uppercase tracking-[0.2em] text-xs font-bold mb-3 block">Kariyer</span>
      <h2 class="text-2xl md:text-3xl font-bold tracking-tight mb-4">Ekibimize katılmak ister misiniz?</h2>
      <p class="text-secondary mb-6">Manuel terapi konusunda uzmanlaşmış, kurumsal hayata değer katmak isteyen terapistleri ekibimize davet ediyoruz.</p>
      <a href="<?= e('mailto:' . setting('contact_email', '')) ?>?subject=Kariyer%20Basvurusu" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-primary text-on-primary font-semibold hover:-translate-y-0.5 transition-transform">
        <span class="material-symbols-outlined">forward_to_inbox</span>
        Başvurumu Gönder
      </a>
    </div>
  </section>

  <?php require __DIR__ . '/includes/render/cta_band.php'; ?>
</main>

<?php require __DIR__ . '/includes/render/footer.php'; ?>
<?php require __DIR__ . '/includes/render/cookie_banner.php'; ?>
<?php $mainV = @filemtime(__DIR__ . '/assets/js/main.js') ?: time(); $animV = @filemtime(__DIR__ . '/assets/js/animations.js') ?: $mainV; ?>
<script src="/assets/js/main.js?v=<?= $mainV ?>" defer></script>
<script src="/assets/js/animations.js?v=<?= $animV ?>" defer></script>
</body>
</html>
