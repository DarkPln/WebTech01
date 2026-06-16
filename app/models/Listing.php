<?php

class Listing extends Model {
    public static function create(array $d, array $images, ?int $userId, string $username): string {
        $db  = self::db();
        $key = 'i_' . time() . '_' . bin2hex(random_bytes(3));

        $uid     = $userId === null ? 'NULL' : (int)$userId;
        $make    = $d['make'] ?? '';
        $model   = $d['model'] ?? '';
        $year    = $d['year'] ?? '';
        $km      = $d['km'] ?? '';
        $fuel    = $d['fuel'] ?? '';
        $gearbox = $d['gearbox'] ?? '';
        $power   = $d['power'] ?? '';
        $antrieb = $d['antrieb'] ?? '';
        $type    = $d['type'] ?? '';
        $cond    = $d['condition'] ?? '';
        $desc    = $d['desc'] ?? '';
        $name    = $d['name'] ?? '';
        $email   = $d['email'] ?? '';
        $phone   = $d['phone'] ?? '';
        $price   = (float)($d['price'] ?? 0);
        $images  = json_encode($images);

        mysqli_query($db,
            "INSERT INTO listings
                (listing_key, user_id, username, make, model, year, km, fuel, gearbox, power, antrieb, type, cond, price, description, contact_name, email, phone, images)
             VALUES ('$key', $uid, '$username', '$make', '$model', '$year', '$km', '$fuel', '$gearbox', '$power', '$antrieb', '$type', '$cond', $price, '$desc', '$name', '$email', '$phone', '$images')"
        );

        return $key;
    }

    public static function findByUser(int $userId): array {
        $db  = self::db();
        $res = mysqli_query($db, "SELECT * FROM listings WHERE user_id = $userId ORDER BY created_at DESC");
        return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
    }

    public static function getAll(): array {
        $db  = self::db();
        $res = mysqli_query($db,
            "SELECT listing_key AS id, username AS userId, contact_name AS name,
                    make, model, year, price, km, fuel, type, cond AS `condition`,
                    description AS `desc`, status, created_at AS createdAt
             FROM listings ORDER BY created_at DESC"
        );
        return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
    }

    public static function getAllRaw(): array {
        $db  = self::db();
        $res = mysqli_query($db, "SELECT * FROM listings ORDER BY created_at DESC");
        return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
    }

    public static function findByKey(string $key): ?array {
        $db  = self::db();
        $res = mysqli_query($db, "SELECT * FROM listings WHERE listing_key = '$key'");
        return $res ? mysqli_fetch_assoc($res) ?: null : null;
    }

    public static function updateStatus(string $key, string $status): bool {
        $db = self::db();
        return (bool)mysqli_query($db, "UPDATE listings SET status = '$status' WHERE listing_key = '$key'");
    }
}
