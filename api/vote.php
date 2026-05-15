<?php
require '../includes/db.php'; 

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

// TEMPORARY BYPASS: Hardcoding user_id to 1
$user_id = 1; 
$spot_id = $data['spot_id'] ?? null;

if (!$spot_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid spot.']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id FROM votes WHERE user_id = ? AND spot_id = ?");
    $stmt->execute([$user_id, $spot_id]);
    $existing_vote = $stmt->fetch();

    if ($existing_vote) {
        $pdo->prepare("DELETE FROM votes WHERE id = ?")->execute([$existing_vote['id']]);
        $message = "Vote removed.";
    } else {
        $pdo->prepare("INSERT INTO votes (user_id, spot_id) VALUES (?, ?)")->execute([$user_id, $spot_id]);
        $message = "Voted successfully!";
    }

    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM votes WHERE spot_id = ?");
    $countStmt->execute([$spot_id]);
    $new_count = $countStmt->fetchColumn();

    echo json_encode([
        'success' => true, 
        'new_count' => $new_count, 
        'message' => $message
    ]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>