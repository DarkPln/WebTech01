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
    $result = $db->query('SELECT username, is_locked AS locked FROM users ORDER BY created_at ASC');
    echo json_encode(['success' => true, 'users' => $result->fetch_all(MYSQLI_ASSOC)]);
} else {
    $input    = json_decode(file_get_contents('php://input'), true) ?? [];
    $username = $input['username'] ?? '';

    $stmt = $db->prepare('UPDATE users SET is_locked = NOT is_locked WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();

    echo json_encode(['success' => true]);
}
