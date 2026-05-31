<?php
/**
 * team_members model.
 */

function team_active(): array
{
    return db()->query('SELECT * FROM team_members WHERE is_active = 1 ORDER BY sort_order, id')->fetchAll();
}

function team_all(): array
{
    return db()->query('SELECT * FROM team_members ORDER BY sort_order, id')->fetchAll();
}

function team_get_by_slug(string $slug): ?array
{
    $stmt = db()->prepare('SELECT * FROM team_members WHERE slug = ? LIMIT 1');
    $stmt->execute([$slug]);
    return $stmt->fetch() ?: null;
}

function team_get(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM team_members WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function team_create(array $d): int
{
    $stmt = db()->prepare('INSERT INTO team_members (slug, full_name, title, bio, bio_long, photo, email, linkedin, phone, sort_order, is_active) VALUES (?,?,?,?,?,?,?,?,?,?,?)');
    $stmt->execute([
        $d['slug'] ?? '',
        $d['full_name'] ?? '',
        $d['title'] ?? null,
        $d['bio'] ?? null,
        $d['bio_long'] ?? null,
        $d['photo'] ?? null,
        $d['email'] ?? null,
        $d['linkedin'] ?? null,
        $d['phone'] ?? null,
        (int) ($d['sort_order'] ?? 0),
        !empty($d['is_active']) ? 1 : 0,
    ]);
    return (int) db()->lastInsertId();
}

function team_update(int $id, array $d): void
{
    $stmt = db()->prepare('UPDATE team_members SET slug=?, full_name=?, title=?, bio=?, bio_long=?, photo=?, email=?, linkedin=?, phone=?, sort_order=?, is_active=? WHERE id=?');
    $stmt->execute([
        $d['slug'] ?? '',
        $d['full_name'] ?? '',
        $d['title'] ?? null,
        $d['bio'] ?? null,
        $d['bio_long'] ?? null,
        $d['photo'] ?? null,
        $d['email'] ?? null,
        $d['linkedin'] ?? null,
        $d['phone'] ?? null,
        (int) ($d['sort_order'] ?? 0),
        !empty($d['is_active']) ? 1 : 0,
        $id,
    ]);
}

function team_delete(int $id): void
{
    db()->prepare('DELETE FROM team_members WHERE id = ?')->execute([$id]);
}
