<?php
session_start();
header('Content-Type: application/json');

// Lukas toggle favorties an/aus

$input  = json_decode(file_get_contents('php://input'), true) ?? [];
$action = $input['action'] ?? '';
$carId  = isset($input['carId']) ? (int)$input['carId'] : null;

$userId = $_SESSION['user_id'] ?? null;
$useDB  = ($userId !== null && $userId > 0);


// datenbankteil für in db gespeicherter user 

if ($useDB) {
    require_once 'db.php';
    $db = getDB();

    if ($action === 'clear') {
        $db->query("DELETE FROM favorites WHERE user_id = $userId");
        echo json_encode(['success' => true, 'status' => 'cleared', 'favorites' => []]);
        exit;
    }

    if ($carId === null) {
        echo json_encode(['success' => false, 'message' => 'Keine ID']);
        exit;
    }

    if ($db->query("SELECT id FROM favorites WHERE user_id = $userId AND car_id = $carId")->fetch_assoc()) {
        $db->query("DELETE FROM favorites WHERE user_id = $userId AND car_id = $carId");
        $status = 'removed';
    } else {
        $db->query("INSERT INTO favorites (user_id, car_id) VALUES ($userId, $carId)");
        $status = 'added';
    }

    $favorites = array_column($db->query("SELECT car_id FROM favorites WHERE user_id = $userId")->fetch_all(MYSQLI_ASSOC), 'car_id');

    echo json_encode(['success' => true, 'status' => $status, 'favorites' => $favorites]);
} else {
    // sessionteil für gäste
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
            // wichtig sonst wird zb aus array(1,2,3) -> array(0=>1,2=>3) und das gibt probleme bei in_array()
        );
        $status = 'removed';
    } else {
        $_SESSION['favorites'][] = $carId;
        $status = 'added';
    }

    echo json_encode(['success' => true, 'status' => $status, 'favorites' => $_SESSION['favorites']]);
}
