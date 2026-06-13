<?php

abstract class Model {

//verbindung db
    protected static function db(): mysqli {
        return Database::getInstance();
    }

// verhinderung sql injection '' -> kein problem mehr 
    protected static function escape(string $value): string {
        return mysqli_real_escape_string(self::db(), $value);
    }
}
