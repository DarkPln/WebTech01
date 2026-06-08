<?php
session_start();
header('Content-Type: application/json');
require_once '../../db.php';

if (empty($_SESSION['is_admin'])) {
    echo json_encode(['success' => false, 'message' => 'Kein Zugriff']);
    exit;
}

$db     = getDB();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $result = $db->query(
        'SELECT booking_key AS id, username AS userId, car_id AS carId, car_name AS carName,
                car_price AS carPrice, status, reason, created_at AS createdAt, updated_at AS updatedAt
         FROM bookings
         ORDER BY created_at DESC'
    );
    echo json_encode(['success' => true, 'bookings' => $result->fetch_all(MYSQLI_ASSOC)]);
} else {
    $input      = json_decode(file_get_contents('php://input'), true) ?? [];
    $bookingKey = $input['id']     ?? '';
    $status     = $input['status'] ?? '';
    $reason     = $input['reason'] ?? '';

    $stmt = $db->prepare('UPDATE bookings SET status = ?, reason = ?, updated_at = NOW() WHERE booking_key = ?');
    $stmt->bind_param('sss', $status, $reason, $bookingKey);
    $stmt->execute();

    echo json_encode(['success' => true]);
}
