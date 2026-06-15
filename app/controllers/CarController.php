<?php

class CarController extends Controller {
    public function list(): void {
        $fahrzeuge = Car::getAll();
        $showFav   = true;
        $this->render('cars/list', compact('fahrzeuge', 'showFav'));
    }

    public function detail(): void {
        // Alle id=-Werte aus dem Query-String lesen (auch negative, z.B. ?id=1&id=-1)
        preg_match_all('/(?:^|&)id=(-?\d+)/', $_SERVER['QUERY_STRING'] ?? '', $m);
        $ids = array_values(array_unique(array_map('intval', $m[1])));

        if (empty($ids)) {
            $this->render('errors/vehicleNotFound');
            return;
        }

        $alleCars = Car::getAllBasic();

        if (count($ids) === 1) {
            // Einzelansicht
            $fahrzeug = Car::getById($ids[0]);
            if (!$fahrzeug) {
                $this->render('errors/vehicleNotFound');
                return;
            }
            $isSold      = Car::isBooked($ids[0]);
            $showFav     = true;
            $compareMode = false;
            $fahrzeuge   = [];
            $notFoundIds = [];
            $currentIds  = $ids;
            $this->render('cars/detail', compact('fahrzeug', 'isSold', 'showFav', 'compareMode', 'fahrzeuge', 'alleCars', 'currentIds', 'notFoundIds'));
        } else {
            // Vergleichsansicht (beliebig viele Fahrzeuge)
            $fahrzeuge   = [];
            $notFoundIds = [];
            foreach ($ids as $id) {
                $car = Car::getById($id);
                if ($car) {
                    $car['isSold'] = Car::isBooked($id);
                    $fahrzeuge[]   = $car;
                } else {
                    $notFoundIds[] = $id;
                }
            }
            if (empty($fahrzeuge)) {
                $this->render('errors/vehicleNotFound');
                return;
            }
            $fahrzeug    = $fahrzeuge[0];
            $isSold      = $fahrzeug['isSold'];
            $showFav     = false;
            $compareMode = true;
            $currentIds  = array_column($fahrzeuge, 'iid');
            $this->render('cars/detail', compact('fahrzeug', 'isSold', 'showFav', 'compareMode', 'fahrzeuge', 'alleCars', 'currentIds', 'notFoundIds'));
        }
    }

    public function pdf(): void {
        $fahrzeug = Car::getById((int)($_GET['id'] ?? 0));
        if (!$fahrzeug) {
            http_response_code(404);
            echo 'Fahrzeug nicht gefunden';
            return;
        }
        $this->render('cars/pdf', compact('fahrzeug'));
    }
}
