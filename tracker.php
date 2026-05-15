<?php
/**
 * tracker.php
 * Include this at the top of any page you want to track.
 * Logs one visit per session per page to avoid refresh inflation.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/includes/db.php';

// Determine the page name from the calling file
$page = basename($_SERVER['PHP_SELF'], '.php');
$page = basename($page, '.html');
$pdo->prepare(
    'INSERT INTO page_visits (page_name) VALUES (?)'
)->execute([$page]);
// Only count once per session per page
$sessionKey = 'visited_' . $page;
if (empty($_SESSION[$sessionKey])) {
    $_SESSION[$sessionKey] = true;
    try {
        $pdo->prepare(
            'INSERT INTO page_visits (page, session_id) VALUES (?, ?)'
        )->execute([$page, session_id()]);
    } catch (PDOException $e) {
        // Silently fail — never break the page because of tracking
    }
}
?>
