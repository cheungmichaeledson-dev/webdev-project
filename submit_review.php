<?php
require 'includes/db.php';

// Prevent PHP warnings from breaking our JSON data
ini_set('display_errors', 0);
$response = ['success' => false, 'message' => 'Unknown error'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // TEMPORARY BYPASS: Hardcoding user_id to 1
    $user_id = 1; 
    
    $spot_id = $_POST['spot_id'] ?? null;
    $rating = $_POST['rating'] ?? 0;
    $comment = trim($_POST['comment'] ?? '');

    if ($spot_id && $rating > 0 && !empty($comment)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO reviews (user_id, spot_id, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$user_id, $spot_id, $rating, $comment]);
            
            $new_id = $pdo->lastInsertId();

            $fetchStmt = $pdo->prepare("
                SELECT r.*, u.username, s.name as spot_name
                FROM reviews r
                JOIN users u ON r.user_id = u.id
                JOIN spots s ON r.spot_id = s.id
                WHERE r.id = ?
            ");
            $fetchStmt->execute([$new_id]);
            $newReview = $fetchStmt->fetch();
            
            $response = ['success' => true, 'message' => 'Review added!', 'review' => $newReview];
        } catch (PDOException $e) {
            $response = ['success' => false, 'message' => 'Database error.'];
        }
    } else {
        $response = ['success' => false, 'message' => 'Please fill out all fields.'];
    }
}

// Clean any hidden spaces/errors, then send pure JSON
if (ob_get_length()) ob_clean();
header('Content-Type: application/json');
echo json_encode($response);
exit;
?>