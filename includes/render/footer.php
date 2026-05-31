<?php
$phone     = setting('contact_phone');
$phoneLbl  = setting('contact_phone_label');
$website   = setting('contact_website');
$email     = setting('contact_email');
$linkedin  = setting('contact_linkedin');
$about     = setting('footer_about');
?>
<footer class="w-full pt-16 pb-0 px-6 md:px-12" style="background:#002555">
  <div class="grid grid-cols-1 md:grid-cols-4 gap-12 max-w-screen-2xl mx-auto">
    <div class="col-span-1">
      <a href="/" class="inline-block mb-6 group">
        <img src="/assets/images/corpoth-logo-white.png" alt="CORPOTH" class="h-20 md:h-24 w-auto transition-transform duration-500 group-hover:scale-105"/>
      </a>
      <p class="text-blue-100 font-sans text-sm leading-relaxed max-w-xs">
        <?= e($about) ?>
      </p>
    </div>
    <div>
      <h5 class="font-bold text-white mb-6 font-sans text-sm uppercase tracking-wider">Kurumsal</h5>
      <ul class="space-y-1">
        <li><a class="footer-link" href="/hakkimizda.php">Hakkımızda</a></li>
        <li><a class="footer-link" href="/ekip.php">Ekip</a></li>
        <li><a class="footer-link" href="/ne-yapiyoruz.php">Ne yapıyoruz?</a></li>
        <li><a class="footer-link" href="/referanslar.php">Referanslar</a></li>
        <li><a class="footer-link" href="/blog.php">Blog</a></li>
        <li><a class="footer-link" href="/sss.php">SSS</a></li>
        <li><a class="footer-link" href="/iletisim.php">İletişim</a></li>
      </ul>
    </div>
    <div>
      <h5 class="font-bold text-white mb-6 font-sans text-sm uppercase tracking-wider">İletişim</h5>
      <ul class="space-y-1">
        <?php if ($phone): ?>
        <li>
          <a href="tel:<?= e(tel_link($phone)) ?>" class="footer-link">
            <span class="material-symbols-outlined text-base footer-icon">call</span>
            <span><?= e($phoneLbl ?: $phone) ?></span>
          </a>
        </li>
        <?php endif; ?>
        <?php if ($website): ?>
        <li>
          <a href="<?= e($website) ?>" target="_blank" rel="noopener noreferrer" class="footer-link">
            <span class="material-symbols-outlined text-base footer-icon">language</span>
            <span><?= e(preg_replace('#^https?://#', '', $website)) ?></span>
          </a>
        </li>
        <?php endif; ?>
        <?php if ($email): ?>
        <li>
          <a href="mailto:<?= e($email) ?>" class="footer-link">
            <span class="material-symbols-outlined text-base footer-icon">mail</span>
            <span><?= e($email) ?></span>
          </a>
        </li>
        <?php endif; ?>
        <?php if ($linkedin): ?>
        <li>
          <a href="<?= e($linkedin) ?>" target="_blank" rel="noopener noreferrer" class="footer-link">
            <span class="material-symbols-outlined text-base footer-icon">work</span>
            <span>LinkedIn</span>
          </a>
        </li>
        <?php endif; ?>
      </ul>
    </div>
    <div>
      <h5 class="font-bold text-white mb-6 font-sans text-sm uppercase tracking-wider">Yasal</h5>
      <ul class="space-y-1">
        <li><a class="footer-link" href="/gizlilik.php">Gizlilik Politikası</a></li>
        <li><a class="footer-link" href="/kvkk.php">KVKK Aydınlatma Metni</a></li>
      </ul>
    </div>
  </div>
  <div class="max-w-screen-2xl mx-auto pt-8 mt-8 border-t border-white/10 flex flex-col sm:flex-row justify-between items-center gap-2 text-slate-400 text-xs font-sans pb-6">
    <span>© <?= date('Y') ?> CORPOTH — Tüm hakları saklıdır.</span>
    <span>Powered by <a href="https://kesicioglu.com" target="_blank" rel="noopener noreferrer" class="font-bold text-white hover:underline underline-offset-4 transition-all">Kesicioglu</a></span>
  </div>
</footer>
