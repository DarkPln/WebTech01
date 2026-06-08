<?php
session_start();
header('Content-Type: application/json');
require_once '../../db.php';

$input    = json_decode(file_get_contents('php://input'), true) ?? [];
$username = trim($input['username'] ?? '');
$password = $input['password'] ?? '';

if (strlen($username) < 5 || strlen($password) < 10) {
    echo json_encode(['success' => false, 'message' => 'Ungültige Eingaben']);
    exit;
}

$db   = getDB();
$stmt = $db->prepare('SELECT id FROM users WHERE username = ?');
$stmt->execute([$username]);

if ($stmt->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Benutzername bereits vergeben']);
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$db->prepare('INSERT INTO users (username, password_hash) VALUES (?, ?)')->execute([$username, $hash]);

echo json_encode(['success' => true]);
