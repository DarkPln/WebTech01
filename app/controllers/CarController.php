<?php

class CarController extends Controller {
    public function list(): void {
        $fahrzeuge = Car::getAll();
        $showFav   = true;
        $this->render('cars/list', compact('fahrzeuge', 'showFav'));
    }

    public function detail(string $id): void {
        $pid      = (int)$id;
        $fahrzeug = Car::getById($pid);

        if (!$fahrzeug) {
            http_response_code(404);
            echo 'Fahrzeug nicht gefunden';
            return;
        }

        $isSold  = Car::isBooked($pid);
        $showFav = true;
        $this->render('cars/detail', compact('fahrzeug', 'isSold', 'showFav'));
    }
}
