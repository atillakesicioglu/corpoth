-- =============================================================
-- Migration 0002 - v2 multi-page baslangic verileri
-- Idempotent: INSERT IGNORE, mevcut kayitlari ezmez.
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
