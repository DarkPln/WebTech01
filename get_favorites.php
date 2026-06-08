<?php
session_start();
header('Content-Type: application/json');

$userId = $_SESSION['user_id'] ?? null;
$useDB  = ($userId !== null && $userId > 0);

if ($useDB) {
    require_once 'db.php';
    $stmt = getDB()->prepare('SELECT car_id FROM favorites WHERE user_id = ?');
    $stmt->execute([$userId]);
    $favorites = array_column($stmt->fetchAll(), 'car_id');
    echo json_encode(['success' => true, 'favorites' => $favorites]);
} else {
    if (!isset($_SESSION['favorites'])) {
        $_SESSION['favorites'] = [];
    }
    echo json_encode(['success' => true, 'favorites' => array_values($_SESSION['favorites'])]);
}
