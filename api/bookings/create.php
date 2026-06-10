<?php
// Neue Buchung anlegen und in der Datenbank speichern

session_start();
header('Content-Type: application/json');
require_once '../../db.php';

// Nur eingeloggte Nutzer dürfen buchen
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Nicht eingeloggt']);
    exit;
}

// Fahrzeugdaten aus dem gesendeten JSON lesen
$eingabe       = json_decode(file_get_contents('php://input'), true) ?? [];
$fahrzeugId    = (int)($eingabe['carId']    ?? 0);
$fahrzeugName  = trim($eingabe['carName']   ?? '');
$fahrzeugPreis = (float)($eingabe['carPrice'] ?? 0);

if (!$fahrzeugId) {
    echo json_encode(['success' => false, 'message' => 'Ungültige Fahrzeug-ID']);
    exit;
}

$db  = getDB();
$uid = $_SESSION['user_id'];

// Prüfen ob das Konto vom Administrator gesperrt wurde
if ($uid > 0) {
    $lockRes = mysqli_query($db, "SELECT is_locked FROM users WHERE id = $uid");
    $nutzer  = mysqli_fetch_assoc($lockRes);
    if ($nutzer && $nutzer['is_locked']) {
        echo json_encode(['success' => false, 'message' => 'Ihr Konto ist gesperrt']);
        exit;
    }
}

// Eindeutigen Schlüssel für diese Buchung erstellen (z.B. b_1718000000_3fa2c1)
$schluessel    = 'b_' . time() . '_' . bin2hex(random_bytes(3));
$uname         = $_SESSION['username'];
$eSchluessel   = mysqli_real_escape_string($db, $schluessel);
$eUname        = mysqli_real_escape_string($db, $uname);
$eFahrzeugName = mysqli_real_escape_string($db, $fahrzeugName);

// Buchung in der Datenbank speichern
mysqli_query($db,
    "INSERT INTO bookings (booking_key, user_id, username, car_id, car_name, car_price)
     VALUES ('$eSchluessel', $uid, '$eUname', $fahrzeugId, '$eFahrzeugName', $fahrzeugPreis)"
);

echo json_encode(['success' => true, 'id' => $schluessel]);
