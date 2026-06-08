<?php
session_start();
header('Content-Type: application/json');
require_once '../../db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Nicht eingeloggt']);
    exit;
}

$input    = json_decode(file_get_contents('php://input'), true) ?? [];
$carId    = (int)($input['carId'] ?? 0);
$carName  = trim($input['carName'] ?? '');
$carPrice = (float)($input['carPrice'] ?? 0);

if (!$carId) {
    echo json_encode(['success' => false, 'message' => 'Ungültige Fahrzeug-ID']);
    exit;
}

$db = getDB();

// Konto-Sperre prüfen
if ($_SESSION['user_id'] > 0) {
    $stmt = $db->prepare('SELECT is_locked FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    if ($user && $user['is_locked']) {
        echo json_encode(['success' => false, 'message' => 'Ihr Konto ist vom Administrator gesperrt']);
        exit;
    }
}

$key  = 'b_' . time() . '_' . bin2hex(random_bytes(3));
$db->prepare(
    'INSERT INTO bookings (booking_key, user_id, username, car_id, car_name, car_price) VALUES (?, ?, ?, ?, ?, ?)'
)->execute([$key, $_SESSION['user_id'], $_SESSION['username'], $carId, $carName, $carPrice]);

echo json_encode(['success' => true, 'id' => $key]);
