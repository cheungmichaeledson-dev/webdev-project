<?php
require 'includes/db.php';

ini_set('display_errors', 0);
$response = ['success' => false, 'message' => 'Invalid request'];

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['review_id'])) {
    $review_id = $data['review_id'];
    $user_id = 1; // TEMPORARY BYPASS

    try {
        $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ? AND user_id = ?");
        $stmt->execute([$review_id, $user_id]);

        $response = ['success' => true, 'message' => 'Review deleted'];
    } catch (PDOException $e) {
        $response = ['success' => false, 'message' => 'Database error'];
    }
}

if (ob_get_length()) ob_clean();
header('Content-Type: application/json');
echo json_encode($response);
exit;
?>