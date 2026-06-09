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

$db           = getDB();
$eBuchungsKey = $db->real_escape_string($buchungsKey);

// Status nur ändern wenn die Buchung diesem Nutzer gehört und noch "bestellt" ist
$db->query(
    "UPDATE bookings
     SET status = 'storniert', updated_at = NOW()
     WHERE booking_key = '$eBuchungsKey' AND user_id = $uid AND status = 'bestellt'"
);

// affected_rows gibt zurück wie viele Zeilen geändert wurden (0 = nichts gefunden)
if ($db->affected_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Buchung nicht gefunden oder nicht stornierbar']);
    exit;
}

echo json_encode(['success' => true]);
