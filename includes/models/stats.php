<?php
/**
 * Stats model.
 */

function stats_active(): array
{
    return db()->query('SELECT * FROM stats WHERE active = 1 ORDER BY sort_order, id')->fetchAll();
}

function stats_all(): array
{
    return db()->query('SELECT * FROM stats ORDER BY sort_order, id')->fetchAll();
}

function stat_get(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM stats WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function stat_create(array $d): int
{
    $stmt = db()->prepare('INSERT INTO stats (icon, value, label, count_to, count_prefix, count_suffix, sort_order, active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([
        $d['icon'] ?? 'trending_up',
        $d['value'] ?? '',
        $d['label'] ?? '',
        isset($d['count_to']) && $d['count_to'] !== '' ? (int) $d['count_to'] : null,
        $d['count_prefix'] ?? null,
        $d['count_suffix'] ?? null,
        (int) ($d['sort_order'] ?? 0),
        !empty($d['active']) ? 1 : 0,
    ]);
    return (int) db()->lastInsertId();
}

function stat_update(int $id, array $d): void
{
    $stmt = db()->prepare('UPDATE stats SET icon=?, value=?, label=?, count_to=?, count_prefix=?, count_suffix=?, sort_order=?, active=? WHERE id=?');
    $stmt->execute([
        $d['icon'] ?? 'trending_up',
        $d['value'] ?? '',
        $d['label'] ?? '',
        isset($d['count_to']) && $d['count_to'] !== '' ? (int) $d['count_to'] : null,
        $d['count_prefix'] ?? null,
        $d['count_suffix'] ?? null,
        (int) ($d['sort_order'] ?? 0),
        !empty($d['active']) ? 1 : 0,
        $id,
    ]);
}

function stat_delete(int $id): void
{
    db()->prepare('DELETE FROM stats WHERE id = ?')->execute([$id]);
}
