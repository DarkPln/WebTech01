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

    $eStatus = $db->real_escape_string($status);
    $eKey    = $db->real_escape_string($key);
    $db->query("UPDATE listings SET status = '$eStatus' WHERE listing_key = '$eKey'");

    echo json_encode(['success' => true]);
}
