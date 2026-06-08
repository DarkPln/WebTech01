<?php
// Eine Buchung auf den Status "storniert" setzen

session_start();
header('Content-Type: application/json');
require_once '../../db.php';

// Nur eingeloggte Nutzer dürfen stornieren
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Nicht eingeloggt']);
    exit;
}

$eingabe     = json_decode(file_get_contents('php://input'), true) ?? [];
$buchungsKey = $eingabe['id'] ?? '';
$uid         = $_SESSION['user_id'];

// Status nur ändern wenn die Buchung diesem Nutzer gehört und noch "bestellt" ist
// Das WHERE verhindert, dass jemand fremde Buchungen storniert
$abfrage = getDB()->prepare(
    'UPDATE bookings
     SET status = "storniert", updated_at = NOW()
     WHERE booking_key = ? AND user_id = ? AND status = "bestellt"'
);
$abfrage->bind_param('si', $buchungsKey, $uid);
$abfrage->execute();

// affected_rows gibt zurück wie viele Zeilen geändert wurden (0 = nichts gefunden)
if ($abfrage->affected_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Buchung nicht gefunden oder nicht stornierbar']);
    exit;
}

echo json_encode(['success' => true]);
