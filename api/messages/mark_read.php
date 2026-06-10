<?php
session_start();
header('Content-Type: application/json');
require_once '../../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] <= 0) {
    echo json_encode(['success' => false]);
    exit;
}

$userId = (int)$_SESSION['user_id'];
$db     = getDB();
mysqli_query($db, "UPDATE messages SET is_read = 1 WHERE user_id = $userId");
echo json_encode(['success' => true]);
