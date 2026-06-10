<?php
session_start();
require_once '../../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] <= 0) {
    echo '<p class="msg-empty">Nicht eingeloggt.</p>';
    exit;
}

$userId = (int)$_SESSION['user_id'];
$db     = getDB();
$res    = mysqli_query($db,
    "SELECT id, title, body, is_read, created_at
     FROM messages
     WHERE user_id = $userId
     ORDER BY created_at DESC
     LIMIT 50"
);
$rows   = mysqli_fetch_all($res, MYSQLI_ASSOC);

if (empty($rows)) {
    echo '<p class="msg-empty">Keine Nachrichten vorhanden.</p>';
    exit;
}

foreach ($rows as $m) {
    $cls   = $m['is_read'] ? 'msg-card' : 'msg-card unread';
    $title = htmlspecialchars($m['title']);
    $body  = htmlspecialchars($m['body'] ?? '');
    $date  = date('d.m.Y, H:i', strtotime($m['created_at']));
    $id    = (int)$m['id'];
    echo "
<div class=\"$cls\" data-id=\"$id\">
    <div class=\"msg-card-header\">
        <span class=\"msg-card-title\">$title</span>
        <span class=\"msg-card-date\">$date</span>
    </div>" . ($body !== '' ? "<p class=\"msg-card-body\">$body</p>" : '') . "
</div>";
}
