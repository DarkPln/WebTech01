<?php

class MessageController extends Controller {
  
// Lukas: serverseitiges Messaging; sprich nur der server schreibt hier (kein angebundens MailSystem o. Ä.)

// nur für eingeloggte User 

    public function listHtml(): void {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] <= 0) {
            echo '<p class="msg-empty">Nicht eingeloggt.</p>';
            return;
        }

        $userId = (int)$_SESSION['user_id'];
        $db     = Database::getInstance();
        $res    = mysqli_query($db,
            "SELECT id, title, body, is_read, created_at
             FROM messages WHERE user_id = $userId
             ORDER BY created_at DESC LIMIT 50"
        );
        $rows = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];

        if (empty($rows)) {
            echo '<p class="msg-empty">Keine Nachrichten vorhanden.</p>';
            return;
        }

        foreach ($rows as $m) {
            $cls   = $m['is_read'] ? 'msg-card' : 'msg-card unread'; // für css 
            $title = htmlspecialchars($m['title']);
            $body  = htmlspecialchars($m['body'] ?? '');
            $date  = date('d.m.Y, H:i', strtotime($m['created_at']));
            echo "
<div class=\"$cls\" data-id=\"{$m['id']}\">
    <div class=\"msg-card-header\">
        <span class=\"msg-card-title\">$title</span>
        <span class=\"msg-card-date\">$date</span>
    </div>" . ($body !== '' ? "<p class=\"msg-card-body\">$body</p>" : '') . "
</div>";
        }
    }

// nachrichten als gelesen markieren

    public function markRead(): void {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] <= 0) {
            $this->json(['success' => false]);
            return;
        }
        Message::markAllRead((int)$_SESSION['user_id']);
        $this->json(['success' => true]);
    }


// unread badge/count 
    
    public function unreadCount(): void {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] <= 0) {
            $this->json(['count' => 0]);
            return;
        }
        $this->json(['count' => Message::unreadCount((int)$_SESSION['user_id'])]);
    }
}
