<?php
session_start();
header('Content-Type: application/json');
require_once '../../db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'listings' => []]);
    exit;
}

$uid      = $_SESSION['user_id'];
$uname    = $_SESSION['username'];
$stmt = getDB()->prepare(
    'SELECT listing_key AS id, username AS userId, make, model, year, price, status, created_at AS createdAt
     FROM listings
     WHERE user_id = ? OR username = ?
     ORDER BY created_at DESC'
);
$stmt->bind_param('is', $uid, $uname);
$stmt->execute();

echo json_encode(['success' => true, 'listings' => $stmt->get_result()->fetch_all(MYSQLI_ASSOC)]);
