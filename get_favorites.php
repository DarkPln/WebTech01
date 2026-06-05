<?php
session_start();

if (!isset($_SESSION['favorites'])) {
    $_SESSION['favorites'] = [];
}

header('Content-Type: application/json');

echo json_encode([
    'success'   => true,
    'favorites' => array_values($_SESSION['favorites'])
]);
