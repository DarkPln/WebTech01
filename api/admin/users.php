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
    $result = mysqli_query($db, 'SELECT username, is_locked AS locked FROM users ORDER BY created_at ASC');
    echo json_encode(['success' => true, 'users' => mysqli_fetch_all($result, MYSQLI_ASSOC)]);
} else {
    $input    = json_decode(file_get_contents('php://input'), true) ?? [];
    $username = $input['username'] ?? '';

    $eUsername = mysqli_real_escape_string($db, $username);
    mysqli_query($db, "UPDATE users SET is_locked = NOT is_locked WHERE username = '$eUsername'");

    echo json_encode(['success' => true]);
}
