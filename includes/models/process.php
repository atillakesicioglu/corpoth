<?php
/**
 * Process steps ("Nasil Calisir?") model.
 */

function process_active(): array
{
    return db()->query('SELECT * FROM process_steps WHERE active = 1 ORDER BY sort_order, step_number, id')->fetchAll();
}

function process_all(): array
{
    return db()->query('SELECT * FROM process_steps ORDER BY sort_order, step_number, id')->fetchAll();
}

function process_get(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM process_steps WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function process_create(array $d): int
{
    $stmt = db()->prepare('INSERT INTO process_steps (step_number, title, description, sort_order, active) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([
        (int) ($d['step_number'] ?? 1),
        $d['title'] ?? '',
        $d['description'] ?? '',
        (int) ($d['sort_order'] ?? 0),
        !empty($d['active']) ? 1 : 0,
    ]);
    return (int) db()->lastInsertId();
}

function process_update(int $id, array $d): void
{
    $stmt = db()->prepare('UPDATE process_steps SET step_number=?, title=?, description=?, sort_order=?, active=? WHERE id=?');
    $stmt->execute([
        (int) ($d['step_number'] ?? 1),
        $d['title'] ?? '',
        $d['description'] ?? '',
        (int) ($d['sort_order'] ?? 0),
        !empty($d['active']) ? 1 : 0,
        $id,
    ]);
}

function process_delete(int $id): void
{
    db()->prepare('DELETE FROM process_steps WHERE id = ?')->execute([$id]);
}
