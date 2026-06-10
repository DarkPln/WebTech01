<?php
session_start();
header('Content-Type: application/json');
require_once '../../db.php';

$userId   = $_SESSION['user_id'] ?? null;
$username = $_SESSION['username'] ?? 'Gast';

$key   = 'i_' . time() . '_' . bin2hex(random_bytes(3));
$price = (float)($_POST['price'] ?? 0);

$db = getDB();

// Add images column if it doesn't exist yet (safe on MariaDB / MySQL 8.0.3+)
$db->query("ALTER TABLE listings ADD COLUMN IF NOT EXISTS images TEXT NULL");

$uid       = $userId === null ? 'NULL' : (int)$userId;
$eKey      = $db->real_escape_string($key);
$eUsername = $db->real_escape_string($username);
$eMake     = $db->real_escape_string($_POST['make']      ?? '');
$eModel    = $db->real_escape_string($_POST['model']     ?? '');
$eYear     = $db->real_escape_string($_POST['year']      ?? '');
$eKm       = $db->real_escape_string($_POST['km']        ?? '');
$eFuel     = $db->real_escape_string($_POST['fuel']      ?? '');
$eGearbox  = $db->real_escape_string($_POST['gearbox']   ?? '');
$ePower    = $db->real_escape_string($_POST['power']     ?? '');
$eAntrieb  = $db->real_escape_string($input['antrieb']  ?? '');
$eType     = $db->real_escape_string($_POST['type']      ?? '');
$eCond     = $db->real_escape_string($_POST['condition'] ?? '');
$eDesc     = $db->real_escape_string($_POST['desc']      ?? '');
$eName     = $db->real_escape_string($_POST['name']      ?? '');
$eEmail    = $db->real_escape_string($_POST['email']     ?? '');
$ePhone    = $db->real_escape_string($_POST['phone']     ?? '');

// Handle image uploads
$uploadedImages = [];
if (isset($_FILES['images']) && !empty($_FILES['images']['tmp_name'][0])) {
    $uploadDir = __DIR__ . '/../../uploads/listings/' . $key . '/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $allowed = ['image/jpeg', 'image/png', 'image/webp'];
    $maxSize = 5 * 1024 * 1024; // 5 MB

    foreach ($_FILES['images']['tmp_name'] as $i => $tmpName) {
        if ($_FILES['images']['error'][$i] !== UPLOAD_ERR_OK) continue;
        if (!in_array($_FILES['images']['type'][$i], $allowed, true)) continue;
        if ($_FILES['images']['size'][$i] > $maxSize) continue;
        if (count($uploadedImages) >= 10) break;

        $origExt  = strtolower(pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION));
        $safeExt  = in_array($origExt, ['jpg', 'jpeg', 'png', 'webp'], true) ? $origExt : 'jpg';
        $filename = $i . '_' . bin2hex(random_bytes(4)) . '.' . $safeExt;

        if (move_uploaded_file($tmpName, $uploadDir . $filename)) {
            $uploadedImages[] = 'uploads/listings/' . $key . '/' . $filename;
        }
    }
}

$eImages = $db->real_escape_string(json_encode($uploadedImages));

$db->query(
    "INSERT INTO listings
        (listing_key, user_id, username, make, model, year, km, fuel, gearbox, power, type, cond, price, description, contact_name, email, phone, images)
     VALUES ('$eKey', $uid, '$eUsername', '$eMake', '$eModel', '$eYear', '$eKm', '$eFuel', '$eGearbox', '$ePower', '$eType', '$eCond', $price, '$eDesc', '$eName', '$eEmail', '$ePhone', '$eImages')"
);

echo json_encode(['success' => true, 'id' => $key]);
