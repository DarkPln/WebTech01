<?php
session_start();
header('Content-Type: application/json');
require_once 'db.php';

// Lukas get favorites 


$userId = $_SESSION['user_id'] ?? null;
$useDB  = ($userId !== null && $userId > 0);

// wenn user eingeloggt ist, dann aus DB, sonst aus Session


if ($useDB) {
    $db     = getDB();
    $favRes = mysqli_query($db, "SELECT car_id FROM favorites WHERE user_id = $userId");
    $ids    = array_column(mysqli_fetch_all($favRes, MYSQLI_ASSOC), 'car_id');
} else {
    if (!isset($_SESSION['favorites'])) $_SESSION['favorites'] = [];
    $ids = array_values($_SESSION['favorites']);
}

// wenn ids da sind, dann die autos zu den ids raussuchen, sonst leeres array
$cars = [];
if (!empty($ids)) {
    $db   = getDB();
    $in   = implode(',', array_map('intval', $ids));
    $cRes = mysqli_query($db, "SELECT iid, marke, modell, baujahr, kraftstoff, kilometerstand, preis, imagepath FROM cars WHERE iid IN ($in)");
    $cars = mysqli_fetch_all($cRes, MYSQLI_ASSOC);
}

echo json_encode(['success' => true, 'favorites' => $ids, 'cars' => $cars]);
