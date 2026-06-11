<?php

class Database {
    private static ?mysqli $instance = null;

    public static function getInstance(): mysqli {
        if (self::$instance === null) {
            self::$instance = mysqli_connect('localhost', 'root', '', 'auto24');
            mysqli_set_charset(self::$instance, 'utf8mb4');
        }
        return self::$instance;
    }
}
