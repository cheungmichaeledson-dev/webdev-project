<?php
/**
 * login_handler.php
 * Pure PHP logic: session management, login, and registration.
 * Included at the top of login.php before any HTML is output.
 */

require 'includes/db.php';
session_start();

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: home.html');
    exit;
}

$error   = '';
$success = '';
$lastAction = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lastAction = $_POST['action'] ?? 'login';

    // ── LOGIN ──
    if ($lastAction === 'login') {
        $identifier = trim($_POST['identifier'] ?? '');
        $password   = $_POST['password'] ?? '';

        if (empty($identifier) || empty($password)) {
            $error = 'Please fill in all fields.';
        } else {
            $stmt = $pdo->prepare(
                'SELECT id, username, email, password_hash, role
                 FROM users
                 WHERE username = :id OR email = :id
                 LIMIT 1'
            );
            $stmt->execute([':id' => $identifier]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id']  = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role']     = $user['role'];
                $redirect = ($user['role'] === 'admin') ? 'admin.php' : 'home.html';
                header('Location: ' . $redirect);
                exit;
            } else {
                $error = 'Invalid username/email or password.';
            }
        }

    // ── REGISTER ──
    } elseif ($lastAction === 'register') {
        $username  = trim($_POST['username'] ?? '');
        $email     = trim($_POST['email']    ?? '');
        $password  = $_POST['password']  ?? '';
        $password2 = $_POST['password2'] ?? '';

        if (empty($username) || empty($email) || empty($password) || empty($password2)) {
            $error = 'Please fill in all fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } elseif (strlen($password) < 8) {
            $error = 'Password must be at least 8 characters.';
        } elseif ($password !== $password2) {
            $error = 'Passwords do not match.';
        } else {
            $check = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1');
            $check->execute([$username, $email]);

            if ($check->fetch()) {
                $error = 'Username or email is already taken.';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $ins  = $pdo->prepare(
                    'INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)'
                );
                $ins->execute([$username, $email, $hash]);
                $success = 'Account created! You can now log in.';
            }
        }
    }
}