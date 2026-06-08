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

$stmt = $db->prepare('SELECT id FROM users WHERE username = ? AND id != ?');
$stmt->bind_param('si', $newUsername, $userId);
$stmt->execute();
if ($stmt->get_result()->fetch_assoc()) {
    echo json_encode(['success' => false, 'message' => 'Benutzername bereits vergeben']);
    exit;
}

$upd = $db->prepare('UPDATE users SET username = ?, password = ? WHERE id = ?');
$upd->bind_param('ssi', $newUsername, $newPassword, $userId);
$upd->execute();

$_SESSION['username'] = $newUsername;
echo json_encode(['success' => true, 'username' => $newUsername]);
