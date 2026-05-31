-- =============================================================
-- Corpoth Baslangic Verileri
-- Mevcut index.html icindeki sabit metinler/ayarlar buradan import edilir.
-- =============================================================

SET NAMES utf8mb4;

-- -------------------------------------------------------------
-- admin_users (varsayilan: admin / corpoth2026 - ilk girisde degistirilmeli)
-- password_hash bcrypt ile uretildi: password_hash('corpoth2026', PASSWORD_BCRYPT)
-- -------------------------------------------------------------
INSERT INTO `admin_users` (`username`, `password_hash`, `email`, `full_name`, `must_change_password`)
VALUES (
  'admin',
  '$2y$10$rN8cV9KZQUv5EeJ6w7H4..1tGfWMhAEZBdKW3p2Lb3mxYFqDsP6Iy',
  'info@corpoth.com',
  'Corpoth Admin',
  1
);
-- NOT: Yukaridaki hash placeholder'dir. install.php uzerinden veya phpMyAdmin'de
--       UPDATE admin_users SET password_hash = '...' yaparak gercek hash girilmelidir.
--       Veya admin paneli ilk acildiginda "must_change_password = 1" oldugu icin
--       sifre yeniden olusturulmasi istenir.

-- -------------------------------------------------------------
-- settings
-- -------------------------------------------------------------
INSERT INTO `settings` (`key_name`, `value`, `type`, `group_name`, `label`, `sort_order`) VALUES
('site_title',         'Kurumsal Masaj & Omurga Terapisi | CORPOTH',                                                                                              'text',     'seo',       'Site Basligi',           10),
('site_description',   'CORPOTH ofiste 10 dakikada kıyafet üstü baş, boyun ve sırt masajı sunar. Kurumsal omurga terapisi ile çalışan stresini azaltın, verimliliği artırın.', 'textarea', 'seo',       'Meta Description',        20),
('site_keywords',      'kurumsal masaj, ofis masaji, omurga terapisi, kurumsal wellness, calisan esenlik',                                                       'text',     'seo',       'Meta Keywords',          30),
('canonical_url',      'https://www.corpoth.com/',                                                                                                              'url',      'seo',       'Canonical URL',          40),
('og_image',           '/assets/images/cover.png',                                                                                                              'image',    'seo',       'OG Gorseli',             50),
('ga_id',              '',                                                                                                                                       'text',     'analytics', 'GA4 Olcum ID (G-XXXX)',  10),
('clarity_id',         '',                                                                                                                                       'text',     'analytics', 'Microsoft Clarity ID',   20),
('contact_phone',      '+905322212323',                                                                                                                          'tel',      'contact',   'Telefon',                10),
('contact_phone_label','0532 221 23 23',                                                                                                                         'text',     'contact',   'Telefon Etiket',         15),
('contact_whatsapp',   '+905322212323',                                                                                                                          'tel',      'contact',   'WhatsApp Numarasi',      20),
('contact_email',      'info@corpoth.com',                                                                                                                       'email',    'contact',   'E-posta',                30),
('contact_website',    'https://www.corpoth.com',                                                                                                                'url',      'contact',   'Website',                40),
('contact_linkedin',   'https://www.linkedin.com/company/corpoth',                                                                                               'url',      'contact',   'LinkedIn',               50),
('contact_address',    '',                                                                                                                                       'textarea', 'contact',   'Adres',                  60),
('footer_about',       'Türkiye''nin öncü kurumsal esenlik platformu. Ofis içi profesyonel terapi hizmetleri ile çalışan mutluluğunu odak noktamıza alıyoruz.',  'textarea', 'footer',    'Footer Hakkimizda',      10),
('cookie_text',        'Bu site, deneyiminizi iyileştirmek için çerezler kullanır. Detaylar için <a href="/kvkk.php">KVKK Aydınlatma Metni</a>''ni inceleyebilirsiniz.', 'html', 'legal',     'Cerez Bildirimi Metni',  10),
('kvkk_html',          '<h2>KVKK Aydınlatma Metni</h2><p>Bu metin, 6698 sayılı Kişisel Verilerin Korunması Kanunu kapsamında veri sorumlusu sıfatıyla CORPOTH tarafından hazırlanmıştır.</p><p><em>Detaylı metin admin panelinden düzenlenebilir.</em></p>', 'html', 'legal', 'KVKK Metni',  20),
('privacy_html',       '<h2>Gizlilik Politikası</h2><p>CORPOTH olarak gizliliğinize önem veriyoruz. Bu politika sitemizdeki kişisel veri uygulamalarımızı açıklar.</p><p><em>Detaylı metin admin panelinden düzenlenebilir.</em></p>', 'html', 'legal', 'Gizlilik Metni', 30),
('lead_notify_email',  'info@corpoth.com',                                                                                                                       'email',    'leads',     'Lead Bildirim E-posta',  10);

