-- =============================================================
-- Corpoth Veritabani Semasi
-- MySQL 5.7+ / MariaDB 10.3+
-- =============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -------------------------------------------------------------
-- admin_users
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE `admin_users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(64) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `email` VARCHAR(190) DEFAULT NULL,
  `full_name` VARCHAR(190) DEFAULT NULL,
  `last_login_at` DATETIME DEFAULT NULL,
  `failed_attempts` TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `locked_until` DATETIME DEFAULT NULL,
  `must_change_password` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- settings (key-value)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `key_name` VARCHAR(120) NOT NULL,
  `value` LONGTEXT,
  `type` ENUM('text','textarea','html','number','image','url','email','tel') NOT NULL DEFAULT 'text',
  `group_name` VARCHAR(80) NOT NULL DEFAULT 'general',
  `label` VARCHAR(190) DEFAULT NULL,
  `help_text` VARCHAR(255) DEFAULT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`key_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- hero (singleton)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `hero`;
CREATE TABLE `hero` (
  `id` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `eyebrow` VARCHAR(190) DEFAULT NULL,
  `title_html` VARCHAR(500) DEFAULT NULL,
  `description` TEXT,
  `image_path` VARCHAR(255) DEFAULT NULL,
  `image_alt` VARCHAR(255) DEFAULT NULL,
  `primary_cta_text` VARCHAR(80) DEFAULT NULL,
  `primary_cta_href` VARCHAR(255) DEFAULT NULL,
  `secondary_cta_text` VARCHAR(80) DEFAULT NULL,
  `secondary_cta_href` VARCHAR(255) DEFAULT NULL,
  `badge_value` VARCHAR(40) DEFAULT NULL,
  `badge_text` VARCHAR(255) DEFAULT NULL,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- service block (singleton, "Kurumsal Omurga Terapisi Nedir?")
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `service_block`;
CREATE TABLE `service_block` (
  `id` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `title` VARCHAR(255) DEFAULT NULL,
  `description` TEXT,
  `image_path` VARCHAR(255) DEFAULT NULL,
  `image_alt` VARCHAR(255) DEFAULT NULL,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- service_features (4 ozellik karti)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `service_features`;
CREATE TABLE `service_features` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `icon` VARCHAR(80) NOT NULL DEFAULT 'check_circle',
  `title` VARCHAR(120) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- audiences ("Kimler Icin?")
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `audiences`;
CREATE TABLE `audiences` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `icon` VARCHAR(80) NOT NULL DEFAULT 'groups',
  `title` VARCHAR(120) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- benefits (Kurumsal Faydalar - 4 kart)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `benefits`;
CREATE TABLE `benefits` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `icon` VARCHAR(80) NOT NULL DEFAULT 'psychology',
  `title` VARCHAR(120) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- stats (highlight rakamlari)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `stats`;
CREATE TABLE `stats` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `icon` VARCHAR(80) NOT NULL DEFAULT 'trending_up',
  `value` VARCHAR(40) NOT NULL,
  `label` VARCHAR(190) NOT NULL,
  `count_to` INT DEFAULT NULL COMMENT 'Animasyon icin sayisal hedef',
  `count_suffix` VARCHAR(10) DEFAULT NULL,
  `count_prefix` VARCHAR(10) DEFAULT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- process_steps (Nasil Calisir? 4 adim)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `process_steps`;
CREATE TABLE `process_steps` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `step_number` TINYINT UNSIGNED NOT NULL,
  `title` VARCHAR(120) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- value_props (Neden CORPOTH? listesi)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `value_props`;
CREATE TABLE `value_props` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `icon` VARCHAR(80) NOT NULL DEFAULT 'task_alt',
  `title` VARCHAR(120) NOT NULL,
  `description` VARCHAR(500) NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- why_block (Neden CORPOTH? gorseli)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `why_block`;
CREATE TABLE `why_block` (
  `id` TINYINT UNSIGNED NOT NULL DEFAULT 1,
  `title` VARCHAR(255) DEFAULT NULL,
  `image_path` VARCHAR(255) DEFAULT NULL,
  `image_alt` VARCHAR(255) DEFAULT NULL,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- scenarios (Kullanim Senaryolari)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `scenarios`;
CREATE TABLE `scenarios` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(190) NOT NULL,
  `description` VARCHAR(500) DEFAULT NULL,
  `image_path` VARCHAR(255) DEFAULT NULL,
  `image_alt` VARCHAR(255) DEFAULT NULL,
  `is_text_card` TINYINT(1) NOT NULL DEFAULT 0,
  `icon` VARCHAR(80) DEFAULT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- references_logos (Marka logo bandi)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `references_logos`;
CREATE TABLE `references_logos` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `logo_path` VARCHAR(255) DEFAULT NULL,
  `url` VARCHAR(255) DEFAULT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- testimonials (Musteri yorumlari)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `testimonials`;
CREATE TABLE `testimonials` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `role` VARCHAR(190) DEFAULT NULL,
  `company` VARCHAR(190) DEFAULT NULL,
  `content` TEXT NOT NULL,
  `photo_path` VARCHAR(255) DEFAULT NULL,
  `rating` TINYINT UNSIGNED NOT NULL DEFAULT 5,
  `sort_order` INT NOT NULL DEFAULT 0,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- faq
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `faq`;
CREATE TABLE `faq` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `question` VARCHAR(500) NOT NULL,
  `answer` TEXT NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `idx_sort` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- leads (form gonderileri)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `leads`;
CREATE TABLE `leads` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(190) NOT NULL,
  `email` VARCHAR(190) NOT NULL,
  `phone` VARCHAR(40) DEFAULT NULL,
  `company` VARCHAR(190) DEFAULT NULL,
  `employees_range` VARCHAR(40) DEFAULT NULL,
  `position` VARCHAR(120) DEFAULT NULL,
  `message` TEXT,
  `status` ENUM('new','contacted','closed','spam') NOT NULL DEFAULT 'new',
  `notes` TEXT,
  `ip` VARCHAR(45) DEFAULT NULL,
  `user_agent` VARCHAR(500) DEFAULT NULL,
  `referer` VARCHAR(500) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- media (uploads)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `media`;
CREATE TABLE `media` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `filename` VARCHAR(255) NOT NULL,
  `path` VARCHAR(500) NOT NULL,
  `mime` VARCHAR(80) DEFAULT NULL,
  `size` INT UNSIGNED DEFAULT NULL,
  `width` INT UNSIGNED DEFAULT NULL,
  `height` INT UNSIGNED DEFAULT NULL,
  `uploaded_by` INT UNSIGNED DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- audit_log
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `audit_log`;
CREATE TABLE `audit_log` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED DEFAULT NULL,
  `action` VARCHAR(80) NOT NULL,
  `target_table` VARCHAR(80) DEFAULT NULL,
  `target_id` INT UNSIGNED DEFAULT NULL,
  `details` TEXT,
  `ip` VARCHAR(45) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------
-- login_attempts (rate limit)
-- -------------------------------------------------------------
DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE `login_attempts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip` VARCHAR(45) NOT NULL,
  `username` VARCHAR(64) DEFAULT NULL,
  `success` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_ip_time` (`ip`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- NOT: v2+ tablolari (pages, team_members, blog_*) artik db/migrations/ altinda
-- Admin -> "Veritabani Guncellemeleri" sayfasindan tek tikla uygulanir.
