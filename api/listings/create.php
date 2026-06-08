<?php
session_start();
header('Content-Type: application/json');
require_once '../../db.php';

$input    = json_decode(file_get_contents('php://input'), true) ?? [];
$userId   = $_SESSION['user_id'] ?? null;
$username = $_SESSION['username'] ?? 'Gast';

$key      = 'i_' . time() . '_' . bin2hex(random_bytes(3));
$make     = $input['make']      ?? '';
$model    = $input['model']     ?? '';
$year     = $input['year']      ?? '';
$km       = $input['km']        ?? '';
$fuel     = $input['fuel']      ?? '';
$gearbox  = $input['gearbox']   ?? '';
$power    = $input['power']     ?? '';
$type     = $input['type']      ?? '';
$cond     = $input['condition'] ?? '';
$price    = (float)($input['price'] ?? 0);
$desc     = $input['desc']      ?? '';
$name     = $input['name']      ?? '';
$email    = $input['email']     ?? '';
$phone    = $input['phone']     ?? '';

$stmt = getDB()->prepare(
    'INSERT INTO listings
        (listing_key, user_id, username, make, model, year, km, fuel, gearbox, power, type, cond, price, description, contact_name, email, phone)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
);
$stmt->bind_param(
    'si' . str_repeat('s', 10) . 'd' . str_repeat('s', 4),
    $key, $userId, $username, $make, $model, $year, $km, $fuel, $gearbox, $power, $type, $cond, $price, $desc, $name, $email, $phone
);
$stmt->execute();

echo json_encode(['success' => true, 'id' => $key]);
