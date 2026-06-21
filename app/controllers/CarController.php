<?php
//Niclas: Controller für Fahrzeuge, enthält die Logik für die Fahrzeugseiten (Einzelansicht, Vergleichsansicht, PDF-Ansicht)
class CarController extends Controller {
    //CarController hat drei Methoden, 
    // die je nach URL-Aufruf verschiedene Seiten ausliefern

    //list() holt alle Fahrzeuge aus der Datenbank und übergibt sie an die View cars/list.php
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
            // Einzelansicht, wenn nur eine id= im Query-String steht
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
            // Vergleichsansicht (beliebig viele Fahrzeuge), wenn mehr als eine id im Query-String steht
            $fahrzeuge   = [];
            $notFoundIds = [];
            foreach ($ids as $id) {
                $car = Car::getById($id);
                if ($car) {
                    $car['isSold'] = Car::isBooked($id);
                    $fahrzeuge[]   = $car;
                } else {
                    //Sammelt nicht gefundene IDs bzw. Fahrzeuge
                    $notFoundIds[] = $id;
                }
            }
            //Fehler, wenn Fahrzeug nicht gefunden:
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
        //compact() erstellt ein Array aus den übergebenen Variablen, damit sie in der View (cars/detail.php) verfügbar sind 
    }
    //Beide Fälle (Einzel- und Vergleichsansicht) werden in der gleichen View cars/detail.php behandelt

    public function pdf(): void {
        //rendert eine druckfreundliche PDF ohne Navgation 
        $fahrzeug = Car::getById((int)($_GET['id'] ?? 0));
        if (!$fahrzeug) {
            //Wenn ID bzw. Fahrzeug nicht existiert, wird die Fehler angezeigt
            http_response_code(404);
            echo 'Fahrzeug nicht gefunden';
            return;
        }
        $this->render('cars/pdf', compact('fahrzeug'));
    }
}

//Zu MVC: Controller enthält keine SQL-Queries und kein HTML, 
//ist Vermittler zwischen Model und View 
