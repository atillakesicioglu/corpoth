<?php
/**
 * blog_posts model.
 */

/**
 * Yayinlanmis yazilar - sayfalama destekli.
 */
function blog_published(int $page = 1, int $perPage = 12, ?int $categoryId = null): array
{
    try {
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;
        $sql = 'SELECT p.*, c.name AS category_name, c.slug AS category_slug
                FROM blog_posts p
                LEFT JOIN blog_categories c ON p.category_id = c.id
                WHERE p.status = "published" AND (p.published_at IS NULL OR p.published_at <= NOW())';
        $params = [];
        if ($categoryId) {
            $sql .= ' AND p.category_id = ?';
            $params[] = $categoryId;
        }
        $sql .= ' ORDER BY p.published_at DESC, p.id DESC LIMIT ' . (int)$perPage . ' OFFSET ' . (int)$offset;
        $stmt = db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (Throwable $e) {
        error_log('blog_published: ' . $e->getMessage());
        return [];
    }
}

function blog_count(?int $categoryId = null): int
{
    try {
        $sql = 'SELECT COUNT(*) FROM blog_posts WHERE status = "published" AND (published_at IS NULL OR published_at <= NOW())';
        $params = [];
        if ($categoryId) {
            $sql .= ' AND category_id = ?';
            $params[] = $categoryId;
        }
        $stmt = db()->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    } catch (Throwable $e) {
        error_log('blog_count: ' . $e->getMessage());
        return 0;
    }
}

/**
 * Anasayfa one cikan: en yeni N yazi.
 */
function blog_featured(int $limit = 3): array
{
    try {
        $stmt = db()->prepare('SELECT p.*, c.name AS category_name, c.slug AS category_slug
                               FROM blog_posts p
                               LEFT JOIN blog_categories c ON p.category_id = c.id
                               WHERE p.status = "published" AND (p.published_at IS NULL OR p.published_at <= NOW())
                               ORDER BY p.published_at DESC, p.id DESC
                               LIMIT ' . (int)$limit);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (Throwable $e) {
        error_log('blog_featured: ' . $e->getMessage());
        return [];
    }
}

/**
 * Tek yazi (slug ile).
 */
function blog_get_by_slug(string $slug): ?array
{
    try {
        $stmt = db()->prepare('SELECT p.*, c.name AS category_name, c.slug AS category_slug
                               FROM blog_posts p
                               LEFT JOIN blog_categories c ON p.category_id = c.id
                               WHERE p.slug = ? LIMIT 1');
        $stmt->execute([$slug]);
        return $stmt->fetch() ?: null;
    } catch (Throwable $e) {
        error_log('blog_get_by_slug: ' . $e->getMessage());
        return null;
    }
}

function blog_get(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM blog_posts WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

/**
 * Yazi ile iliskili (ayni kategori veya en yeni) baska 3 yazi.
 */
function blog_related(int $excludeId, ?int $categoryId = null, int $limit = 3): array
{
    $sql = 'SELECT p.*, c.name AS category_name, c.slug AS category_slug
            FROM blog_posts p
            LEFT JOIN blog_categories c ON p.category_id = c.id
            WHERE p.status = "published" AND p.id != ?
              AND (p.published_at IS NULL OR p.published_at <= NOW())';
    $params = [$excludeId];
    if ($categoryId) {
        $sql .= ' AND p.category_id = ?';
        $params[] = $categoryId;
    }
    $sql .= ' ORDER BY p.published_at DESC LIMIT ' . (int)$limit;
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function blog_view_increment(int $id): void
{
    try {
        db()->prepare('UPDATE blog_posts SET view_count = view_count + 1 WHERE id = ?')->execute([$id]);
    } catch (Throwable $e) {
        error_log('blog_view_increment: ' . $e->getMessage());
    }
}

function blog_admin_list(): array
{
    return db()->query('SELECT p.*, c.name AS category_name FROM blog_posts p LEFT JOIN blog_categories c ON p.category_id = c.id ORDER BY p.created_at DESC')->fetchAll();
}

function blog_create(array $d): int
{
    $stmt = db()->prepare('INSERT INTO blog_posts (slug, title, excerpt, content_html, cover_image, category_id, author_name, tags, status, published_at, meta_title, meta_description, og_image) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)');
    $stmt->execute([
        $d['slug'] ?? '',
        $d['title'] ?? '',
        $d['excerpt'] ?? null,
        $d['content_html'] ?? null,
        $d['cover_image'] ?? null,
        !empty($d['category_id']) ? (int)$d['category_id'] : null,
        $d['author_name'] ?? null,
        $d['tags'] ?? null,
        $d['status'] ?? 'draft',
        !empty($d['published_at']) ? $d['published_at'] : null,
        $d['meta_title'] ?? null,
        $d['meta_description'] ?? null,
        $d['og_image'] ?? null,
    ]);
    return (int) db()->lastInsertId();
}

function blog_update(int $id, array $d): void
{
    $stmt = db()->prepare('UPDATE blog_posts SET slug=?, title=?, excerpt=?, content_html=?, cover_image=?, category_id=?, author_name=?, tags=?, status=?, published_at=?, meta_title=?, meta_description=?, og_image=? WHERE id=?');
    $stmt->execute([
        $d['slug'] ?? '',
        $d['title'] ?? '',
        $d['excerpt'] ?? null,
        $d['content_html'] ?? null,
        $d['cover_image'] ?? null,
        !empty($d['category_id']) ? (int)$d['category_id'] : null,
        $d['author_name'] ?? null,
        $d['tags'] ?? null,
        $d['status'] ?? 'draft',
        !empty($d['published_at']) ? $d['published_at'] : null,
        $d['meta_title'] ?? null,
        $d['meta_description'] ?? null,
        $d['og_image'] ?? null,
        $id,
    ]);
}

function blog_delete(int $id): void
{
    db()->prepare('DELETE FROM blog_posts WHERE id = ?')->execute([$id]);
}

/**
 * Slug uretici - turkce karakterleri sadelestirir.
 */
function blog_slugify(string $text): string
{
    $text = trim($text);
    $tr = ['ş','Ş','ı','İ','ğ','Ğ','ü','Ü','ö','Ö','ç','Ç'];
    $en = ['s','s','i','i','g','g','u','u','o','o','c','c'];
    $text = str_replace($tr, $en, $text);
    $text = mb_strtolower($text, 'UTF-8');
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}
