<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403); echo json_encode(['error' => 'Forbidden']); exit;
}
require '../includes/db.php';
header('Content-Type: application/json');

$users    = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$spots    = $pdo->query('SELECT COUNT(*) FROM spots')->fetchColumn();
$reviews  = $pdo->query('SELECT COUNT(*) FROM reviews')->fetchColumn();
$featured = $pdo->query('SELECT COUNT(*) FROM spots WHERE featured = 1')->fetchColumn();

$recent = $pdo->query('
    SELECT r.id, r.rating, r.comment, r.created_at,
           u.username, s.name AS spot_name
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    JOIN spots s ON r.spot_id = s.id
    ORDER BY r.created_at DESC
    LIMIT 5
')->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(compact('users', 'spots', 'reviews', 'featured', 'recent'));
