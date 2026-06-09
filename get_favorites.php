<?php
session_start();
header('Content-Type: application/json');
require_once 'db.php';

// Lukas get favorites 


$userId = $_SESSION['user_id'] ?? null;
$useDB  = ($userId !== null && $userId > 0);

// wenn user eingeloggt ist, dann aus DB, sonst aus Session


if ($useDB) {
    $ids = array_column(getDB()->query("SELECT car_id FROM favorites WHERE user_id = $userId")->fetch_all(MYSQLI_ASSOC), 'car_id');
} else {
    if (!isset($_SESSION['favorites'])) $_SESSION['favorites'] = [];
    $ids = array_values($_SESSION['favorites']);
}

$cars = [];
if (!empty($ids)) {
    $in   = implode(',', array_map('intval', $ids));
    $cars = getDB()->query("SELECT iid, marke, modell, baujahr, kraftstoff, kilometerstand, preis, imagepath FROM cars WHERE iid IN ($in)")->fetch_all(MYSQLI_ASSOC);
}

echo json_encode(['success' => true, 'favorites' => $ids, 'cars' => $cars]);
