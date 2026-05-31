-- =============================================================
-- Migration 0004 - Navigation menusu (header)
-- Idempotent: tablo varsa atlanir, seed INSERT IGNORE.
-- Self-referencing FK: parent_id -> nav_items.id (cocuk silinince zincir kopar)
-- =============================================================

CREATE TABLE IF NOT EXISTS `nav_items` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` INT UNSIGNED DEFAULT NULL,
  `label` VARCHAR(120) NOT NULL,
  `href` VARCHAR(255) NOT NULL DEFAULT '#',
  `icon` VARCHAR(60) DEFAULT NULL,
  `description` VARCHAR(255) DEFAULT NULL,
  `key_slug` VARCHAR(50) DEFAULT NULL COMMENT 'Aktif sayfa eslestirmesi icin: home/service/about/...',
  `is_dropdown_parent` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'TRUE: tiklanmaz, sadece dropdown acar',
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_parent`     (`parent_id`),
  KEY `idx_sort`       (`sort_order`),
  KEY `idx_active`     (`is_active`),
  CONSTRAINT `fk_nav_parent` FOREIGN KEY (`parent_id`) REFERENCES `nav_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed (INSERT IGNORE: bos tabloda calisir, dolu tabloda dokunmaz)
-- Top-level: ID 1-5 reserve. Sadece bu tablo sifirsa eklenir.

INSERT IGNORE INTO `nav_items`
  (`id`, `parent_id`, `label`,    `href`,            `icon`, `description`, `key_slug`,   `is_dropdown_parent`, `sort_order`, `is_active`)
VALUES
  (1, NULL, 'Anasayfa',  '/',                NULL, NULL, 'home',       0, 10, 1),
  (2, NULL, 'Ne yapıyoruz?', '/ne-yapiyoruz.php', NULL, NULL, 'service',    0, 20, 1),
  (3, NULL, 'Kurumsal',  '#',                NULL, NULL, 'corporate',  1, 30, 1),
  (4, NULL, 'Bilgi',     '#',                NULL, NULL, 'resources',  1, 40, 1),
  (5, NULL, 'İletişim',  '/iletisim.php',    NULL, NULL, 'contact',    0, 50, 1);

INSERT IGNORE INTO `nav_items`
  (`parent_id`, `label`,        `href`,             `icon`,             `description`,                                   `key_slug`,   `is_dropdown_parent`, `sort_order`, `is_active`)
VALUES
  (3, 'Hakkımızda',  '/hakkimizda.php',  'corporate_fare', 'Misyonumuz, vizyonumuz, değerlerimiz',     'about',      0, 10, 1),
  (3, 'Ekip',        '/ekip.php',        'groups',         'Kurucu ve uzman kadromuz',                 'team',       0, 20, 1),
  (3, 'Referanslar', '/referanslar.php', 'apartment',      'Birlikte çalıştığımız markalar',           'references', 0, 30, 1),
  (4, 'Blog',        '/blog.php',        'article',        'İçgörü ve uzman yazıları',                 'blog',       0, 10, 1),
  (4, 'SSS',         '/sss.php',         'help',           'Sıkça sorulan sorular',                    'faq',        0, 20, 1);
