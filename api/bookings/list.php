<?php
// Alle Buchungen des eingeloggten Nutzers aus der Datenbank laden

session_start();
header('Content-Type: application/json');
require_once '../../db.php';

// Nur eingeloggte Nutzer dürfen ihre Buchungen sehen
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'bookings' => []]);
    exit;
}

// Buchungen des aktuellen Nutzers laden, neueste zuerst
$uid    = $_SESSION['user_id'];
$db     = getDB();
$result = mysqli_query($db,
    "SELECT booking_key AS id,
            car_id      AS carId,
            car_name    AS carName,
            car_price   AS carPrice,
            status,
            reason,
            created_at  AS createdAt,
            updated_at  AS updatedAt
     FROM bookings
     WHERE user_id = $uid
     ORDER BY created_at DESC"
);

echo json_encode(['success' => true, 'bookings' => mysqli_fetch_all($result, MYSQLI_ASSOC)]);
