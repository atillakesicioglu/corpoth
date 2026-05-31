<?php
/**
 * Leads (form submissions) model.
 */

function lead_create(array $d): int
{
    $stmt = db()->prepare('INSERT INTO leads (name, email, phone, company, employees_range, position, message, ip, user_agent, referer) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([
        $d['name'] ?? '',
        $d['email'] ?? '',
        $d['phone'] ?? null,
        $d['company'] ?? null,
        $d['employees_range'] ?? null,
        $d['position'] ?? null,
        $d['message'] ?? null,
        $d['ip'] ?? null,
        $d['user_agent'] ?? null,
        $d['referer'] ?? null,
    ]);
    return (int) db()->lastInsertId();
}

function leads_list(array $filters = [], int $limit = 100, int $offset = 0): array
{
    $where = [];
    $params = [];
    if (!empty($filters['status'])) {
        $where[] = 'status = ?';
        $params[] = $filters['status'];
    }
    if (!empty($filters['search'])) {
        $where[] = '(name LIKE ? OR email LIKE ? OR company LIKE ?)';
        $needle = '%' . $filters['search'] . '%';
        array_push($params, $needle, $needle, $needle);
    }
    if (!empty($filters['date_from'])) {
        $where[] = 'created_at >= ?';
        $params[] = $filters['date_from'] . ' 00:00:00';
    }
    if (!empty($filters['date_to'])) {
        $where[] = 'created_at <= ?';
        $params[] = $filters['date_to'] . ' 23:59:59';
    }

    $sql = 'SELECT * FROM leads';
    if ($where) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }
    $sql .= ' ORDER BY created_at DESC LIMIT ' . (int) $limit . ' OFFSET ' . (int) $offset;

    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function leads_count(array $filters = []): int
{
    $where = [];
    $params = [];
    if (!empty($filters['status'])) {
        $where[] = 'status = ?';
        $params[] = $filters['status'];
    }
    $sql = 'SELECT COUNT(*) FROM leads';
    if ($where) {
        $sql .= ' WHERE ' . implode(' AND ', $where);
    }
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return (int) $stmt->fetchColumn();
}

function lead_get(int $id): ?array
{
    $stmt = db()->prepare('SELECT * FROM leads WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function lead_update_status(int $id, string $status, ?string $notes = null): void
{
    if (!in_array($status, ['new','contacted','closed','spam'], true)) {
        return;
    }
    if ($notes !== null) {
        db()->prepare('UPDATE leads SET status=?, notes=? WHERE id=?')->execute([$status, $notes, $id]);
    } else {
        db()->prepare('UPDATE leads SET status=? WHERE id=?')->execute([$status, $id]);
    }
}

function lead_delete(int $id): void
{
    db()->prepare('DELETE FROM leads WHERE id = ?')->execute([$id]);
}

function leads_stats_summary(): array
{
    return [
        'total'      => (int) db()->query('SELECT COUNT(*) FROM leads')->fetchColumn(),
        'new'        => (int) db()->query("SELECT COUNT(*) FROM leads WHERE status = 'new'")->fetchColumn(),
        'contacted'  => (int) db()->query("SELECT COUNT(*) FROM leads WHERE status = 'contacted'")->fetchColumn(),
        'closed'     => (int) db()->query("SELECT COUNT(*) FROM leads WHERE status = 'closed'")->fetchColumn(),
        'last_7days' => (int) db()->query('SELECT COUNT(*) FROM leads WHERE created_at >= NOW() - INTERVAL 7 DAY')->fetchColumn(),
        'today'      => (int) db()->query('SELECT COUNT(*) FROM leads WHERE DATE(created_at) = CURDATE()')->fetchColumn(),
    ];
}
