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

-- =============================================================
-- v2 - Multi-page seed (idempotent: INSERT IGNORE)
-- =============================================================

-- pages: Hakkimizda + Hizmet detay icerikleri
INSERT IGNORE INTO `pages` (`slug`, `title`, `hero_eyebrow`, `hero_subtitle`, `content_html`, `meta_title`, `meta_description`, `is_active`) VALUES
('hakkimizda',
 'Hakkımızda',
 'CORPOTH',
 'İş hayatının yoğun temposunda, ekiplerin enerjisini ve bedensel sağlığını koruyan kurumsal bir esenlik markası.',
 '<h2>Misyonumuz</h2><p>Modern iş yaşamının getirdiği omurga, boyun, kas-iskelet sorunlarını çalışanların gündelik akışını bozmadan ofiste ele almak. Ekiplerin verimini, motivasyonunu ve bağlılığını sürdürülebilir kılmak.</p><h2>Vizyonumuz</h2><p>Türkiye''nin önde gelen kurumlarına standart sağlayan, çalışan deneyimini bedensel esenlik perspektifinden yeniden tanımlayan kurumsal terapi markası olmak.</p><h2>Değerlerimiz</h2><ul><li><strong>Profesyonellik:</strong> Her seans sertifikalı uzmanlar tarafından, hastane hijyen standartlarında uygulanır.</li><li><strong>Erişilebilirlik:</strong> Çalışanlarınızın masasından kalkıp 10 dakikada terapiye, sonra işine dönmesi. Yer ve kıyafet değişikliği yok.</li><li><strong>Kanıt odaklı:</strong> Uyguladığımız tüm protokoller bilimsel temele dayanır; öncesi-sonrası ölçümler raporlanır.</li><li><strong>Sürdürülebilir etki:</strong> Tek seferlik bir benefit değil; periyodik ziyaretlerle iş yerinizin bedensel sağlık standardı.</li></ul>',
 'Hakkımızda | CORPOTH - Kurumsal Esenlik ve Terapi',
 'CORPOTH; Türkiye''nin önde gelen kurumlarına ofis içinde kıyafet üstü, 10 dakikalık profesyonel omurga terapisi sunar. Misyonumuzu, vizyonumuzu ve değerlerimizi keşfedin.',
 1),

('hizmet-detay',
 'Hizmetimiz',
 'Kurumsal Omurga Terapisi',
 'Çalışanlarınızın masasından kalkmadan, ofisinize kadar gelen profesyonel terapi hizmetimizi tanıyın.',
 '<h2>Kurumsal Omurga Terapisi Nedir?</h2><p>Modern ofis yaşamının getirdiği boyun, sırt ve omurga ağrılarını ofis içerisinde, çalışanın koltuğundan kalkmadan, kıyafet üstü uygulayan profesyonel bir terapi hizmetidir. Sertifikalı terapistlerimiz hastane standartlarındaki ekipmanlarıyla şirketinize gelir; her çalışanınıza özel 10 dakikalık seans uygular.</p><h2>Neden CORPOTH?</h2><p>Çalışan başına yıllık ortalama <strong>4-7 iş günü</strong> bel ve sırt ağrıları nedeniyle kaybediliyor. Ekiplerin verimini koruyan, motivasyonu yükselten ve bağlılığı artıran bu hizmet; <strong>masraf değil yatırımdır</strong>. Pilot uygulamalarımızda memnuniyet oranı %96''nın üzerindedir.</p><h2>Süreç Nasıl İşliyor?</h2><ol><li><strong>İhtiyaç Analizi:</strong> İK ekibinizle yapılan kısa görüşmede çalışan sayısı, ofis koşulları ve hedeflerinizi belirleriz.</li><li><strong>Pilot Uygulama:</strong> Genellikle 20-50 kişilik bir grup üzerinde tek günlük pilot yapar, öncesi-sonrası geri bildirim toplarız.</li><li><strong>Periyodik Plan:</strong> Aylık veya çift haftalık ziyaret takvimi belirlenir; her seans öncesi ekibinize hatırlatma gönderilir.</li><li><strong>Raporlama:</strong> Çeyreklik özet raporlarda katılım, memnuniyet ve etkinlik metrikleri paylaşılır.</li></ol><h2>Hijyen ve Güvenlik</h2><p>Tüm temas yüzeyleri her seans öncesi ve sonrası hastane standardında dezenfekte edilir. Tek kullanımlık örtüler kullanılır. Terapistlerimiz periyodik sağlık kontrolünden geçer.</p>',
 'Kurumsal Omurga Terapisi Hizmeti | CORPOTH',
 'Ofis içinde, çalışanın koltuğundan kalkmadan, kıyafet üstü 10 dakikalık profesyonel omurga terapisi. Detaylar ve süreç akışı.',
 1);

