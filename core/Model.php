<?php

abstract class Model {
    protected static function db(): mysqli {
        return Database::getInstance();
    }

    protected static function escape(string $value): string {
        return mysqli_real_escape_string(self::db(), $value);
    }
}
