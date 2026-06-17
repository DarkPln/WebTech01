<?php
// MEssage logik Lukas 

class Message extends Model {

// passende nachrichten zu user aus der db finden 
    public static function findByUser(int $userId): array {
        $db  = self::db();
        $res = mysqli_query($db, "SELECT * FROM messages WHERE user_id = $userId ORDER BY created_at DESC");
        return $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
    }

// nachrichten als gelesen setzen bzw flag bei is_read in db tabelle messages setzen 
    public static function markAllRead(int $userId): bool {
        return (bool)mysqli_query(self::db(), "UPDATE messages SET is_read = 1 WHERE user_id = $userId");
    }

// unread count aus tabelle message ist einfach die anzahl der nachrichten in tabelle nachrichten die noch nicht als gelesen markiert wurden 
    public static function unreadCount(int $userId): int {
        $db  = self::db();
        $res = mysqli_query($db, "SELECT COUNT(*) AS cnt FROM messages WHERE user_id = $userId AND is_read = 0");
        return (int)mysqli_fetch_assoc($res)['cnt'];
    }

// message erzeugen; geht von adminController aus da dieser bei zB Genehmigung eine Nachricht an den betroffenen user schickt 
    public static function create(int $userId, string $title, string $body): bool {
        $db = self::db();
        return (bool)mysqli_query($db,
            "INSERT INTO messages (user_id, title, body, is_read) VALUES ($userId, '$title', '$body', 0)"
        );
    }
}
