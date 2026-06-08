<?php
function getDB(): mysqli {
    static $db = null;
    if ($db === null) {
        $db = new mysqli('localhost', 'root', '', 'auto24');
        $db->set_charset('utf8mb4');
    }
    return $db;
}
