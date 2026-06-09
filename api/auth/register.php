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

$db       = getDB();
$eUser    = $db->real_escape_string($username);
$ePass    = $db->real_escape_string($password);

if ($db->query("SELECT id FROM users WHERE username = '$eUser'")->fetch_assoc()) {
    echo json_encode(['success' => false, 'message' => 'Benutzername bereits vergeben']);
    exit;
}

$db->query("INSERT INTO users (username, password) VALUES ('$eUser', '$ePass')");

echo json_encode(['success' => true]);
