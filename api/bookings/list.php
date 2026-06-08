<?php
session_start();
header('Content-Type: application/json');
require_once '../../db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'bookings' => []]);
    exit;
}

$stmt = getDB()->prepare(
    'SELECT booking_key AS id, car_id AS carId, car_name AS carName, car_price AS carPrice,
            status, reason, created_at AS createdAt, updated_at AS updatedAt
     FROM bookings
     WHERE user_id = ?
     ORDER BY created_at DESC'
);
$stmt->execute([$_SESSION['user_id']]);

echo json_encode(['success' => true, 'bookings' => $stmt->fetchAll()]);
