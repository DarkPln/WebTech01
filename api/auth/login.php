<?php
session_start();
header('Content-Type: application/json');
require_once '../../db.php';

$input    = json_decode(file_get_contents('php://input'), true) ?? [];
$username = trim($input['username'] ?? '');
$password = $input['password'] ?? '';

// Hardcoded Admin (keine DB-Zeile nötig)
if ($username === 'admin' && $password === 'Admin1234') {
    $_SESSION['user_id']  = 0;
    $_SESSION['username'] = 'admin';
    $_SESSION['is_admin'] = true;
    echo json_encode(['success' => true, 'isAdmin' => true, 'username' => 'admin']);
    exit;
}

// Demo-Nutzer
if ($username === 'TestUser' && $password === 'TestPass123') {
    $_SESSION['user_id']  = -1;
    $_SESSION['username'] = 'TestUser';
    $_SESSION['is_admin'] = false;
    echo json_encode(['success' => true, 'isAdmin' => false, 'username' => 'TestUser']);
    exit;
}

$db   = getDB();
$user = $db->query("SELECT id, username, password, is_locked, is_admin FROM users WHERE username = '" . $db->real_escape_string($username) . "'")->fetch_assoc();

if (!$user || $user['password'] !== $password) {
    echo json_encode(['success' => false, 'message' => 'Falscher Benutzername oder Passwort']);
    exit;
}

if ($user['is_locked']) {
    echo json_encode(['success' => false, 'message' => 'Ihr Konto ist vom Administrator gesperrt']);
    exit;
}

$_SESSION['user_id']  = (int)$user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['is_admin'] = (bool)$user['is_admin'];

echo json_encode(['success' => true, 'isAdmin' => (bool)$user['is_admin'], 'username' => $user['username']]);
