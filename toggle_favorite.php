<?php
session_start();
header('Content-Type: application/json');

$input  = json_decode(file_get_contents('php://input'), true) ?? [];
$action = $input['action'] ?? '';
$carId  = isset($input['carId']) ? (int)$input['carId'] : null;

$userId = $_SESSION['user_id'] ?? null;
$useDB  = ($userId !== null && $userId > 0);

if ($useDB) {
    require_once 'db.php';
    $db = getDB();

    if ($action === 'clear') {
        $db->prepare('DELETE FROM favorites WHERE user_id = ?')->execute([$userId]);
        echo json_encode(['success' => true, 'status' => 'cleared', 'favorites' => []]);
        exit;
    }

    if ($carId === null) {
        echo json_encode(['success' => false, 'message' => 'Keine ID']);
        exit;
    }

    $stmt = $db->prepare('SELECT id FROM favorites WHERE user_id = ? AND car_id = ?');
    $stmt->execute([$userId, $carId]);

    if ($stmt->fetch()) {
        $db->prepare('DELETE FROM favorites WHERE user_id = ? AND car_id = ?')->execute([$userId, $carId]);
        $status = 'removed';
    } else {
        $db->prepare('INSERT INTO favorites (user_id, car_id) VALUES (?, ?)')->execute([$userId, $carId]);
        $status = 'added';
    }

    $stmt = $db->prepare('SELECT car_id FROM favorites WHERE user_id = ?');
    $stmt->execute([$userId]);
    $favorites = array_column($stmt->fetchAll(), 'car_id');

    echo json_encode(['success' => true, 'status' => $status, 'favorites' => $favorites]);
} else {
    // Gäste: Favoriten in Session
    if (!isset($_SESSION['favorites'])) {
        $_SESSION['favorites'] = [];
    }

    if ($action === 'clear') {
        $_SESSION['favorites'] = [];
        echo json_encode(['success' => true, 'status' => 'cleared', 'favorites' => []]);
        exit;
    }

    if ($carId === null) {
        echo json_encode(['success' => false, 'message' => 'Keine ID']);
        exit;
    }

    if (in_array($carId, $_SESSION['favorites'], true)) {
        $_SESSION['favorites'] = array_values(
            array_filter($_SESSION['favorites'], fn($id) => $id !== $carId)
        );
        $status = 'removed';
    } else {
        $_SESSION['favorites'][] = $carId;
        $status = 'added';
    }

    echo json_encode(['success' => true, 'status' => $status, 'favorites' => $_SESSION['favorites']]);
}
