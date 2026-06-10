<?php
function getDB() {
    static $db = null;
    if ($db === null) {
        $db = mysqli_connect('localhost', 'root', '', 'auto24');
        mysqli_set_charset($db, 'utf8mb4');
    }
    return $db;
}
