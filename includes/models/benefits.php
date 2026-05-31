<?php
/**
 * Benefits model.
 */

function benefits_active(): array
{
    return db()->query('SELECT * FROM benefits WHERE active = 1 ORDER BY sort_order, id')->fetchAll();
}

function benefits_all(): array
{
    return db()->query('SELECT * FROM benefits ORDER BY sort_order, id')->fetchAll();
}

function benefit_get(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM benefits WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function benefit_create(array $d): int
{
    $stmt = db()->prepare('INSERT INTO benefits (icon, title, description, sort_order, active) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([
        $d['icon'] ?? 'psychology',
        $d['title'] ?? '',
        $d['description'] ?? '',
        (int) ($d['sort_order'] ?? 0),
        !empty($d['active']) ? 1 : 0,
    ]);
    return (int) db()->lastInsertId();
}

function benefit_update(int $id, array $d): void
{
    $stmt = db()->prepare('UPDATE benefits SET icon=?, title=?, description=?, sort_order=?, active=? WHERE id=?');
    $stmt->execute([
        $d['icon'] ?? 'psychology',
        $d['title'] ?? '',
        $d['description'] ?? '',
        (int) ($d['sort_order'] ?? 0),
        !empty($d['active']) ? 1 : 0,
        $id,
    ]);
}

function benefit_delete(int $id): void
{
    db()->prepare('DELETE FROM benefits WHERE id = ?')->execute([$id]);
}
