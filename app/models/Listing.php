<?php

class Listing extends Model {
    public static function create(array $d, array $images, ?int $userId, string $username): string {
        $db  = self::db();
        $key = 'i_' . time() . '_' . bin2hex(random_bytes(3));

        $uid       = $userId === null ? 'NULL' : (int)$userId;
        $eKey      = self::escape($key);
        $eUser     = self::escape($username);
        $eMake     = self::escape($d['make'] ?? '');
        $eModel    = self::escape($d['model'] ?? '');
        $eYear     = self::escape($d['year'] ?? '');
        $eKm       = self::escape($d['km'] ?? '');
        $eFuel     = self::escape($d['fuel'] ?? '');
        $eGearbox  = self::escape($d['gearbox'] ?? '');
        $ePower    = self::escape($d['power'] ?? '');
        $eAntrieb  = self::escape($d['antrieb'] ?? '');
        $eType     = self::escape($d['type'] ?? '');
        $eCond     = self::escape($d['condition'] ?? '');
        $eDesc     = self::escape($d['desc'] ?? '');
        $eName     = self::escape($d['name'] ?? '');
        $eEmail    = self::escape($d['email'] ?? '');
        $ePhone    = self::escape($d['phone'] ?? '');
        $price     = (float)($d['price'] ?? 0);
        $eImages   = self::escape(json_encode($images));

        mysqli_query($db,
            "INSERT INTO listings
                (listing_key, user_id, username, make, model, year, km, fuel, gearbox, power, antrieb, type, cond, price, description, contact_name, email, phone, images)
             VALUES ('$eKey', $uid, '$eUser', '$eMake', '$eModel', '$eYear', '$eKm', '$eFuel', '$eGearbox', '$ePower', '$eAntrieb', '$eType', '$eCond', $price, '$eDesc', '$eName', '$eEmail', '$ePhone', '$eImages')"
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
        $k   = self::escape($key);
        $res = mysqli_query($db, "SELECT * FROM listings WHERE listing_key = '$k'");
        return $res ? mysqli_fetch_assoc($res) ?: null : null;
    }

    public static function updateStatus(string $key, string $status): bool {
        $db = self::db();
        $k  = self::escape($key);
        $s  = self::escape($status);
        return (bool)mysqli_query($db, "UPDATE listings SET status = '$s' WHERE listing_key = '$k'");
    }
}