-- -------------------------------------------------------------
-- hero
-- -------------------------------------------------------------
INSERT INTO `hero` (`id`, `eyebrow`, `title_html`, `description`, `image_path`, `image_alt`, `primary_cta_text`, `primary_cta_href`, `secondary_cta_text`, `secondary_cta_href`, `badge_value`, `badge_text`)
VALUES (
  1,
  'Kurumsal Esenlik Lideri',
  'Çalışanlarınıza <br/> <span class="text-primary">değer katın</span>',
  'Ofis ortamında sadece 10 dakikada gerçekleştirilen, profesyonel terapistler eşliğinde sunulan kurumsal omurga terapisi ile verimliliği ve mutluluğu artırın.',
  '/assets/images/stitch-hero.jpg',
  'Premium ofis ortamında profesyonel terapi seansı',
  'Teklif Al',
  '#contact',
  'Hizmeti İncele',
  '#service',
  '10dk',
  'Hızlı ve etkili uygulama süresi ile kesintisiz verimlilik.'
);

-- -------------------------------------------------------------
-- service_block
-- -------------------------------------------------------------
INSERT INTO `service_block` (`id`, `title`, `description`, `image_path`, `image_alt`)
VALUES (
  1,
  'Kurumsal Omurga Terapisi Nedir?',
  'Corpoth, modern çalışma hayatının getirdiği fiziksel yükleri hafifletmek için tasarlanmış, klinik hassasiyette bir wellness çözümüdür. Kıyafetlerinizi çıkarmadan, sterilize edilmiş özel ekipmanlarla uygulanır.',
  '/assets/images/stitch-massage.jpg',
  'Modern ofiste profesyonel boyun ve sırt masajı uygulaması'
);

-- -------------------------------------------------------------
-- service_features
-- -------------------------------------------------------------
INSERT INTO `service_features` (`icon`, `title`, `description`, `sort_order`) VALUES
('check_circle',     'Kıyafet Üstü',       'Soyunma gerektirmeyen, iş akışını bozmayan profesyonel uygulama.',                       10),
('clean_hands',      'Maksimum Hijyen',    'Her seans öncesi ve sonrası sterilize edilen tek kullanımlık ekipmanlar.',               20),
('timer',            '10 Dakika Seans',    'Öğle arası veya kahve molasına sığan hızlı tazelenme.',                                  30),
('self_improvement', 'Bölgesel Odak',      'Boyun, omuz ve sırt bölgesindeki biriken stresi hedef alır.',                            40);

-- -------------------------------------------------------------
-- audiences
-- -------------------------------------------------------------
INSERT INTO `audiences` (`icon`, `title`, `description`, `sort_order`) VALUES
('laptop_mac',     'Masa Başı Çalışanlar', 'Uzun süre bilgisayar karşısında hareketsiz kalan, boyun ve bel ağrısı yaşayan profesyoneller.', 10),
('groups',         'İK Yöneticileri',      'Çalışan bağlılığını artırmak ve şirket kültürünü iyileştirmek isteyen insan kaynakları liderleri.', 20),
('corporate_fare', 'Vizyoner Şirketler',   'Modern yan haklar sunarak yetenekleri elde tutmak isteyen kurumsal yapılar.',                     30);

