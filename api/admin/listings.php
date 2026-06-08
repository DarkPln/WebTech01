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
        'SELECT listing_key AS id, username AS userId, contact_name AS name,
                make, model, year, price, km, fuel, type, cond AS `condition`,
                description AS `desc`, status, created_at AS createdAt
         FROM listings
         ORDER BY created_at DESC'
    );
    echo json_encode(['success' => true, 'listings' => $result->fetch_all(MYSQLI_ASSOC)]);
} else {
    $input  = json_decode(file_get_contents('php://input'), true) ?? [];
    $key    = $input['id']     ?? '';
    $status = $input['status'] ?? '';

    $stmt = $db->prepare('UPDATE listings SET status = ? WHERE listing_key = ?');
    $stmt->bind_param('ss', $status, $key);
    $stmt->execute();

    echo json_encode(['success' => true]);
}
