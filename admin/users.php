<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403); echo json_encode(['error' => 'Forbidden']); exit;
}
require '../includes/db.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$data   = json_decode(file_get_contents('php://input'), true) ?? [];

// GET - list all users
if ($method === 'GET') {
    $rows = $pdo->query('
        SELECT id, username, email, role, created_at,
               (SELECT COUNT(*) FROM reviews WHERE user_id = users.id) AS review_count
        FROM users
        ORDER BY created_at DESC
    ')->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows);
    exit;
}

// POST - change role or ban
if ($method === 'POST') {
    $id     = intval($data['id'] ?? 0);
    $action = $data['action'] ?? '';

    if (!$id) { echo json_encode(['error' => 'Missing id']); exit; }

    // Prevent admin from demoting themselves
    if ($id === intval($_SESSION['user_id'])) {
        echo json_encode(['error' => 'You cannot change your own role.']); exit;
    }

    if ($action === 'promote') {
        $pdo->prepare('UPDATE users SET role = "admin" WHERE id = ?')->execute([$id]);
        echo json_encode(['success' => true, 'role' => 'admin']);
    } elseif ($action === 'demote') {
        $pdo->prepare('UPDATE users SET role = "user" WHERE id = ?')->execute([$id]);
        echo json_encode(['success' => true, 'role' => 'user']);
    } else {
        echo json_encode(['error' => 'Unknown action']);
    }
    exit;
}

// DELETE - delete user account
if ($method === 'DELETE') {
    $id = intval($data['id'] ?? 0);
    if (!$id) { echo json_encode(['error' => 'Missing id']); exit; }
    if ($id === intval($_SESSION['user_id'])) {
        echo json_encode(['error' => 'You cannot delete your own account.']); exit;
    }
    $pdo->prepare('DELETE FROM users WHERE id = ?')->execute([$id]);
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['error' => 'Method not allowed']);
