<?php

declare(strict_types=1);
require __DIR__ . '/bootstrap.php';
require_login();

$db = database();
$summary = $db->query("SELECT COALESCE(SUM(balance), 0) AS total_receivables, COALESCE(SUM(CASE WHEN status = 'unpaid' THEN balance ELSE 0 END), 0) AS unpaid, COALESCE(SUM(CASE WHEN due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) AND balance > 0 THEN balance ELSE 0 END), 0) AS near_due, COALESCE(SUM(CASE WHEN status = 'paid' THEN total_amount ELSE 0 END), 0) AS paid FROM receivables")->fetch();
$branches = $db->query("SELECT b.id, b.name, COALESCE(SUM(r.balance), 0) AS receivables, COUNT(DISTINCT r.customer_id) AS customer_count FROM branches b LEFT JOIN receivables r ON r.branch_id = b.id GROUP BY b.id, b.name ORDER BY b.name")->fetchAll();
$recent = $db->query("SELECT r.id, c.name AS customer, b.name AS branch, e.name AS event, r.invoice_date, r.total_amount, r.balance, r.due_date, r.status FROM receivables r JOIN customers c ON c.id = r.customer_id JOIN branches b ON b.id = r.branch_id JOIN events e ON e.id = r.event_id ORDER BY r.invoice_date DESC, r.id DESC LIMIT 10")->fetchAll();

respond(['success' => true, 'summary' => $summary, 'branches' => $branches, 'recent_receivables' => $recent]);
