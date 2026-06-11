<?php

class Booking extends Model {
    public static function create(array $d): string {
        $db  = self::db();
        $key = 'b_' . time() . '_' . bin2hex(random_bytes(3));

        $eKey      = self::escape($key);
        $userId    = (int)($d['user_id'] ?? 0);
        $eUsername = self::escape($d['username'] ?? '');
        $carId     = (int)($d['car_id'] ?? 0);
        $eCarName  = self::escape($d['car_name'] ?? '');
        $carPrice  = (float)($d['car_price'] ?? 0);

        mysqli_query($db,
            "INSERT INTO bookings (booking_key, user_id, username, car_id, car_name, car_price, status)
             VALUES ('$eKey', $userId, '$eUsername', $carId, '$eCarName', $carPrice, 'bestellt')"
        );

        return $key;
    }

    public static function cancel(string $key, int $userId): bool {
        $db = self::db();
        $k  = self::escape($key);
        return (bool)mysqli_query($db,
            "UPDATE bookings SET status = 'storniert' WHERE booking_key = '$k' AND user_id = $userId AND status = 'bestellt'"
        );
    }

    public static function findByUser(int $userId): array {
        $db  = self::db();
        $res = mysqli_query($db, "SELECT * FROM bookings WHERE user_id = $userId ORDER BY created_at DESC");
        return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
    }

    public static function getAll(): array {
        $db  = self::db();
        $res = mysqli_query($db,
            "SELECT b.*, u.username AS display_username
             FROM bookings b LEFT JOIN users u ON b.user_id = u.id
             ORDER BY b.created_at DESC"
        );
        return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
    }

    public static function getByStatus(array $statuses): array {
        $db   = self::db();
        $list = implode("','", array_map([self::class, 'escape'], $statuses));
        $res  = mysqli_query($db, "SELECT * FROM bookings WHERE status IN ('$list') ORDER BY created_at DESC");
        return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
    }

    public static function findByKey(string $key): ?array {
        $db  = self::db();
        $k   = self::escape($key);
        $res = mysqli_query($db, "SELECT * FROM bookings WHERE booking_key = '$k'");
        return $res ? mysqli_fetch_assoc($res) ?: null : null;
    }

    public static function updateStatus(string $key, string $status): bool {
        $db = self::db();
        $k  = self::escape($key);
        $s  = self::escape($status);
        return (bool)mysqli_query($db, "UPDATE bookings SET status = '$s', updated_at = NOW() WHERE booking_key = '$k'");
    }
}