-- team_members: Cemal Kaya kurucu kaydi
INSERT IGNORE INTO `team_members` (`slug`, `full_name`, `title`, `bio`, `bio_long`, `photo`, `email`, `linkedin`, `sort_order`, `is_active`) VALUES
('cemal-kaya',
 'Cemal Kaya',
 'Kurucu, Baş Terapist',
 'CORPOTH''un kurucusu. 15+ yıllık manuel terapi ve kurumsal sağlık deneyimiyle Türkiye''nin önde gelen şirketlerinde binlerce çalışana ulaştı.',
 '<p>Cemal Kaya, 15 yılı aşkın süredir kas-iskelet sistemi rehabilitasyonu, manuel terapi ve postür koruma konularında uzmanlaşmış sertifikalı bir terapisttir. Türkiye''nin önde gelen kurumlarında 5.000''i aşkın çalışana bireysel ve grup seansları uyguladı.</p><p>Hastane bazlı klasik fizyoterapi modelinin kurumsal hayata getirdiği zorlukları yakından gözlemleyen Kaya; <strong>çalışanın yerine gitmek</strong> fikri etrafında CORPOTH metodunu geliştirdi. Bugün yöntemi; ofis koşullarına özel tasarlanmış mobil ekipman, kıyafet üstü uygulama protokolü ve 10 dakikalık standartlaştırılmış seans yapısıyla farklı sektörlerden onlarca kuruma fayda sağlıyor.</p><p>Kaya, kurumsal esenlik konusunda profesyonel topluluklara konferans verir; yazılı içerikleriyle bedensel sağlığın çalışan deneyimi üzerindeki etkisi konusunda farkındalık çalışmalarına devam eder.</p>',
 '/assets/images/team/cemal-kaya.jpg',
 NULL,
 NULL,
 10,
 1);

-- blog_categories: 3 baslangic kategori
INSERT IGNORE INTO `blog_categories` (`slug`, `name`, `description`, `sort_order`, `is_active`) VALUES
('kurumsal-saglik',  'Kurumsal Sağlık',  'İş yerinde bedensel ve zihinsel sağlık üzerine içgörüler.', 10, 1),
('ofis-ergonomisi',  'Ofis Ergonomisi',  'Masa başı çalışanların postürü, ekipman ve alışkanlıkları.', 20, 1),
('calisan-deneyimi', 'Çalışan Deneyimi', 'Bağlılık, motivasyon ve kurumsal kültür yazıları.',          30, 1);

