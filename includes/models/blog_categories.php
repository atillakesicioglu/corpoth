<?php
/**
 * blog_categories model.
 */

function blog_cat_active(): array
{
    try {
        return db()->query('SELECT * FROM blog_categories WHERE is_active = 1 ORDER BY sort_order, name')->fetchAll();
    } catch (Throwable $e) {
        error_log('blog_cat_active: ' . $e->getMessage());
        return [];
    }
}

function blog_cat_all(): array
{
    try {
        return db()->query('SELECT * FROM blog_categories ORDER BY sort_order, name')->fetchAll();
    } catch (Throwable $e) {
        error_log('blog_cat_all: ' . $e->getMessage());
        return [];
    }
}

function blog_cat_get_by_slug(string $slug): ?array
{
    try {
        $stmt = db()->prepare('SELECT * FROM blog_categories WHERE slug = ? LIMIT 1');
        $stmt->execute([$slug]);
        return $stmt->fetch() ?: null;
    } catch (Throwable $e) {
        error_log('blog_cat_get_by_slug: ' . $e->getMessage());
        return null;
    }
}

function blog_cat_get(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM blog_categories WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function blog_cat_create(array $d): int
{
    $stmt = db()->prepare('INSERT INTO blog_categories (slug, name, description, sort_order, is_active) VALUES (?,?,?,?,?)');
    $stmt->execute([
        $d['slug'] ?? '',
        $d['name'] ?? '',
        $d['description'] ?? null,
        (int) ($d['sort_order'] ?? 0),
        !empty($d['is_active']) ? 1 : 0,
    ]);
    return (int) db()->lastInsertId();
}

function blog_cat_update(int $id, array $d): void
{
    $stmt = db()->prepare('UPDATE blog_categories SET slug=?, name=?, description=?, sort_order=?, is_active=? WHERE id=?');
    $stmt->execute([
        $d['slug'] ?? '',
        $d['name'] ?? '',
        $d['description'] ?? null,
        (int) ($d['sort_order'] ?? 0),
        !empty($d['is_active']) ? 1 : 0,
        $id,
    ]);
}

function blog_cat_delete(int $id): void
{
    db()->prepare('DELETE FROM blog_categories WHERE id = ?')->execute([$id]);
}
