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

// Prüfen ob das Konto vom Administrator gesperrt wurde
if ($_SESSION['user_id'] > 0) {
    $abfrage = getDB()->prepare('SELECT is_locked FROM users WHERE id = ?');
    $abfrage->execute([$_SESSION['user_id']]);
    $nutzer = $abfrage->fetch();
    if ($nutzer && $nutzer['is_locked']) {
        echo json_encode(['success' => false, 'message' => 'Ihr Konto ist gesperrt']);
        exit;
    }
}

// Eindeutigen Schlüssel für diese Buchung erstellen (z.B. b_1718000000_3fa2c1)
$schluessel = 'b_' . time() . '_' . bin2hex(random_bytes(3));

// Buchung in der Datenbank speichern
getDB()->prepare(
    'INSERT INTO bookings (booking_key, user_id, username, car_id, car_name, car_price)
     VALUES (?, ?, ?, ?, ?, ?)'
)->execute([
    $schluessel,
    $_SESSION['user_id'],
    $_SESSION['username'],
    $fahrzeugId,
    $fahrzeugName,
    $fahrzeugPreis
]);

echo json_encode(['success' => true, 'id' => $schluessel]);
