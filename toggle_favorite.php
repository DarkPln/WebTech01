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
        $stmt = $db->prepare('DELETE FROM favorites WHERE user_id = ?');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        echo json_encode(['success' => true, 'status' => 'cleared', 'favorites' => []]);
        exit;
    }

    if ($carId === null) {
        echo json_encode(['success' => false, 'message' => 'Keine ID']);
        exit;
    }

    $stmt = $db->prepare('SELECT id FROM favorites WHERE user_id = ? AND car_id = ?');
    $stmt->bind_param('ii', $userId, $carId);
    $stmt->execute();

    if ($stmt->get_result()->fetch_assoc()) {
        $del = $db->prepare('DELETE FROM favorites WHERE user_id = ? AND car_id = ?');
        $del->bind_param('ii', $userId, $carId);
        $del->execute();
        $status = 'removed';
    } else {
        $ins = $db->prepare('INSERT INTO favorites (user_id, car_id) VALUES (?, ?)');
        $ins->bind_param('ii', $userId, $carId);
        $ins->execute();
        $status = 'added';
    }

    $all = $db->prepare('SELECT car_id FROM favorites WHERE user_id = ?');
    $all->bind_param('i', $userId);
    $all->execute();
    $favorites = array_column($all->get_result()->fetch_all(MYSQLI_ASSOC), 'car_id');

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
