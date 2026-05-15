<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403); echo json_encode(['error' => 'Forbidden']); exit;
}
require '../includes/db.php';

$method = $_SERVER['REQUEST_METHOD'];

/* ── GET: list all spots ── */
if ($method === 'GET') {
    header('Content-Type: application/json');
    $rows = $pdo->query('SELECT * FROM spots ORDER BY name ASC')->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows);
    exit;
}

/* ── POST: create or update (supports multipart for image upload) ── */
if ($method === 'POST') {
    header('Content-Type: application/json');

    // Detect multipart vs JSON
    $isMultipart = isset($_SERVER['CONTENT_TYPE']) &&
                   str_contains($_SERVER['CONTENT_TYPE'], 'multipart/form-data');

    if ($isMultipart) {
        $id          = intval($_POST['id'] ?? 0);
        $name        = trim($_POST['name'] ?? '');
        $category    = trim($_POST['category'] ?? '');
        $address     = trim($_POST['address'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $history     = trim($_POST['history'] ?? '');
        $featured    = isset($_POST['featured']) ? (int)filter_var($_POST['featured'], FILTER_VALIDATE_BOOLEAN) : 0;
    } else {
        $data        = json_decode(file_get_contents('php://input'), true) ?? [];
        $id          = intval($data['id'] ?? 0);
        $name        = trim($data['name'] ?? '');
        $category    = trim($data['category'] ?? '');
        $address     = trim($data['address'] ?? '');
        $description = trim($data['description'] ?? '');
        $history     = trim($data['history'] ?? '');
        $featured    = isset($data['featured']) ? (int)(bool)$data['featured'] : 0;
    }

    if (!$name) { echo json_encode(['error' => 'Name is required']); exit; }

    /* ── Handle image upload ── */
    $imagePath = null;
    if (!empty($_FILES['image']['tmp_name'])) {
        $file    = $_FILES['image'];
        $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $finfo   = finfo_open(FILEINFO_MIME_TYPE);
        $mime    = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $allowed)) {
            echo json_encode(['error' => 'Invalid image type. Use JPG, PNG, WEBP, or GIF.']); exit;
        }
        if ($file['size'] > 5 * 1024 * 1024) {
            echo json_encode(['error' => 'Image must be under 5 MB.']); exit;
        }

        $ext      = match($mime) {
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp',
            'image/gif'  => 'gif',
            default      => 'jpg'
        };
        $filename = 'spot_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $dest     = __DIR__ . '/../images/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            echo json_encode(['error' => 'Failed to save image.']); exit;
        }
        $imagePath = 'images/' . $filename;
    }

    if ($id) {
        // Update existing
        if ($imagePath) {
            // Delete old image if it was a spot_ upload (don't delete original assets)
            $old = $pdo->prepare('SELECT image FROM spots WHERE id = ?');
            $old->execute([$id]);
            $oldImg = $old->fetchColumn();
            if ($oldImg && str_contains(basename($oldImg), 'spot_') && file_exists(__DIR__ . '/../' . $oldImg)) {
                @unlink(__DIR__ . '/../' . $oldImg);
            }
            $pdo->prepare('UPDATE spots SET name=?, category=?, address=?, description=?, history=?, featured=?, image=? WHERE id=?')
                ->execute([$name, $category, $address, $description, $history, $featured, $imagePath, $id]);
        } else {
            $pdo->prepare('UPDATE spots SET name=?, category=?, address=?, description=?, history=?, featured=? WHERE id=?')
                ->execute([$name, $category, $address, $description, $history, $featured, $id]);
        }
        echo json_encode(['success' => true, 'id' => $id]);
    } else {
        // Insert new
        $pdo->prepare('INSERT INTO spots (name, category, address, description, history, featured, image) VALUES (?, ?, ?, ?, ?, ?, ?)')
            ->execute([$name, $category, $address, $description, $history, $featured, $imagePath]);
        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
    }
    exit;
}

/* ── DELETE ── */
if ($method === 'DELETE') {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $id   = intval($data['id'] ?? 0);
    if (!$id) { echo json_encode(['error' => 'Missing id']); exit; }

    // Clean up uploaded image if applicable
    $old = $pdo->prepare('SELECT image FROM spots WHERE id = ?');
    $old->execute([$id]);
    $oldImg = $old->fetchColumn();
    if ($oldImg && str_contains(basename($oldImg), 'spot_') && file_exists(__DIR__ . '/../' . $oldImg)) {
        @unlink(__DIR__ . '/../' . $oldImg);
    }

    $pdo->prepare('DELETE FROM spots WHERE id = ?')->execute([$id]);
    echo json_encode(['success' => true]);
    exit;
}

header('Content-Type: application/json');
echo json_encode(['error' => 'Method not allowed']);
