<?php
require_once __DIR__ . '/includes/bootstrap.php';

$page_title       = 'İletişim | CORPOTH';
$page_description = 'CORPOTH ile iletişime geçin: telefon, e-posta, WhatsApp ve LinkedIn üzerinden bize ulaşın veya teklif formunu doldurun.';
$page_canonical   = rtrim(setting('canonical_url', ''), '/') . '/iletisim.php';
$page_breadcrumb  = [['label' => 'İletişim']];
$page_jsonld      = [
    '@type' => 'ContactPage',
    'name'  => 'İletişim',
    'url'   => $page_canonical,
];
$current_page     = 'contact';
$is_subpage       = true;

require __DIR__ . '/includes/render/head.php';

$phone     = setting('contact_phone');
$phoneLbl  = setting('contact_phone_label');
$wa        = setting('contact_whatsapp');
$email     = setting('contact_email');
$linkedin  = setting('contact_linkedin');
?>
<body class="bg-surface text-on-surface font-body">
<a href="#main" class="skip-link">İçeriğe geç</a>
<?php require __DIR__ . '/includes/render/nav.php'; ?>

<main id="main" class="pt-20">
  <?php
  extract(page_hero_load('iletisim', [
    'hero_eyebrow'  => 'İLETİŞİM',
    'hero_title'    => 'Birlikte çalışmaya hazırız',
    'hero_subtitle' => 'Şirketinize özel teklif hazırlayabilmemiz için formu doldurun veya doğrudan bizimle iletişime geçin. Genellikle 1 iş günü içinde size dönüş yapıyoruz.',
  ]));
  $breadcrumbs = [['label' => 'İletişim']];
  require __DIR__ . '/includes/render/page_hero.php';
  ?>

  <section class="py-16 md:py-20 px-6 md:px-12" id="form">
    <div class="max-w-screen-2xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-8 md:gap-12">
      <!-- Sol: Iletisim kanallari -->
      <div class="lg:col-span-1 space-y-4" data-animate="fade-up">
        <h2 class="text-2xl font-bold tracking-tight mb-2">İletişim Kanalları</h2>
        <p class="text-secondary mb-6">En hızlı yanıt için telefon veya WhatsApp'ı tercih edebilirsiniz.</p>

        <?php if ($phone): ?>
        <a href="tel:<?= e(tel_link($phone)) ?>" class="flex items-center gap-4 bg-primary-fixed text-primary rounded-xl p-5 hover:-translate-y-0.5 transition-transform no-underline">
          <span class="material-symbols-outlined text-2xl">call</span>
          <div class="min-w-0">
            <div class="text-xs uppercase tracking-wider opacity-70">Telefon</div>
            <div class="text-lg font-bold truncate"><?= e($phoneLbl ?: $phone) ?></div>
          </div>
        </a>
        <?php endif; ?>

        <?php if ($wa): ?>
        <a href="<?= e(wa_link($wa)) ?>" target="_blank" rel="noopener noreferrer" class="flex items-center gap-4 bg-[#25D366]/10 text-[#128C7E] rounded-xl p-5 hover:-translate-y-0.5 transition-transform no-underline">
          <span class="material-symbols-outlined text-2xl">chat</span>
          <div>
            <div class="text-xs uppercase tracking-wider opacity-70">WhatsApp</div>
            <div class="text-lg font-bold">Mesaj Gönder</div>
          </div>
        </a>
        <?php endif; ?>

        <?php if ($email): ?>
        <a href="mailto:<?= e($email) ?>" class="flex items-center gap-4 bg-surface-container-high text-on-surface rounded-xl p-5 hover:-translate-y-0.5 transition-transform no-underline">
          <span class="material-symbols-outlined text-2xl">mail</span>
          <div class="min-w-0">
            <div class="text-xs uppercase tracking-wider opacity-70">E-posta</div>
            <div class="text-lg font-bold truncate"><?= e($email) ?></div>
          </div>
        </a>
        <?php endif; ?>

        <?php if ($linkedin): ?>
        <a href="<?= e($linkedin) ?>" target="_blank" rel="noopener noreferrer" class="flex items-center gap-4 bg-[#0A66C2]/10 text-[#0A66C2] rounded-xl p-5 hover:-translate-y-0.5 transition-transform no-underline">
          <span class="material-symbols-outlined text-2xl">work</span>
          <div>
            <div class="text-xs uppercase tracking-wider opacity-70">LinkedIn</div>
            <div class="text-lg font-bold">Şirketimizi takip edin</div>
          </div>
        </a>
        <?php endif; ?>

        <div class="bg-primary text-on-primary rounded-xl p-6 mt-2">
          <div class="flex items-center gap-3 mb-3">
            <span class="material-symbols-outlined">schedule</span>
            <h3 class="font-bold">Yanıt Süresi</h3>
          </div>
          <p class="text-sm text-primary-fixed/90">Form gönderiminizden sonra <strong>1 iş günü</strong> içinde size özel teklifimizi paylaşıyoruz.</p>
        </div>
      </div>

      <!-- Sag: Form (mevcut contact partial'i form'unu kullan) -->
      <div class="lg:col-span-2" data-animate="fade-up" data-animate-delay="100">
        <div class="bg-surface-container-low p-6 md:p-10 rounded-xl border border-outline-variant/15">
          <h2 class="text-2xl md:text-3xl font-bold tracking-tight mb-2">Teklif Formu</h2>
          <p class="text-secondary mb-6">Aşağıdaki formu doldurun, size özel hazırladığımız teklifi 1 iş günü içinde gönderelim.</p>
          <form id="lead-form" action="/submit-lead.php" method="post" novalidate class="space-y-5">
            <input type="text" name="website" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true"/>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div>
                <label class="block text-sm font-semibold mb-2" for="lf-name">Ad Soyad <span class="text-error">*</span></label>
                <input id="lf-name" name="name" type="text" required class="w-full rounded-xl border border-outline-variant/30 bg-surface-container-lowest px-4 py-3 text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none"/>
              </div>
              <div>
                <label class="block text-sm font-semibold mb-2" for="lf-email">İş E-postası <span class="text-error">*</span></label>
                <input id="lf-email" name="email" type="email" required class="w-full rounded-xl border border-outline-variant/30 bg-surface-container-lowest px-4 py-3 text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none"/>
              </div>
              <div>
                <label class="block text-sm font-semibold mb-2" for="lf-phone">Telefon</label>
                <input id="lf-phone" name="phone" type="tel" class="w-full rounded-xl border border-outline-variant/30 bg-surface-container-lowest px-4 py-3 text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none"/>
              </div>
              <div>
                <label class="block text-sm font-semibold mb-2" for="lf-company">Şirket <span class="text-error">*</span></label>
                <input id="lf-company" name="company" type="text" required class="w-full rounded-xl border border-outline-variant/30 bg-surface-container-lowest px-4 py-3 text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none"/>
              </div>
            </div>

            <div>
              <label class="block text-sm font-semibold mb-3">Çalışan Sayısı</label>
              <div class="flex flex-wrap gap-3">
                <?php foreach (['<50' => '50-', '50-200' => '50-200', '200-500' => '200-500', '500+' => '500+'] as $val => $lbl): ?>
                <label class="inline-flex items-center gap-2 cursor-pointer rounded-xl border border-outline-variant/30 bg-surface-container-lowest px-4 py-2.5 hover:border-primary/40 has-[:checked]:border-primary has-[:checked]:bg-primary-fixed has-[:checked]:text-primary transition-colors text-sm font-medium">
                  <input type="radio" name="employees_range" value="<?= attr($val) ?>" class="accent-primary"/>
                  <?= e($lbl) ?>
                </label>
                <?php endforeach; ?>
              </div>
            </div>

            <div>
              <label class="block text-sm font-semibold mb-2" for="lf-position">Pozisyon</label>
              <select id="lf-position" name="position" class="w-full rounded-xl border border-outline-variant/30 bg-surface-container-lowest px-4 py-3 text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none">
                <option value="">Seçiniz</option>
                <option>İK / Çalışan Deneyimi</option>
                <option>Yönetim / C-level</option>
                <option>Ofis / Operasyon</option>
                <option>Satın Alma</option>
                <option>Diğer</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-semibold mb-2" for="lf-message">Mesajınız</label>
              <textarea id="lf-message" name="message" rows="3" class="w-full rounded-xl border border-outline-variant/30 bg-surface-container-lowest px-4 py-3 text-on-surface focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none resize-none"></textarea>
            </div>

            <label class="flex items-start gap-3 text-sm text-secondary">
              <input type="checkbox" name="kvkk" required class="mt-1 accent-primary"/>
              <span><a href="/kvkk.php" class="text-primary hover:underline">KVKK Aydınlatma Metni</a>'ni okudum ve kişisel verilerimin işlenmesine onay veriyorum.</span>
            </label>

            <button type="submit" class="primary-gradient text-white w-full md:w-auto px-8 py-4 rounded-xl font-label text-sm uppercase tracking-widest font-bold shadow-lg hover:-translate-y-0.5 transition-transform inline-flex items-center justify-center gap-2">
              <span class="material-symbols-outlined text-lg">send</span>
              Teklif Talep Et
            </button>

            <div id="lead-form-status" class="hidden text-sm rounded-xl px-4 py-3" role="status" aria-live="polite"></div>
          </form>
        </div>
      </div>
    </div>
  </section>
</main>

<?php require __DIR__ . '/includes/render/footer.php'; ?>
<?php require __DIR__ . '/includes/render/cookie_banner.php'; ?>
<?php $mainV = @filemtime(__DIR__ . '/assets/js/main.js') ?: time(); $animV = @filemtime(__DIR__ . '/assets/js/animations.js') ?: $mainV; ?>
<script src="/assets/js/main.js?v=<?= $mainV ?>" defer></script>
<script src="/assets/js/animations.js?v=<?= $animV ?>" defer></script>
</body>
</html>
