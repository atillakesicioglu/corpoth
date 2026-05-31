-- =============================================================
-- Migration 0006 - Ne yapıyoruz sayfasi + nav + Cemal Kaya biyografi
-- Idempotent: slug/nav guncellemeleri mevcut kayitlara uygulanir.
-- =============================================================

-- pages: hizmet-detay -> ne-yapiyoruz (slug degisimi)
UPDATE `pages`
SET
  `slug`             = 'ne-yapiyoruz',
  `title`            = 'Ne yapıyoruz?',
  `hero_eyebrow`     = 'Ne yapıyoruz?',
  `hero_subtitle`    = 'Ofis içinde, kıyafet üstü ve 10 dakikada uygulanan kurumsal omurga terapisi ile ekiplerinize bedensel esenlik standardı getiriyoruz.',
  `meta_title`       = 'Ne yapıyoruz? | CORPOTH',
  `meta_description` = 'CORPOTH ofis içinde kıyafet üstü kurumsal omurga terapisi sunar. Sürecimiz, faydalarımız ve uygulama detaylarımız.'
WHERE `slug` = 'hizmet-detay';

-- Zaten ne-yapiyoruz slug'i varsa metinleri guncelle
UPDATE `pages`
SET
  `title`            = 'Ne yapıyoruz?',
  `hero_eyebrow`     = 'Ne yapıyoruz?',
  `hero_subtitle`    = 'Ofis içinde, kıyafet üstü ve 10 dakikada uygulanan kurumsal omurga terapisi ile ekiplerinize bedensel esenlik standardı getiriyoruz.',
  `meta_title`       = 'Ne yapıyoruz? | CORPOTH',
  `meta_description` = 'CORPOTH ofis içinde kıyafet üstü kurumsal omurga terapisi sunar. Sürecimiz, faydalarımız ve uygulama detaylarımız.'
WHERE `slug` = 'ne-yapiyoruz';

-- Nav: Hizmet -> Ne yapıyoruz?
UPDATE `nav_items`
SET `label` = 'Ne yapıyoruz?', `href` = '/ne-yapiyoruz.php'
WHERE `href` = '/hizmet.php' OR (`key_slug` = 'service' AND `parent_id` IS NULL);

-- Cemal Kaya: CV''ye uygun biyografi
UPDATE `team_members`
SET
  `title`    = 'Kurucu & Baş Terapist',
  `bio`      = 'Marmara Üniversitesi Beden Eğitimi mezunu; 30 yılı aşkın spor, fitness ve manuel terapi deneyimi. Levent Tenis Kulübü''nde uzun yıllar kondisyon, SPA ve tenis koordinasyonu alanında çalıştıktan sonra kurumsal esenliği ofislere taşıyan CORPOTH''u kurdu.',
  `bio_long` = '<p><strong>Cemal Kaya</strong>, 1973 Bonn doğumlu; Marmara Üniversitesi Beden Eğitimi ve Spor Yüksek Okulu (Atletizm Antrenörlüğü) mezunudur. Pedagojik formasyonu ile beden eğitimi öğretmenliği yapabilme yetkinliğine sahiptir.</p><h2>Profesyonel deneyim</h2><p>1995''ten bu yana <strong>Levent Tenis Kulübü</strong> bünyesinde fitness, SPA, tenis kondisyonerliği ve tenis koordinatörlüğü görevlerini sürdürmektedir. 2002''den itibaren çeşitli kurum ve sitelere fitness stüdyosu kurulumu danışmanlığı vermekte; yoga, pilates ve refleksoloji alanlarında eğitim ve organizasyon çalışmaları yürütmektedir.</p><p>Milli tenisçi Tuna Altuna''nın yurtdışı turnuva ve kamplarında koçluk ve egzersiz terapistliği yapmış; kulübün Türkiye şampiyonası 35+ erkek takımının kaptanlığını ve takım terapistliğini üstlenmiştir. Özel Hattat Kliniği''nde refleksoloji uzmanlığı, Thai masaj ve masör-masöz beceri geliştirme sertifikaları ile manuel terapi alanındaki birikimini kurumsal hayata taşımıştır.</p><h2>CORPOTH vizyonu</h2><p>Ofis çalışanlarının gün içinde koltuğundan kalkmadan, kıyafet üstü ve hijyenik koşullarda omurga-boyun-sırt bölgesine odaklanan kısa terapi seansları sunan <strong>CORPOTH</strong> metodunu geliştirmiştir. Amacı; spor bilimleri, fitness ve manuel terapi birikimini kurumsal wellness kültürüne aktarmaktır.</p><h2>Eğitim ve sertifikalar (öne çıkanlar)</h2><ul><li>MEB Hayat Boyu Öğrenme — Masör/Masöz beceri geliştirme</li><li>Türkiye Cimnastik Federasyonu — Pilates eğitmenliği</li><li>IFBB — Kişisel fitness antrenörlüğü, spor beslenmesi, vücut geliştirme programlama</li><li>International Institute of Reflexology — Ingham metodu</li><li>ITM — Nuad Bo-Rarn Thai masajı</li><li>TREPS — International Health and Fitness Summit</li></ul><p>Dürüst, iletişim gücü yüksek ve takım çalışmasına yatkın bir yaklaşımla; kurumların çalışan sağlığı hedeflerine bilimsel temelli, uygulanabilir çözümler sunmaktadır.</p>'
WHERE `slug` = 'cemal-kaya';
