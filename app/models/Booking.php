<?php
// Tim

// Datenbankzugriffe für die bookings-Tabelle
class Booking extends Model {

    // Neue Buchung anlegen und den generierten Buchungs-Key zurückgeben
    public static function create(array $d): string {
        $db = self::db();
        // Eindeutiger Key aus Timestamp + Zufallsbytes, z.B. "b_1718123456_a3f9c1"
        $key = 'b_' . time() . '_' . bin2hex(random_bytes(3));

        $userId   = (int)($d['user_id'] ?? 0);
        $username = $d['username'] ?? '';
        $carId    = (int)($d['car_id'] ?? 0);
        $carName  = $d['car_name'] ?? '';
        $carPrice = (float)($d['car_price'] ?? 0);

        // Status wird beim Anlegen immer auf "bestellt" gesetzt
        mysqli_query($db,
            "INSERT INTO bookings (booking_key, user_id, username, car_id, car_name, car_price, status)
             VALUES ('$key', $userId, '$username', $carId, '$carName', $carPrice, 'bestellt')"
        );

        return $key;
    }

    // Buchung stornieren, nur wenn sie dem Nutzer gehört und noch "bestellt" ist
    public static function cancel(string $key, int $userId): bool {
        $db = self::db();
        // user_id + status in der WHERE-Bedingung verhindert fremdes Stornieren
        return (bool)mysqli_query($db,
            "UPDATE bookings SET status = 'storniert' WHERE booking_key = '$key' AND user_id = $userId AND status = 'bestellt'"
        );
    }

    // Alle Buchungen eines Nutzers absteigend nach Datum zurückgeben
    public static function findByUser(int $userId): array {
        $db  = self::db();
        $res = mysqli_query($db, "SELECT * FROM bookings WHERE user_id = $userId ORDER BY created_at DESC");
        return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
    }
}
