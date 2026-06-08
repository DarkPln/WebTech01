<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['loggedIn' => false]);
    exit;
}

$isLocked = false;
if ($_SESSION['user_id'] > 0) {
    require_once '../../db.php';
    $stmt = getDB()->prepare('SELECT is_locked FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $row = $stmt->fetch();
    $isLocked = $row ? (bool)$row['is_locked'] : false;
}

echo json_encode([
    'loggedIn' => true,
    'username' => $_SESSION['username'],
    'isAdmin'  => (bool)($_SESSION['is_admin'] ?? false),
    'isLocked' => $isLocked,
]);