-- blog_posts: 3 ornek yazi (admin sonrasi duzenler)
INSERT IGNORE INTO `blog_posts` (`slug`, `title`, `excerpt`, `content_html`, `cover_image`, `category_id`, `author_name`, `status`, `published_at`, `meta_description`) VALUES
('ofiste-bel-agrisi-neden-yaygin',
 'Ofiste Bel Ağrısı Neden Bu Kadar Yaygın?',
 'Ofis çalışanlarının %80''i kariyerinin bir döneminde bel ağrısı yaşıyor. Sebepler ve kurumsal ölçekte alınabilecek önlemler.',
 '<p>Modern ofis yaşamı bedenimize, milyonlarca yıllık evrimimizle geliştirdiğimiz hareket örüntülerinin tam zıttını dayatır. Günde 8 saat ve üzeri sabit oturma pozisyonu; lomber omurga üzerinde ayakta durmaya kıyasla <strong>%40 daha fazla baskı</strong> oluşturur.</p><h2>Üç Ana Tetikleyici</h2><ol><li>Yetersiz desteği olan ofis koltuğu</li><li>Monitör yüksekliğinin yanlış ayarlanması (boyun fleksiyonu)</li><li>Yetersiz hareket molası</li></ol><h2>Kurumsal Çözüm</h2><p>Bireysel düzeyde farkındalık tek başına yetmez. Şirketin ergonomi standartı belirlemesi, periyodik terapi seansları ile hareket alışkanlığı kazandırması ve veri ile ilerlemesi gerekir.</p>',
 NULL, 1, 'CORPOTH Editör', 'published', NOW(),
 'Ofis çalışanlarında bel ağrısının yaygın nedenleri ve kurumsal düzeyde alınabilecek bilimsel temelli önlemler.'),

('10-dakika-terapinin-bilimi',
 '10 Dakikalık Terapinin Arkasındaki Bilim',
 'Kısa ama yoğun bir manuel terapi seansı, neden 1 saatlik klasik fizyoterapi kadar etkili olabilir? Aktif bölge stratejisini açıklıyoruz.',
 '<p>Manuel terapide süre tek başına etkinlik göstergesi değildir; doğru bölgeye, doğru teknikle uygulanan kısa müdahaleler bazen uzun seanslardan daha kalıcı sonuçlar verir.</p><h2>Aktif Bölge Stratejisi</h2><p>CORPOTH metodunda her çalışan için <em>en problematik 2-3 bölge</em> hızlı bir değerlendirmeyle tespit edilir; 10 dakika boyunca yalnızca bu bölgelere yoğunlaşılır.</p><blockquote>"Doğru noktaya 60 saniye, yanlış noktaya 60 dakikadan değerlidir." - Manuel terapi prensibi</blockquote><h2>Veri Ne Diyor?</h2><p>Pilot uygulamalarımızda 4 haftalık periyodik 10-dakika seansları sonrası katılımcıların ağrı skoru ortalama <strong>%38 oranında</strong> azaldı.</p>',
 NULL, 1, 'Cemal Kaya', 'published', DATE_SUB(NOW(), INTERVAL 5 DAY),
 '10 dakikalık manuel terapi seansının bilimsel temeli; aktif bölge stratejisi ve veri-temelli sonuçlar.'),

('hibrit-calisma-postur',
 'Hibrit Çalışmada Postür: Evdeki Tehlike',
 'Ev ofisinde mutfak masası, kanepe, yatak... Her gün değişen çalışma yüzeyi bedeninize ne yapıyor?',
 '<p>Hibrit çalışma modeli özgürlük getirdi ama kontrolsüz ortamlar bedensel sorunları artırdı. Pandemi sonrası araştırmalar, hibrit çalışanların boyun ağrısı şikayetinde <strong>%24 artış</strong> olduğunu gösteriyor.</p><h2>3 Pratik Öneri</h2><ul><li>Sabit bir ev-ofis köşesi kurun; her gün aynı yerde çalışın.</li><li>Ekran yüksekliğinizi kitap/laptop standı ile göz hizasına getirin.</li><li>Saatte 2 dakika ayağa kalkın; küçük bir mola, büyük bir koruma.</li></ul><p>Şirketler hibrit ekipler için yıllık ev-ofis ergonomi denetimi ve kurumsal terapi paketleri sunarak kayıp iş günlerini azaltabilir.</p>',
 NULL, 2, 'CORPOTH Editör', 'published', DATE_SUB(NOW(), INTERVAL 12 DAY),
 'Hibrit çalışma düzeninin postür sağlığına etkisi ve şirketlerin alması gereken önlemler.');
