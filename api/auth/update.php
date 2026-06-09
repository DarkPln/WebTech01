<?php
session_start();
header('Content-Type: application/json');
require_once '../../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] <= 0) {
    echo json_encode(['success' => false, 'message' => 'Nicht eingeloggt']);
    exit;
}

$input       = json_decode(file_get_contents('php://input'), true) ?? [];
$newUsername = trim($input['username'] ?? '');
$newPassword = $input['password'] ?? '';

if (strlen($newUsername) < 5 || strlen($newPassword) < 10) {
    echo json_encode(['success' => false, 'message' => 'Ungültige Eingaben']);
    exit;
}

$db     = getDB();
$userId = $_SESSION['user_id'];
$eUser  = $db->real_escape_string($newUsername);
$ePass  = $db->real_escape_string($newPassword);

if ($db->query("SELECT id FROM users WHERE username = '$eUser' AND id != $userId")->fetch_assoc()) {
    echo json_encode(['success' => false, 'message' => 'Benutzername bereits vergeben']);
    exit;
}

$db->query("UPDATE users SET username = '$eUser', password = '$ePass' WHERE id = $userId");

$_SESSION['username'] = $newUsername;
echo json_encode(['success' => true, 'username' => $newUsername]);
