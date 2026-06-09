<?php
session_start();
header('Content-Type: application/json');
require_once '../../db.php';

$input    = json_decode(file_get_contents('php://input'), true) ?? [];
$userId   = $_SESSION['user_id'] ?? null;
$username = $_SESSION['username'] ?? 'Gast';

$key   = 'i_' . time() . '_' . bin2hex(random_bytes(3));
$price = (float)($input['price'] ?? 0);

$db        = getDB();
$uid       = $userId === null ? 'NULL' : (int)$userId;
$eKey      = $db->real_escape_string($key);
$eUsername = $db->real_escape_string($username);
$eMake     = $db->real_escape_string($input['make']      ?? '');
$eModel    = $db->real_escape_string($input['model']     ?? '');
$eYear     = $db->real_escape_string($input['year']      ?? '');
$eKm       = $db->real_escape_string($input['km']        ?? '');
$eFuel     = $db->real_escape_string($input['fuel']      ?? '');
$eGearbox  = $db->real_escape_string($input['gearbox']   ?? '');
$ePower    = $db->real_escape_string($input['power']     ?? '');
$eType     = $db->real_escape_string($input['type']      ?? '');
$eCond     = $db->real_escape_string($input['condition'] ?? '');
$eDesc     = $db->real_escape_string($input['desc']      ?? '');
$eName     = $db->real_escape_string($input['name']      ?? '');
$eEmail    = $db->real_escape_string($input['email']     ?? '');
$ePhone    = $db->real_escape_string($input['phone']     ?? '');

$db->query(
    "INSERT INTO listings
        (listing_key, user_id, username, make, model, year, km, fuel, gearbox, power, type, cond, price, description, contact_name, email, phone)
     VALUES ('$eKey', $uid, '$eUsername', '$eMake', '$eModel', '$eYear', '$eKm', '$eFuel', '$eGearbox', '$ePower', '$eType', '$eCond', $price, '$eDesc', '$eName', '$eEmail', '$ePhone')"
);

echo json_encode(['success' => true, 'id' => $key]);
