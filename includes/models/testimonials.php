<?php
/**
 * Testimonials model.
 */

function testimonials_active(): array
{
    return db()->query('SELECT * FROM testimonials WHERE active = 1 ORDER BY sort_order, id')->fetchAll();
}

function testimonials_all(): array
{
    return db()->query('SELECT * FROM testimonials ORDER BY sort_order, id')->fetchAll();
}

function testimonial_get(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM testimonials WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function testimonial_create(array $d): int
{
    $stmt = db()->prepare('INSERT INTO testimonials (name, role, company, content, photo_path, rating, sort_order, active) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([
        $d['name'] ?? '',
        $d['role'] ?? null,
        $d['company'] ?? null,
        $d['content'] ?? '',
        $d['photo_path'] ?? null,
        max(1, min(5, (int) ($d['rating'] ?? 5))),
        (int) ($d['sort_order'] ?? 0),
        !empty($d['active']) ? 1 : 0,
    ]);
    return (int) db()->lastInsertId();
}

function testimonial_update(int $id, array $d): void
{
    $stmt = db()->prepare('UPDATE testimonials SET name=?, role=?, company=?, content=?, photo_path=?, rating=?, sort_order=?, active=? WHERE id=?');
    $stmt->execute([
        $d['name'] ?? '',
        $d['role'] ?? null,
        $d['company'] ?? null,
        $d['content'] ?? '',
        $d['photo_path'] ?? null,
        max(1, min(5, (int) ($d['rating'] ?? 5))),
        (int) ($d['sort_order'] ?? 0),
        !empty($d['active']) ? 1 : 0,
        $id,
    ]);
}

function testimonial_delete(int $id): void
{
    db()->prepare('DELETE FROM testimonials WHERE id = ?')->execute([$id]);
}
