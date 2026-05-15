<?php
// api/fetch_reviews.php
require '../includes/db.php';
ini_set('display_errors', 0);

try {
    // 1. Fetch the 20 most recent reviews
    $stmt = $pdo->query("
        SELECT r.*, u.username, s.name as spot_name
        FROM reviews r
        JOIN users u ON r.user_id = u.id
        JOIN spots s ON r.spot_id = s.id
        ORDER BY r.created_at DESC
        LIMIT 20
    ");
    $reviews = $stmt->fetchAll();

    // 2. Fetch the live totals for the Stats Bar
    $totalSpots = $pdo->query("SELECT COUNT(*) FROM spots")->fetchColumn();
    $totalReviews = $pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
    $totalVotes = $pdo->query("SELECT COUNT(*) FROM votes")->fetchColumn();
    $totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

    if (ob_get_length()) ob_clean();
    header('Content-Type: application/json');
    
    // 3. Send BOTH the reviews and the stats back to the browser
    echo json_encode([
        'success' => true, 
        'reviews' => $reviews,
        'stats' => [
            'spots' => $totalSpots,
            'reviews' => $totalReviews,
            'votes' => $totalVotes,
            'users' => $totalUsers
        ]
    ]);

} catch (PDOException $e) {
    if (ob_get_length()) ob_clean();
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>