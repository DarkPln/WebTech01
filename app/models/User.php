<?php

class User extends Model {
    public static function findByUsername(string $username): ?array {
        $db   = self::db();
        $u    = self::escape($username);
        $res  = mysqli_query($db, "SELECT id, username, password, is_locked, is_admin FROM users WHERE username = '$u'");
        return $res ? mysqli_fetch_assoc($res) ?: null : null;
    }

    public static function findById(int $id): ?array {
        $db  = self::db();
        $res = mysqli_query($db, "SELECT id, username, email, phone, city, is_admin FROM users WHERE id = $id");
        return $res ? mysqli_fetch_assoc($res) ?: null : null;
    }

    public static function create(string $username, string $password): bool {
        $db = self::db();
        $u  = self::escape($username);
        $p  = self::escape($password);
        return (bool)mysqli_query($db, "INSERT INTO users (username, password) VALUES ('$u', '$p')");
    }

    public static function usernameExists(string $username): bool {
        $db  = self::db();
        $u   = self::escape($username);
        $res = mysqli_query($db, "SELECT id FROM users WHERE username = '$u'");
        return $res && mysqli_num_rows($res) > 0;
    }

    public static function update(int $id, array $fields): bool {
        $db   = self::db();
        $sets = [];
        foreach ($fields as $col => $val) {
            $safe   = self::escape((string)$val);
            $sets[] = "$col = '$safe'";
        }
        return (bool)mysqli_query($db, "UPDATE users SET " . implode(', ', $sets) . " WHERE id = $id");
    }

    public static function getAll(): array {
        $db  = self::db();
        $res = mysqli_query($db, "SELECT id, username, email, is_locked, is_admin, created_at FROM users ORDER BY created_at DESC");
        return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
    }

    public static function toggleLock(int $id): bool {
        return (bool)mysqli_query(self::db(), "UPDATE users SET is_locked = NOT is_locked WHERE id = $id");
    }
}
