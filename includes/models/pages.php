<?php
/**
 * pages model.
 */

function page_get(string $slug): ?array
{
    $stmt = db()->prepare('SELECT * FROM pages WHERE slug = ? LIMIT 1');
    $stmt->execute([$slug]);
    return $stmt->fetch() ?: null;
}

function page_all(): array
{
    return db()->query('SELECT * FROM pages ORDER BY title')->fetchAll();
}

function page_create(array $d): int
{
    $stmt = db()->prepare('INSERT INTO pages (slug, title, hero_eyebrow, hero_subtitle, hero_image, content_html, meta_title, meta_description, og_image, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([
        $d['slug'] ?? '',
        $d['title'] ?? '',
        $d['hero_eyebrow'] ?? null,
        $d['hero_subtitle'] ?? null,
        $d['hero_image'] ?? null,
        $d['content_html'] ?? null,
        $d['meta_title'] ?? null,
        $d['meta_description'] ?? null,
        $d['og_image'] ?? null,
        !empty($d['is_active']) ? 1 : 0,
    ]);
    return (int) db()->lastInsertId();
}

function page_update(int $id, array $d): void
{
    $stmt = db()->prepare('UPDATE pages SET slug=?, title=?, hero_eyebrow=?, hero_subtitle=?, hero_image=?, content_html=?, meta_title=?, meta_description=?, og_image=?, is_active=? WHERE id=?');
    $stmt->execute([
        $d['slug'] ?? '',
        $d['title'] ?? '',
        $d['hero_eyebrow'] ?? null,
        $d['hero_subtitle'] ?? null,
        $d['hero_image'] ?? null,
        $d['content_html'] ?? null,
        $d['meta_title'] ?? null,
        $d['meta_description'] ?? null,
        $d['og_image'] ?? null,
        !empty($d['is_active']) ? 1 : 0,
        $id,
    ]);
}

function page_delete(int $id): void
{
    db()->prepare('DELETE FROM pages WHERE id = ?')->execute([$id]);
}

function page_get_by_id(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM pages WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}
