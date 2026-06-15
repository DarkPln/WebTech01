<?php

class CarController extends Controller {
    public function list(): void {
        $fahrzeuge = Car::getAll();
        $showFav   = true;
        $this->render('cars/list', compact('fahrzeuge', 'showFav'));
    }

    public function detail(): void {
        // Alle id=-Werte aus dem Query-String lesen (z.B. ?id=1&id=4&id=7)
        preg_match_all('/(?:^|&)id=(\d+)/', $_SERVER['QUERY_STRING'] ?? '', $m);
        $ids = array_values(array_unique(array_filter(array_map('intval', $m[1]))));

        if (empty($ids)) {
            http_response_code(404);
            echo 'Fahrzeug nicht gefunden';
            return;
        }

        $alleCars = Car::getAllBasic();

        if (count($ids) === 1) {
            // Einzelansicht
            $fahrzeug = Car::getById($ids[0]);
            if (!$fahrzeug) {
                http_response_code(404);
                echo 'Fahrzeug nicht gefunden';
                return;
            }
            $isSold      = Car::isBooked($ids[0]);
            $showFav     = true;
            $compareMode = false;
            $fahrzeuge   = [];
            $currentIds  = $ids;
            $this->render('cars/detail', compact('fahrzeug', 'isSold', 'showFav', 'compareMode', 'fahrzeuge', 'alleCars', 'currentIds'));
        } else {
            // Vergleichsansicht (beliebig viele Fahrzeuge)
            $fahrzeuge = [];
            foreach ($ids as $id) {
                $car = Car::getById($id);
                if ($car) {
                    $car['isSold'] = Car::isBooked($id);
                    $fahrzeuge[]   = $car;
                }
            }
            if (empty($fahrzeuge)) {
                http_response_code(404);
                echo 'Fahrzeuge nicht gefunden';
                return;
            }
            $fahrzeug    = $fahrzeuge[0];
            $isSold      = $fahrzeug['isSold'];
            $showFav     = false;
            $compareMode = true;
            $currentIds  = array_column($fahrzeuge, 'iid');
            $this->render('cars/detail', compact('fahrzeug', 'isSold', 'showFav', 'compareMode', 'fahrzeuge', 'alleCars', 'currentIds'));
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