-- -------------------------------------------------------------
-- benefits
-- -------------------------------------------------------------
INSERT INTO `benefits` (`icon`, `title`, `description`, `sort_order`) VALUES
('psychology',            'Stres Azaltma',  'Zihinsel yorgunluğu giderir ve stres seviyesini anında minimize eder.',  10),
('bolt',                  'Enerji Artışı',  'Kan dolaşımını hızlandırarak vücuda taze bir enerji verir.',             20),
('center_focus_strong',   'Konsantrasyon',  'Odaklanma süresini artırarak hata payını düşürür.',                      30),
('eco',                   'Ofis Atmosferi', 'Ofis içindeki pozitif iletişimi ve ekip ruhunu güçlendirir.',            40);

-- -------------------------------------------------------------
-- stats
-- -------------------------------------------------------------
INSERT INTO `stats` (`icon`, `value`, `label`, `count_to`, `count_prefix`, `count_suffix`, `sort_order`) VALUES
('trending_up',           '%25', 'Verimlilik Artışı',  25, '%', NULL, 10),
('sentiment_satisfied',   '%40', 'Çalışan Memnuniyeti', 40, '%', NULL, 20);

-- -------------------------------------------------------------
-- process_steps
-- -------------------------------------------------------------
INSERT INTO `process_steps` (`step_number`, `title`, `description`, `sort_order`) VALUES
(1, 'Hazırlık',  'Ekibimiz ofisinize uygun bir noktada mini istasyonunu kurar.',                10),
(2, 'Analiz',    'Terapistimiz çalışanın postürünü ve ağrı noktalarını hızlıca analiz eder.',  20),
(3, 'Uygulama',  'Kıyafet üstü, 10 dakikalık hedefe yönelik terapi uygulanır.',                30),
(4, 'Dönüş',     'Çalışanınız zinde ve motive bir şekilde masasına döner.',                    40);

-- -------------------------------------------------------------
-- value_props
-- -------------------------------------------------------------
INSERT INTO `value_props` (`icon`, `title`, `description`, `sort_order`) VALUES
('task_alt', 'Pratik Çözümler',     'Ofis düzeninizi bozmadan, her mekana uyum sağlayan kurulum.',           10),
('verified', 'Uzman Kadro',         'Sertifikalı ve kurumsal görgü kurallarına hakim terapistler.',          20),
('diamond',  'Premium Deneyim',     'Çalışanlarınıza kendilerini gerçekten değerli hissettirecek kalite.', 30);

-- -------------------------------------------------------------
-- why_block
-- -------------------------------------------------------------
INSERT INTO `why_block` (`id`, `title`, `image_path`, `image_alt`)
VALUES (
  1,
  'Neden CORPOTH''u Seçmelisiniz?',
  '/assets/images/stitch-detail.jpg',
  'Profesyonel terapistin sırt masajı uyguladığı sinematik yakın çekim'
);

-- -------------------------------------------------------------
-- scenarios
-- -------------------------------------------------------------
INSERT INTO `scenarios` (`title`, `description`, `image_path`, `image_alt`, `is_text_card`, `icon`, `sort_order`) VALUES
('Ekip Ödülleri',        NULL, '/assets/images/stitch-scenario-team.jpg',  'Modern ofiste mutlu profesyonel ekip',         0, NULL,  10),
('Özel Gün Hediyeleri',  NULL, '/assets/images/stitch-scenario-gift.jpg',  'Ahşap masada zarif kurumsal hediye paketi',    0, NULL,  20),
('Kurumsal Etkinlikler', NULL, '/assets/images/stitch-scenario-event.jpg', 'Şık bir mekanda kurumsal etkinlik anı',        0, NULL,  30),
('Genişletilmiş Esenlik','Ayrıca Yoga ve Nefes Terapisi seansları ile tam kapsamlı wellness desteği.', NULL, NULL, 1, 'spa', 40);

