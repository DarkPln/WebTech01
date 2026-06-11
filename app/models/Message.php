<?php

class Message extends Model {
    public static function findByUser(int $userId): array {
        $db  = self::db();
        $res = mysqli_query($db, "SELECT * FROM messages WHERE user_id = $userId ORDER BY created_at DESC");
        return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
    }

    public static function markAllRead(int $userId): bool {
        return (bool)mysqli_query(self::db(), "UPDATE messages SET is_read = 1 WHERE user_id = $userId");
    }

    public static function unreadCount(int $userId): int {
        $db  = self::db();
        $res = mysqli_query($db, "SELECT COUNT(*) AS cnt FROM messages WHERE user_id = $userId AND is_read = 0");
        return (int)mysqli_fetch_assoc($res)['cnt'];
    }

    public static function create(int $userId, string $title, string $body): bool {
        $db    = self::db();
        $eTitle = self::escape($title);
        $eBody  = self::escape($body);
        return (bool)mysqli_query($db,
            "INSERT INTO messages (user_id, title, body, is_read) VALUES ($userId, '$eTitle', '$eBody', 0)"
        );
    }
}
