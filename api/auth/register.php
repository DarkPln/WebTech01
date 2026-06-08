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

$db->prepare('INSERT INTO users (username, password) VALUES (?, ?)')->execute([$username, $password]);

echo json_encode(['success' => true]);
