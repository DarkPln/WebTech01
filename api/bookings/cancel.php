<?php
session_start();
header('Content-Type: application/json');
require_once '../../db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Nicht eingeloggt']);
    exit;
}

$input      = json_decode(file_get_contents('php://input'), true) ?? [];
$bookingKey = $input['id'] ?? '';

$stmt = getDB()->prepare(
    'UPDATE bookings SET status = "storniert", updated_at = NOW()
     WHERE booking_key = ? AND user_id = ? AND status = "bestellt"'
);
$stmt->execute([$bookingKey, $_SESSION['user_id']]);

if ($stmt->rowCount() === 0) {
    echo json_encode(['success' => false, 'message' => 'Buchung nicht gefunden oder nicht stornierbar']);
    exit;
}

echo json_encode(['success' => true]);
