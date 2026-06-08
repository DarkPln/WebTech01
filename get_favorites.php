<?php
session_start();
header('Content-Type: application/json');
require_once 'db.php';

$userId = $_SESSION['user_id'] ?? null;
$useDB  = ($userId !== null && $userId > 0);

if ($useDB) {
    $stmt = getDB()->prepare('SELECT car_id FROM favorites WHERE user_id = ?');
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $ids = array_column($stmt->get_result()->fetch_all(MYSQLI_ASSOC), 'car_id');
} else {
    if (!isset($_SESSION['favorites'])) $_SESSION['favorites'] = [];
    $ids = array_values($_SESSION['favorites']);
}

$cars = [];
if (!empty($ids)) {
    $intIds       = array_map('intval', $ids);
    $placeholders = implode(',', array_fill(0, count($intIds), '?'));
    $types        = str_repeat('i', count($intIds));
    $stmt = getDB()->prepare("SELECT iid, marke, modell, baujahr, kraftstoff, kilometerstand, preis, imagepath FROM cars WHERE iid IN ($placeholders)");
    $stmt->bind_param($types, ...$intIds);
    $stmt->execute();
    $cars = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

echo json_encode(['success' => true, 'favorites' => $ids, 'cars' => $cars]);
