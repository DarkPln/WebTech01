<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['loggedIn' => false]);
    exit;
}

$isLocked = false;
$email    = null;
$phone    = null;
$city     = null;

if ($_SESSION['user_id'] > 0) {
    require_once '../../db.php';
    $userId = (int)$_SESSION['user_id'];
    $db     = getDB();
    $res    = mysqli_query($db, "SELECT is_locked, email, phone, city FROM users WHERE id = $userId");
    $row    = mysqli_fetch_assoc($res);
    if ($row) {
        $isLocked = (bool)$row['is_locked'];
        $email    = $row['email'];
        $phone    = $row['phone'];
        $city     = $row['city'];
    }
}

echo json_encode([
    'loggedIn' => true,
    'username' => $_SESSION['username'],
    'isAdmin'  => (bool)($_SESSION['is_admin'] ?? false),
    'isLocked' => $isLocked,
    'email'    => $email,
    'phone'    => $phone,
    'city'     => $city,
]);
