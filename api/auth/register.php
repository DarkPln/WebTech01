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

$db    = getDB();
$eUser = mysqli_real_escape_string($db, $username);
$ePass = mysqli_real_escape_string($db, $password);

$chkRes = mysqli_query($db, "SELECT id FROM users WHERE username = '$eUser'");
if (mysqli_fetch_assoc($chkRes)) {
    echo json_encode(['success' => false, 'message' => 'Benutzername bereits vergeben']);
    exit;
}

mysqli_query($db, "INSERT INTO users (username, password) VALUES ('$eUser', '$ePass')");

echo json_encode(['success' => true]);
