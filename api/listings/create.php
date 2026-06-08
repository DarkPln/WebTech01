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

$db         = getDB();
$userIdSql  = $userId === null ? 'NULL' : (int)$userId;
$eKey       = $db->real_escape_string($key);
$eUsername  = $db->real_escape_string($username);
$eMake      = $db->real_escape_string($make);
$eModel     = $db->real_escape_string($model);
$eYear      = $db->real_escape_string($year);
$eKm        = $db->real_escape_string($km);
$eFuel      = $db->real_escape_string($fuel);
$eGearbox   = $db->real_escape_string($gearbox);
$ePower     = $db->real_escape_string($power);
$eType      = $db->real_escape_string($type);
$eCond      = $db->real_escape_string($cond);
$eDesc      = $db->real_escape_string($desc);
$eName      = $db->real_escape_string($name);
$eEmail     = $db->real_escape_string($email);
$ePhone     = $db->real_escape_string($phone);

$db->query(
    "INSERT INTO listings
        (listing_key, user_id, username, make, model, year, km, fuel, gearbox, power, type, cond, price, description, contact_name, email, phone)
     VALUES ('$eKey', $userIdSql, '$eUsername', '$eMake', '$eModel', '$eYear', '$eKm', '$eFuel', '$eGearbox', '$ePower', '$eType', '$eCond', $price, '$eDesc', '$eName', '$eEmail', '$ePhone')"
);

echo json_encode(['success' => true, 'id' => $key]);
