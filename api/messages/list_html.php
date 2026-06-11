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
    $cls      = $m['is_read'] ? 'msg-card' : 'msg-card unread';
    $title    = htmlspecialchars($m['title']);
    $titleAttr = htmlspecialchars($m['title'], ENT_QUOTES);
    $body     = htmlspecialchars($m['body'] ?? '');
    $bodyAttr = htmlspecialchars($m['body'] ?? '', ENT_QUOTES);
    $date     = date('d.m.Y, H:i', strtotime($m['created_at']));
    $id       = (int)$m['id'];
    echo "
<div class=\"$cls\" data-id=\"$id\">
    <div class=\"msg-card-header\">
        <span class=\"msg-card-title\">$title</span>
        <span class=\"msg-card-date\">$date</span>
        <button class=\"msg-download-btn\"
                data-title=\"$titleAttr\"
                data-body=\"$bodyAttr\"
                data-date=\"$date\"
                onclick=\"downloadMsgPDF(this)\"
                title=\"Als PDF herunterladen\">
            <svg width=\"14\" height=\"14\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4\"/><polyline points=\"7 10 12 15 17 10\"/><line x1=\"12\" y1=\"15\" x2=\"12\" y2=\"3\"/></svg>
            PDF
        </button>
    </div>" . ($body !== '' ? "<p class=\"msg-card-body\">$body</p>" : '') . "
</div>";
}
