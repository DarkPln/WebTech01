<?php
session_start();
header('Content-Type: application/json');
require_once '../../db.php';

if (empty($_SESSION['is_admin'])) {
    echo json_encode(['success' => false, 'message' => 'Kein Zugriff']);
    exit;
}

$db    = getDB();
$input = json_decode(file_get_contents('php://input'), true) ?? [];
$iid   = (int)($input['iid'] ?? 0);

if (!$iid) {
    echo json_encode(['success' => false, 'message' => 'Ungültige ID']);
    exit;
}

mysqli_query($db, "DELETE FROM cars WHERE iid = $iid");

if (mysqli_affected_rows($db) > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Fahrzeug nicht gefunden']);
}
