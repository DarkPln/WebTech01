<?php
// Tim

class Booking extends Model {
    public static function create(array $d): string {
        $db  = self::db();
        $key = 'b_' . time() . '_' . bin2hex(random_bytes(3));

        $userId    = (int)($d['user_id'] ?? 0);
        $username  = $d['username'] ?? '';
        $carId     = (int)($d['car_id'] ?? 0);
        $carName   = $d['car_name'] ?? '';
        $carPrice  = (float)($d['car_price'] ?? 0);

        mysqli_query($db,
            "INSERT INTO bookings (booking_key, user_id, username, car_id, car_name, car_price, status)
             VALUES ('$key', $userId, '$username', $carId, '$carName', $carPrice, 'bestellt')"
        );

        return $key;
    }

    public static function cancel(string $key, int $userId): bool {
        $db = self::db();
        return (bool)mysqli_query($db,
            "UPDATE bookings SET status = 'storniert' WHERE booking_key = '$key' AND user_id = $userId AND status = 'bestellt'"
        );
    }

    public static function findByUser(int $userId): array {
        $db  = self::db();
        $res = mysqli_query($db, "SELECT * FROM bookings WHERE user_id = $userId ORDER BY created_at DESC");
        return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
    }

}
