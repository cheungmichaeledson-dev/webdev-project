<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403); echo json_encode(['error' => 'Forbidden']); exit;
}
require '../includes/db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$data   = json_decode(file_get_contents('php://input'), true) ?? [];

// GET - list all reviews
if ($method === 'GET') {
    $rows = $pdo->query('
        SELECT r.id, r.rating, r.comment, r.admin_reply, r.created_at,
               u.username, s.name AS spot_name, r.spot_id
        FROM reviews r
        JOIN users u ON r.user_id = u.id
        JOIN spots s ON r.spot_id = s.id
        ORDER BY r.created_at DESC
    ')->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows);
    exit;
}

// DELETE
if ($method === 'DELETE') {
    $id = intval($data['id'] ?? 0);
    if (!$id) { echo json_encode(['error' => 'Missing id']); exit; }
    $pdo->prepare('DELETE FROM reviews WHERE id = ?')->execute([$id]);
    echo json_encode(['success' => true]);
    exit;
}

// POST - save admin reply
if ($method === 'POST') {
    $id    = intval($data['id'] ?? 0);
    $reply = trim($data['reply'] ?? '');
    if (!$id) { echo json_encode(['error' => 'Missing id']); exit; }
    $pdo->prepare('UPDATE reviews SET admin_reply = ? WHERE id = ?')->execute([$reply ?: null, $id]);
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['error' => 'Method not allowed']);
