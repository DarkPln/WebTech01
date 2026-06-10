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
$uname = mysqli_real_escape_string($db, $_SESSION['username']);

$result = mysqli_query($db,
    "SELECT listing_key AS id, username AS userId, make, model, year, price, status, created_at AS createdAt
     FROM listings
     WHERE user_id = $uid OR username = '$uname'
     ORDER BY created_at DESC"
);

echo json_encode(['success' => true, 'listings' => mysqli_fetch_all($result, MYSQLI_ASSOC)]);
