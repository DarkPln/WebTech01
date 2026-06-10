<?php
session_start();
header('Content-Type: application/json');
require_once '../../db.php';

if (empty($_SESSION['is_admin'])) {
    echo json_encode(['success' => false, 'message' => 'Kein Zugriff']);
    exit;
}

$db     = getDB();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $result = mysqli_query($db,
        'SELECT booking_key AS id, username AS userId, car_id AS carId, car_name AS carName,
                car_price AS carPrice, status, reason, created_at AS createdAt, updated_at AS updatedAt
         FROM bookings
         ORDER BY created_at DESC'
    );
    echo json_encode(['success' => true, 'bookings' => mysqli_fetch_all($result, MYSQLI_ASSOC)]);
    exit;
}

$input      = json_decode(file_get_contents('php://input'), true) ?? [];
$bookingKey = $input['id']     ?? '';
$status     = $input['status'] ?? '';
$reason     = $input['reason'] ?? '';

$eStatus     = mysqli_real_escape_string($db, $status);
$eReason     = mysqli_real_escape_string($db, $reason);
$eBookingKey = mysqli_real_escape_string($db, $bookingKey);

mysqli_query($db, "UPDATE bookings SET status = '$eStatus', reason = '$eReason', updated_at = NOW() WHERE booking_key = '$eBookingKey'");

// Create a notification message for the user if it's a real account
$bkgRes  = mysqli_query($db, "SELECT user_id, car_name FROM bookings WHERE booking_key = '$eBookingKey'");
$booking = mysqli_fetch_assoc($bkgRes);
if ($booking && (int)$booking['user_id'] > 0) {
    $userId  = (int)$booking['user_id'];
    $carName = mysqli_real_escape_string($db, $booking['car_name'] ?? 'Ihr Fahrzeug');

    $titles = [
        'bestellt'        => 'Buchung eingegangen',
        'in_bearbeitung'  => 'Buchung in Bearbeitung',
        'versandt'        => 'Fahrzeug bereit',
        'fertig'          => 'Buchung abgeschlossen',
        'storniert'       => 'Buchung storniert',
        'abgelehnt'       => 'Buchung abgelehnt',
    ];
    $bodies = [
        'bestellt'        => "Ihre Buchung für \"$carName\" wurde erfolgreich aufgenommen.",
        'in_bearbeitung'  => "Ihre Buchung für \"$carName\" wird aktuell bearbeitet.",
        'versandt'        => "Ihr Fahrzeug \"$carName\" steht zur Abholung bereit.",
        'fertig'          => "Ihre Buchung für \"$carName\" wurde erfolgreich abgeschlossen. Vielen Dank!",
        'storniert'       => "Ihre Buchung für \"$carName\" wurde storniert.",
        'abgelehnt'       => "Ihre Buchung für \"$carName\" wurde leider abgelehnt." . ($reason !== '' ? " Grund: $reason" : ''),
    ];

    if (isset($titles[$status])) {
        $eTitle = mysqli_real_escape_string($db, $titles[$status]);
        $eBody  = mysqli_real_escape_string($db, $bodies[$status]);
        mysqli_query($db, "INSERT INTO messages (user_id, title, body) VALUES ($userId, '$eTitle', '$eBody')");
    }
}

echo json_encode(['success' => true]);
