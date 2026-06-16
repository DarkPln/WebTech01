<?php

class User extends Model {

// user finden
    public static function findByUsername(string $username): ?array {
        $db   = self::db();
        $res  = mysqli_query($db, "SELECT id, username, password, is_locked, is_admin FROM users WHERE username = '$username'");
        return $res ? mysqli_fetch_assoc($res) ?: null : null;
    }


    // user tab status 
    public static function findById(int $id): ?array {
        $db  = self::db();
        $res = mysqli_query($db, "SELECT id, username, email, phone, city, is_admin FROM users WHERE id = $id");
        return $res ? mysqli_fetch_assoc($res) ?: null : null;
    }


    // user anlegen
    public static function create(string $username, string $password): bool {
        $db = self::db();
        return (bool)mysqli_query($db, "INSERT INTO users (username, password) VALUES ('$username', '$password')");
    }


    // gibt es user schon? reg 
    public static function usernameExists(string $username): bool {
        $db  = self::db();
        $res = mysqli_query($db, "SELECT id FROM users WHERE username = '$username'");
        return $res && mysqli_num_rows($res) > 0;
    }

    // user tab update; dynamisch über array
    public static function update(int $id, array $fields): bool {
        $db   = self::db();
        $sets = [];
        foreach ($fields as $col => $val) {
            $sets[] = "$col = '$val'";
        }
        return (bool)mysqli_query($db, "UPDATE users SET " . implode(', ', $sets) . " WHERE id = $id");
    }

    // gibt alle user zurück, admin user tab
    public static function getAll(): array {
        $db  = self::db();
        $res = mysqli_query($db, "SELECT id, username, email, is_locked, is_admin, created_at FROM users ORDER BY created_at DESC");
        return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
    }

    // user sperren/entsperren
    public static function toggleLock(int $id): bool {
        return (bool)mysqli_query(self::db(), "UPDATE users SET is_locked = NOT is_locked WHERE id = $id");
    }
}
