<?php
session_start();
header('Content-Type: application/json');
require_once '../../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] <= 0) {
    echo json_encode(['count' => 0]);
    exit;
}

$userId = (int)$_SESSION['user_id'];
$db     = getDB();
$res    = mysqli_query($db, "SELECT COUNT(*) AS cnt FROM messages WHERE user_id = $userId AND is_read = 0");
$row    = mysqli_fetch_assoc($res);
echo json_encode(['count' => (int)($row['cnt'] ?? 0)]);
