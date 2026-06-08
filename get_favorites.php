<?php
session_start();
header('Content-Type: application/json');
require_once 'db.php';

$userId = $_SESSION['user_id'] ?? null;
$useDB  = ($userId !== null && $userId > 0);

if ($useDB) {
    $stmt = getDB()->prepare('SELECT car_id FROM favorites WHERE user_id = ?');
    $stmt->execute([$userId]);
    $ids = array_column($stmt->fetchAll(), 'car_id');
} else {
    if (!isset($_SESSION['favorites'])) $_SESSION['favorites'] = [];
    $ids = array_values($_SESSION['favorites']);
}

$cars = [];
if (!empty($ids)) {
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = getDB()->prepare("SELECT iid, marke, modell, baujahr, kraftstoff, kilometerstand, preis, imagepath FROM cars WHERE iid IN ($placeholders)");
    $stmt->execute(array_map('intval', $ids));
    $cars = $stmt->fetchAll();
}

echo json_encode(['success' => true, 'favorites' => $ids, 'cars' => $cars]);
