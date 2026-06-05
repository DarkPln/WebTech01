<?php
session_start();

if (!isset($_SESSION['favorites'])) {
    $_SESSION['favorites'] = [];
}

header('Content-Type: application/json');

$input  = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';
$carId  = isset($input['carId']) ? (int)$input['carId'] : null;

// Alle leeren
if ($action === 'clear') {
    $_SESSION['favorites'] = [];
    echo json_encode(['success' => true, 'status' => 'cleared', 'favorites' => []]);
    exit;
}

// ID prüfen
if ($carId === null) {
    echo json_encode(['success' => false, 'message' => 'Keine ID']);
    exit;
}

// Toggle: drin → raus, nicht drin → rein
if (in_array($carId, $_SESSION['favorites'], true)) {
    $_SESSION['favorites'] = array_values(
        array_filter($_SESSION['favorites'], fn($id) => $id !== $carId)
    );
    $status = 'removed';
} else {
    $_SESSION['favorites'][] = $carId;
    $status = 'added';
}

echo json_encode([
    'success'   => true,
    'status'    => $status,
    'favorites' => $_SESSION['favorites']
]);
