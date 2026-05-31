<?php
$phone     = setting('contact_phone');
$phoneLbl  = setting('contact_phone_label');
$wa        = setting('contact_whatsapp');
$email     = setting('contact_email');
?>
<section class="py-24 md:py-32 px-6 md:px-12 bg-surface" id="contact">
  <div class="max-w-6xl mx-auto">
    <div class="text-center mb-12" data-animate="fade-up">
      <h2 class="text-4xl md:text-5xl font-bold tracking-tight mb-6">Şimdi siz de çalışanlarınıza değer katın</h2>
      <p class="text-lg md:text-xl text-secondary max-w-2xl mx-auto">Size özel teklif hazırlamamız için formu doldurun ya da bizimle doğrudan iletişime geçin.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 md:gap-12">
      <!-- Lead Form -->
      <div class="lg:col-span-3 bg-surface-container-low p-6 md:p-10 rounded-xl border border-outline-variant/15" data-animate="fade-up">
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

      <!-- Direct Contact Methods -->
      <div class="lg:col-span-2 flex flex-col gap-4" data-animate="fade-up" data-animate-delay="100">
        <?php if ($phone): ?>
        <a href="tel:<?= e(tel_link($phone)) ?>" class="flex items-center gap-4 bg-primary-fixed text-primary rounded-xl p-5 hover:-translate-y-0.5 transition-transform no-underline">
          <span class="material-symbols-outlined text-2xl">call</span>
          <div>
            <div class="text-xs uppercase tracking-wider opacity-70">Telefon</div>
            <div class="text-lg font-bold"><?= e($phoneLbl ?: $phone) ?></div>
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
          <div>
            <div class="text-xs uppercase tracking-wider opacity-70">E-posta</div>
            <div class="text-lg font-bold"><?= e($email) ?></div>
          </div>
        </a>
        <?php endif; ?>

        <div class="bg-primary text-on-primary rounded-xl p-6 mt-2">
          <div class="flex items-center gap-3 mb-3">
            <span class="material-symbols-outlined">schedule</span>
            <h4 class="font-bold">Yanıt Süresi</h4>
          </div>
          <p class="text-sm text-primary-fixed/90">Form gönderiminizden sonra 1 iş günü içinde size özel teklifimizi paylaşıyoruz.</p>
        </div>
      </div>
    </div>
  </div>
</section>
