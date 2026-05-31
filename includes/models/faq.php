<?php
/**
 * FAQ model.
 */

function faq_active(?int $limit = null): array
{
    $sql = 'SELECT * FROM faq WHERE active = 1 ORDER BY sort_order, id';
    if ($limit !== null && $limit > 0) {
        $sql .= ' LIMIT ' . (int) $limit;
    }
    return db()->query($sql)->fetchAll();
}

function faq_all(): array
{
    return db()->query('SELECT * FROM faq ORDER BY sort_order, id')->fetchAll();
}

function faq_get(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM faq WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function faq_create(array $d): int
{
    $stmt = db()->prepare('INSERT INTO faq (question, answer, sort_order, active) VALUES (?, ?, ?, ?)');
    $stmt->execute([
        $d['question'] ?? '',
        $d['answer'] ?? '',
        (int) ($d['sort_order'] ?? 0),
        !empty($d['active']) ? 1 : 0,
    ]);
    return (int) db()->lastInsertId();
}

function faq_update(int $id, array $d): void
{
    $stmt = db()->prepare('UPDATE faq SET question=?, answer=?, sort_order=?, active=? WHERE id=?');
    $stmt->execute([
        $d['question'] ?? '',
        $d['answer'] ?? '',
        (int) ($d['sort_order'] ?? 0),
        !empty($d['active']) ? 1 : 0,
        $id,
    ]);
}

function faq_delete(int $id): void
{
    db()->prepare('DELETE FROM faq WHERE id = ?')->execute([$id]);
}
