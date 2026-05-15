<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403); echo json_encode(['error' => 'Forbidden']); exit;
}
require '../includes/db.php';
header('Content-Type: application/json');

// Total visits
$total = $pdo->query('SELECT COUNT(*) FROM page_visits')->fetchColumn();

// Visits per page
$perPage = $pdo->query('
    SELECT page, COUNT(*) AS visits
    FROM page_visits
    GROUP BY page
    ORDER BY visits DESC
')->fetchAll(PDO::FETCH_ASSOC);

// Visits per day (last 14 days)
$perDay = $pdo->query('
    SELECT DATE(visited_at) AS day, COUNT(*) AS visits
    FROM page_visits
    WHERE visited_at >= DATE_SUB(NOW(), INTERVAL 14 DAY)
    GROUP BY DATE(visited_at)
    ORDER BY day ASC
')->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(compact('total', 'perPage', 'perDay'));
