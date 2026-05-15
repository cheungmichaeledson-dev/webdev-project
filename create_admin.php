<?php
require 'includes/db.php';
$hash = password_hash('BSIT34', PASSWORD_DEFAULT);
$pdo->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, 'admin')")
    ->execute(['admin', 'admin@intraspots.com', $hash]);
echo 'Done';