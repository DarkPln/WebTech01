<?php

// Lukas Favoritenlogik

class Favorite extends Model {

// fügt fav hinzu bzw entfernt wenn schon favorit 

    public static function toggle(int $userId, int $carId): string {
        $db  = self::db();
        $res = mysqli_query($db, "SELECT id FROM favorites WHERE user_id = $userId AND car_id = $carId");
        if (mysqli_num_rows($res) > 0) {
            mysqli_query($db, "DELETE FROM favorites WHERE user_id = $userId AND car_id = $carId");
            return 'removed';
        }
        mysqli_query($db, "INSERT INTO favorites (user_id, car_id) VALUES ($userId, $carId)");
        return 'added';
    }


// holt die favoriten aus der favorites tabelle der db und gibt array zurück

    public static function findByUser(int $userId): array {
        $db  = self::db();
        $res = mysqli_query($db, "SELECT car_id FROM favorites WHERE user_id = $userId");
        return $res ? array_column(mysqli_fetch_all($res, MYSQLI_ASSOC), 'car_id') : [];
    }

// baut auf findByUser auf; holt autos von User die er als Favoriten gespeichert hat mit allen dazugehörigen daten  

    public static function getCarsForUser(int $userId): array {
        $db      = self::db();
        $favIds  = self::findByUser($userId);
        if (empty($favIds)) return [];
        $list = implode(',', $favIds);
        $res  = mysqli_query($db, "SELECT * FROM cars WHERE iid IN ($list)");
        return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
    }
}
