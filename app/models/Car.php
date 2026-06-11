<?php

class Car extends Model {
    public static function getAll(array $filters = []): array {
        $db    = self::db();
        $where = "iid NOT IN (
            SELECT car_id FROM bookings
            WHERE status IN ('in_bearbeitung','versandt','fertig')
            AND car_id IS NOT NULL
        )";

        $result = mysqli_query($db, "SELECT * FROM cars WHERE $where ORDER BY id ASC");
        return $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
    }

    public static function getById(int $id): ?array {
        $db  = self::db();
        $res = mysqli_query($db, "SELECT * FROM cars WHERE iid = $id");
        return $res ? mysqli_fetch_assoc($res) ?: null : null;
    }

    public static function getForCarousel(): array {
        $db  = self::db();
        $res = mysqli_query($db, "SELECT iid, marke, modell, preis, imagepath, baujahr FROM cars ORDER BY id ASC");
        return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
    }

    public static function create(array $d): bool {
        $db = self::db();
        $name      = self::escape($d['name']);
        $descr     = self::escape($d['beschreibung'] ?? '');
        $img       = self::escape($d['imagepath']);
        $preis     = (float)$d['preis'];
        $kat       = self::escape($d['kategorie']);
        $unkat     = self::escape($d['unterkategorie']);
        $marke     = self::escape($d['marke']);
        $modell    = self::escape($d['modell']);
        $baujahr   = (int)$d['baujahr'];
        $kraft     = self::escape($d['kraftstoff']);
        $km        = (int)$d['kilometerstand'];
        $ps        = (int)$d['leistung_ps'];
        $antrieb   = self::escape($d['antrieb']);

        $nxtRes  = mysqli_query($db, "SELECT COALESCE(MAX(iid), 100) + 1 AS next FROM cars");
        $nextIid = (int)mysqli_fetch_assoc($nxtRes)['next'];

        return (bool)mysqli_query($db,
            "INSERT INTO cars (iid, name, beschreibung, imagepath, preis, kategorie, unterkategorie, marke, modell, baujahr, kraftstoff, kilometerstand, leistung_ps, antrieb)
             VALUES ($nextIid, '$name', '$descr', '$img', $preis, '$kat', '$unkat', '$marke', '$modell', $baujahr, '$kraft', $km, $ps, '$antrieb')"
        );
    }

    public static function delete(int $id): bool {
        return (bool)mysqli_query(self::db(), "DELETE FROM cars WHERE iid = $id");
    }

    public static function getAll_admin(): array {
        $db  = self::db();
        $res = mysqli_query($db, "SELECT * FROM cars ORDER BY id DESC");
        return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
    }

    public static function isBooked(int $id): bool {
        $db  = self::db();
        $res = mysqli_query($db, "SELECT COUNT(*) AS cnt FROM bookings WHERE car_id = $id AND status IN ('in_bearbeitung','versandt','fertig')");
        return (int)mysqli_fetch_assoc($res)['cnt'] > 0;
    }
}
