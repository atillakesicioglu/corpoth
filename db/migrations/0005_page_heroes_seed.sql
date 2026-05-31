-- =============================================================
-- Migration 0005 - Diger sayfa hero kayitlari (admin'den duzenlenebilir)
-- Idempotent: INSERT IGNORE
-- =============================================================

INSERT IGNORE INTO `pages` (`slug`, `title`, `hero_eyebrow`, `hero_subtitle`, `content_html`, `meta_title`, `meta_description`, `is_active`) VALUES
('ekip',
 'Uzman ekibimizle tanışın',
 'EKİBİMİZ',
 'Sertifikalı terapistlerimiz; kurumsal deneyim, manuel terapi uzmanlığı ve yenilikçi yaklaşımlarıyla şirketinize değer katar.',
 NULL,
 'Ekip | CORPOTH',
 'CORPOTH ekibi: kurucumuz Cemal Kaya ve uzman terapistlerimiz.',
 1),

('referanslar',
 'Birlikte çalıştığımız markalar',
 'REFERANSLAR',
 'Türkiye''nin önde gelen kurumlarına bedensel esenlik standardı sunuyoruz.',
 NULL,
 'Referanslar | CORPOTH',
 'CORPOTH ile çalışan kurumlar ve müşteri geri bildirimleri.',
 1),

('sss',
 'Sıkça Sorulan Sorular',
 'BİLGİ',
 'En çok merak edilen konular bir arada. Aradığınızı bulamazsanız bize iletin.',
 NULL,
 'SSS | CORPOTH',
 'CORPOTH hizmeti hakkında sık sorulan sorular.',
 1),

('iletisim',
 'Birlikte çalışmaya hazırız',
 'İLETİŞİM',
 'Şirketinize özel teklif hazırlayabilmemiz için formu doldurun veya doğrudan bizimle iletişime geçin.',
 NULL,
 'İletişim | CORPOTH',
 'CORPOTH ile iletişime geçin: telefon, e-posta, WhatsApp.',
 1),

('blog',
 'İçgörüler & uzman yazıları',
 'BLOG',
 'Kurumsal sağlık, ofis ergonomisi ve çalışan deneyimi üzerine düşünceler.',
 NULL,
 'Blog | CORPOTH',
 'CORPOTH Blog: kurumsal sağlık ve ofis ergonomisi yazıları.',
 1);