-- -------------------------------------------------------------
-- references_logos
-- -------------------------------------------------------------
INSERT INTO `references_logos` (`name`, `logo_path`, `sort_order`) VALUES
('Turkcell',     '/assets/images/references/turkcell.png',     10),
('BKM',          '/assets/images/references/bkm.png',          20),
('Derimod',      '/assets/images/references/derimod.png',      30),
('Finartz',      '/assets/images/references/finartz.png',      40),
('Gürsoy Grup',  '/assets/images/references/gursoy-grup.png',  50);

-- -------------------------------------------------------------
-- testimonials (ornek baslangic verileri - admin'den duzenlenir)
-- -------------------------------------------------------------
INSERT INTO `testimonials` (`name`, `role`, `company`, `content`, `rating`, `sort_order`) VALUES
('Ayşe Demir',   'İK Direktörü',     'Örnek Şirket A.Ş.',  'Corpoth ile çalışan memnuniyet skorlarımızda gözle görülür bir artış yaşadık. Ekibimiz seansları sabırsızlıkla bekliyor.', 5, 10),
('Mehmet Yılmaz','Operasyon Müdürü', 'Örnek Holding',      'Ofiste 10 dakikada uygulanan profesyonel terapi, hem pratik hem etkili. Yatırım geri dönüşü çok yüksek.',                  5, 20),
('Zeynep Kaya',  'CEO',              'Örnek Teknoloji',     'Kıyafet üstü ve hijyenik yaklaşımları sayesinde tüm ekibimiz tereddütsüz katılıyor. Premium bir hizmet.',                 5, 30);

-- -------------------------------------------------------------
-- faq
-- -------------------------------------------------------------
INSERT INTO `faq` (`question`, `answer`, `sort_order`) VALUES
('Hijyen nasıl sağlanıyor?',                       'Her seans öncesi ve sonrası tüm temas yüzeyleri hastane standartlarında dezenfekte edilir. Tek kullanımlık örtüler ve sertifikalı malzemeler kullanılır.', 10),
('Kaç çalışandan itibaren hizmet veriyorsunuz?',   'Pilot uygulamalarımızı 20 kişilik ekiplerden başlatabiliyoruz. Daha büyük ekipler için aynı gün içinde birden fazla terapistle hizmet sunabiliyoruz.',  20),
('Hangi şehirlerde hizmet veriyorsunuz?',          'Başta İstanbul olmak üzere Ankara, İzmir, Bursa ve Kocaeli''nde aktif olarak hizmet veriyoruz. Diğer iller için lütfen bizimle iletişime geçin.',         30),
('Seans gerçekten 10 dakika mı?',                  'Evet, her çalışana yönelik aktif uygulama süresi 10 dakikadır. Hazırlık ve geçişlerle birlikte kişi başı toplam 12-15 dakika olarak planlanır.',         40),
('Çalışanların kıyafetlerini değiştirmesi gerekiyor mu?', 'Hayır. Tüm uygulamalar kıyafet üstü gerçekleştirilir. Çalışanlar masalarından kalkıp seansa geçip 10 dakika sonra işine dönebilir.',                  50),
('Fiyatlandırma nasıl belirleniyor?',              'Fiyatlandırma; çalışan sayısı, seans sıklığı ve hizmet verdiğimiz şehre göre değişir. Size özel teklif hazırlamamız için iletişim formunu doldurabilirsiniz.', 60);

-- NOT: v2+ baslangic verileri (pages, team_members, blog_*) artik db/migrations/ altinda
-- Admin -> "Veritabani Guncellemeleri" sayfasindan tek tikla uygulanir.
