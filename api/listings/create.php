<?php
session_start();
header('Content-Type: application/json');
require_once '../../db.php';

$input    = json_decode(file_get_contents('php://input'), true) ?? [];
$userId   = $_SESSION['user_id'] ?? null;
$username = $_SESSION['username'] ?? 'Gast';

$key = 'i_' . time() . '_' . bin2hex(random_bytes(3));
getDB()->prepare(
    'INSERT INTO listings
        (listing_key, user_id, username, make, model, year, km, fuel, gearbox, power, type, cond, price, description, contact_name, email, phone)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
)->execute([
    $key,
    $userId,
    $username,
    $input['make']      ?? '',
    $input['model']     ?? '',
    $input['year']      ?? '',
    $input['km']        ?? '',
    $input['fuel']      ?? '',
    $input['gearbox']   ?? '',
    $input['power']     ?? '',
    $input['type']      ?? '',
    $input['condition'] ?? '',
    (float)($input['price'] ?? 0),
    $input['desc']      ?? '',
    $input['name']      ?? '',
    $input['email']     ?? '',
    $input['phone']     ?? '',
]);

echo json_encode(['success' => true, 'id' => $key]);
