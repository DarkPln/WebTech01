<?php
//Modell
// Niclas
abstract class Model {

//verbindung db
    protected static function db(): mysqli {
        return Database::getInstance();
    }
}
