<?php
/**
 * Settings model (key/value).
 */

function settings_all(): array
{
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }
    $cache = [];
    foreach (db()->query('SELECT * FROM settings ORDER BY group_name, sort_order, key_name') as $row) {
        $cache[$row['key_name']] = $row;
    }
    return $cache;
}

function setting(string $key, ?string $default = null): string
{
    $all = settings_all();
    return isset($all[$key]) ? (string) ($all[$key]['value'] ?? '') : (string) ($default ?? '');
}

function settings_by_group(string $group): array
{
    $rows = [];
    foreach (settings_all() as $row) {
        if ($row['group_name'] === $group) {
            $rows[] = $row;
        }
    }
    return $rows;
}

function settings_groups(): array
{
    $groups = [];
    foreach (settings_all() as $row) {
        $groups[$row['group_name']][] = $row;
    }
    return $groups;
}

function settings_update(array $values): void
{
    $stmt = db()->prepare('UPDATE settings SET value = :value WHERE key_name = :key');
    foreach ($values as $key => $value) {
        $stmt->execute([':key' => $key, ':value' => $value]);
    }
}
