-- =============================================================
-- Migration 0003 - Sayfa hero gorseli icin overlay opaklik ve blur
-- Idempotent: kolon yoksa eklenir, hata vermez.
-- =============================================================

-- pages tablosuna hero_overlay_opacity (0-100) ve hero_blur (0-30 px) kolonlari
-- "ADD COLUMN IF NOT EXISTS" MariaDB'de calisir; MySQL 8 < 8.0.29 desteklemez.
-- Bu yuzden information_schema kontrolu ile kosullu calistiriyoruz.

SET @col_exists := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'pages' AND COLUMN_NAME = 'hero_overlay_opacity'
);
SET @sql := IF(@col_exists = 0,
  'ALTER TABLE `pages` ADD COLUMN `hero_overlay_opacity` TINYINT UNSIGNED NOT NULL DEFAULT 50 AFTER `hero_image`',
  'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @col_exists := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'pages' AND COLUMN_NAME = 'hero_blur'
);
SET @sql := IF(@col_exists = 0,
  'ALTER TABLE `pages` ADD COLUMN `hero_blur` TINYINT UNSIGNED NOT NULL DEFAULT 0 AFTER `hero_overlay_opacity`',
  'SELECT 1');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
