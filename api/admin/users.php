<?php
session_start();
header('Content-Type: application/json');
require_once '../../db.php';

if (empty($_SESSION['is_admin'])) {
    echo json_encode(['success' => false, 'message' => 'Kein Zugriff']);
    exit;
}

$db     = getDB();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $stmt = $db->query('SELECT username, is_locked AS locked FROM users ORDER BY created_at ASC');
    echo json_encode(['success' => true, 'users' => $stmt->fetchAll()]);
} else {
    $input    = json_decode(file_get_contents('php://input'), true) ?? [];
    $username = $input['username'] ?? '';

    $db->prepare('UPDATE users SET is_locked = NOT is_locked WHERE username = ?')
       ->execute([$username]);

    echo json_encode(['success' => true]);
}
