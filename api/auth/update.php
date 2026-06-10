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
$newPassword = $input['password']  ?? '';
$newEmail    = trim($input['email'] ?? '');
$newPhone    = trim($input['phone'] ?? '');
$newCity     = trim($input['city']  ?? '');

if (strlen($newUsername) < 5) {
    echo json_encode(['success' => false, 'message' => 'Benutzername zu kurz (min. 5 Zeichen)']);
    exit;
}

if ($newPassword !== '' && strlen($newPassword) < 10) {
    echo json_encode(['success' => false, 'message' => 'Passwort zu kurz (min. 10 Zeichen)']);
    exit;
}

$db     = getDB();
$userId = (int)$_SESSION['user_id'];
$eUser  = mysqli_real_escape_string($db, $newUsername);
$eEmail = mysqli_real_escape_string($db, $newEmail);
$ePhone = mysqli_real_escape_string($db, $newPhone);
$eCity  = mysqli_real_escape_string($db, $newCity);

$chkRes = mysqli_query($db, "SELECT id FROM users WHERE username = '$eUser' AND id != $userId");
if (mysqli_fetch_assoc($chkRes)) {
    echo json_encode(['success' => false, 'message' => 'Benutzername bereits vergeben']);
    exit;
}

$emailVal = $newEmail !== '' ? "'$eEmail'" : 'NULL';
$phoneVal = $newPhone !== '' ? "'$ePhone'" : 'NULL';
$cityVal  = $newCity  !== '' ? "'$eCity'"  : 'NULL';

if ($newPassword !== '') {
    $ePass = mysqli_real_escape_string($db, $newPassword);
    mysqli_query($db, "UPDATE users SET username = '$eUser', password = '$ePass', email = $emailVal, phone = $phoneVal, city = $cityVal WHERE id = $userId");
} else {
    mysqli_query($db, "UPDATE users SET username = '$eUser', email = $emailVal, phone = $phoneVal, city = $cityVal WHERE id = $userId");
}

$_SESSION['username'] = $newUsername;
echo json_encode(['success' => true, 'username' => $newUsername]);
