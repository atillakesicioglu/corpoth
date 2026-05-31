<?php
/**
 * Corpoth - DB Migrations runner
 * --------------------------------------------------------------
 * db/migrations/ klasoru altindaki *.sql dosyalarini sirayla
 * uygulayan, applied/pending durumunu takip eden basit migration
 * sistemi. Admin -> "Veritabani Guncellemeleri" sayfasi tarafindan
 * kullanilir.
 *
 * Dosya isimlendirme: 0001_aciklama.sql, 0002_aciklama.sql ...
 * Sayfayi her acista migrations tablosu garanti edilir (idempotent).
 */

if (!defined('CORPOTH_MIGRATIONS_DIR')) {
    define('CORPOTH_MIGRATIONS_DIR', CORPOTH_ROOT . '/db/migrations');
}

/** migrations tablosunu garanti et (yoksa olustur). */
function migrations_init(): void
{
    db()->exec(
        'CREATE TABLE IF NOT EXISTS `migrations` (
            `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(190) NOT NULL,
            `applied_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `success` TINYINT(1) NOT NULL DEFAULT 1,
            `statements_count` INT UNSIGNED NOT NULL DEFAULT 0,
            `elapsed_ms` INT UNSIGNED NOT NULL DEFAULT 0,
            `error_message` TEXT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uniq_name` (`name`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );
}

/** Diskdeki tum migration dosyalarinin tam yollari (sortli). */
function migrations_files(): array
{
    if (!is_dir(CORPOTH_MIGRATIONS_DIR)) return [];
    $files = glob(CORPOTH_MIGRATIONS_DIR . '/*.sql') ?: [];
    sort($files, SORT_STRING);
    return $files;
}

/** name => row eslemesi (uygulanmis migration kayitlari). */
function migrations_applied_map(): array
{
    migrations_init();
    $rows = db()->query(
        'SELECT name, applied_at, success, statements_count, elapsed_ms, error_message
           FROM `migrations` ORDER BY id ASC'
    )->fetchAll(PDO::FETCH_ASSOC);

    $map = [];
    foreach ($rows as $r) $map[$r['name']] = $r;
    return $map;
}

/** Henuz basariyla uygulanmamis migration dosyalarinin yollari. */
function migrations_pending(): array
{
    $applied = migrations_applied_map();
    $pending = [];
    foreach (migrations_files() as $f) {
        $name = basename($f);
        $row = $applied[$name] ?? null;
        if ($row === null || (int)$row['success'] !== 1) {
            $pending[] = $f;
        }
    }
    return $pending;
}

/**
 * SQL dosyasini ; ile statement'lara boler.
 * - Tek satirlik -- yorumlari, /* ... *\/ blok yorumlari atilir.
 * - String icindeki ; karakterleri korunur (', ", ` arasinda).
 * - Backslash escape destekli.
 */
function migrations_split_sql(string $sql): array
{
    $sql = preg_replace('/^\s*--[^\n]*$/m', '', $sql);
    $sql = preg_replace('|/\*(?!!)[\s\S]*?\*/|', '', $sql);

    $statements = [];
    $current = '';
    $inString = false;
    $stringChar = '';
    $len = strlen($sql);

    for ($i = 0; $i < $len; $i++) {
        $c = $sql[$i];

        if (!$inString && ($c === '"' || $c === "'" || $c === '`')) {
            $inString = true;
            $stringChar = $c;
            $current .= $c;
            continue;
        }

        if ($inString) {
            $current .= $c;
            if ($c === $stringChar) {
                $bs = 0;
                $j = $i - 1;
                while ($j >= 0 && $sql[$j] === '\\') { $bs++; $j--; }
                if ($bs % 2 === 0) {
                    $inString = false;
                }
            }
            continue;
        }

        if ($c === ';') {
            $stmt = trim($current);
            if ($stmt !== '') $statements[] = $stmt;
            $current = '';
            continue;
        }

        $current .= $c;
    }

    $stmt = trim($current);
    if ($stmt !== '') $statements[] = $stmt;

    return $statements;
}

/**
 * Tek bir migration dosyasini calistirir.
 * Donus: ['success' => bool, 'name', 'statements', 'elapsed_ms', 'error']
 */
function migrations_run_file(string $file): array
{
    migrations_init();
    $name = basename($file);
    $start = microtime(true);

    if (!is_file($file)) {
        return [
            'success' => false, 'name' => $name, 'statements' => 0,
            'elapsed_ms' => 0, 'error' => 'Dosya bulunamadi',
        ];
    }

    $sql = file_get_contents($file);
    if ($sql === false) {
        return [
            'success' => false, 'name' => $name, 'statements' => 0,
            'elapsed_ms' => 0, 'error' => 'Dosya okunamadi',
        ];
    }

    $statements = migrations_split_sql($sql);
    $count = count($statements);
    $pdo = db();

    try {
        foreach ($statements as $i => $stmt) {
            try {
                $pdo->exec($stmt);
            } catch (PDOException $e) {
                throw new RuntimeException(
                    'Statement #' . ($i + 1) . ' - ' . $e->getMessage()
                );
            }
        }

        $elapsed = (int) round((microtime(true) - $start) * 1000);

        $up = $pdo->prepare(
            'INSERT INTO `migrations` (`name`, `success`, `statements_count`, `elapsed_ms`, `error_message`)
             VALUES (?, 1, ?, ?, NULL)
             ON DUPLICATE KEY UPDATE
               `applied_at` = NOW(),
               `success` = 1,
               `statements_count` = VALUES(`statements_count`),
               `elapsed_ms` = VALUES(`elapsed_ms`),
               `error_message` = NULL'
        );
        $up->execute([$name, $count, $elapsed]);

        return [
            'success' => true, 'name' => $name, 'statements' => $count,
            'elapsed_ms' => $elapsed, 'error' => null,
        ];
    } catch (Throwable $e) {
        $elapsed = (int) round((microtime(true) - $start) * 1000);
        $err = $e->getMessage();

        try {
            $up = $pdo->prepare(
                'INSERT INTO `migrations` (`name`, `success`, `statements_count`, `elapsed_ms`, `error_message`)
                 VALUES (?, 0, ?, ?, ?)
                 ON DUPLICATE KEY UPDATE
                   `applied_at` = NOW(),
                   `success` = 0,
                   `statements_count` = VALUES(`statements_count`),
                   `elapsed_ms` = VALUES(`elapsed_ms`),
                   `error_message` = VALUES(`error_message`)'
            );
            $up->execute([$name, $count, $elapsed, $err]);
        } catch (Throwable $_) { /* sessiz - asil hatayi geri don */ }

        return [
            'success' => false, 'name' => $name, 'statements' => $count,
            'elapsed_ms' => $elapsed, 'error' => $err,
        ];
    }
}

/** Bekleyen tum migration'lari sirayla calistirir, ilk hatada durur. */
function migrations_run_all(): array
{
    $results = [];
    foreach (migrations_pending() as $file) {
        $r = migrations_run_file($file);
        $results[] = $r;
        if (!$r['success']) break;
    }
    return $results;
}

/**
 * Migration'i "uygulanmis" olarak isaretler ama dosyayi calistirmaz.
 * Kullanim: kullanici phpMyAdmin'den manuel calistirmissa, sistem bilsin.
 */
function migrations_mark_applied(string $name): void
{
    migrations_init();
    $pdo = db();
    $up = $pdo->prepare(
        'INSERT INTO `migrations` (`name`, `success`, `statements_count`, `elapsed_ms`, `error_message`)
         VALUES (?, 1, 0, 0, NULL)
         ON DUPLICATE KEY UPDATE
           `applied_at` = NOW(),
           `success` = 1,
           `error_message` = NULL'
    );
    $up->execute([$name]);
}

/** name'e karsilik gelen migration dosyasinin tam yolunu donderir (varsa). */
function migrations_resolve_path(string $name): ?string
{
    $name = basename($name);
    if (!preg_match('/^[A-Za-z0-9_\-\.]+\.sql$/', $name)) return null;
    $path = CORPOTH_MIGRATIONS_DIR . '/' . $name;
    return is_file($path) ? $path : null;
}
