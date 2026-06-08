<?php
session_start();
header('Content-Type: application/json');
require_once '../../db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'listings' => []]);
    exit;
}

$db    = getDB();
$uid   = $_SESSION['user_id'];
$uname = $db->real_escape_string($_SESSION['username']);

$result = $db->query(
    "SELECT listing_key AS id, username AS userId, make, model, year, price, status, created_at AS createdAt
     FROM listings
     WHERE user_id = $uid OR username = '$uname'
     ORDER BY created_at DESC"
);

echo json_encode(['success' => true, 'listings' => $result->fetch_all(MYSQLI_ASSOC)]);
