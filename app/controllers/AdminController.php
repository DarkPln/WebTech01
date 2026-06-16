<!-- Tim -->
<?php

class AdminController extends Controller {
    public function index(): void {
        $this->render('admin/index'); //Anzeige des Admin-Dashboards
    }

    // ---- Listings ----

    // requireAdmin() prüft $_SESSION['is_admin'] und bricht mit HTTP 403 ab wenn kein Admin eingeloggt ist
    // Listing::getAll() holt alle Zeilen aus der listings-Tabelle
    // $this->json() setzt Content Type application/json und gibt das Array per json_encode() aus
    public function getListings(): void {
        $this->requireAdmin();
        $this->json(['success' => true, 'listings' => Listing::getAll()]);
    }

    public function updateListing(): void {
        $this->requireAdmin();
        // JSON-Body lesen, Formulardaten kommen nicht per $_POST sondern als Raw-JSON
        $input  = json_decode(file_get_contents('php://input'), true) ?? [];
        $key    = $input['id']     ?? '';
        $status = $input['status'] ?? '';

        $listing = Listing::findByKey($key);
        if (!$listing) {
            $this->json(['success' => false, 'message' => 'Inserat nicht gefunden']);
            return;
        }

        $oldStatus = $listing['status'];

        // Inserat aus listings (Wartebereich) in cars kopieren
        // Zweite Bedingung verhindert doppelten cars-Eintrag wenn Admin zweimal auf Genehmigen klickt
        if ($status === 'genehmigt' && $oldStatus !== 'genehmigt') {
            // Formular schickt Kraftstoff kleingeschrieben (z.B. "benzin"), cars-Tabelle erwartet "Benzin"
            $kraftMap   = ['benzin' => 'Benzin', 'diesel' => 'Diesel', 'elektro' => 'Elektro', 'hybrid' => 'Hybrid', 'lpg' => 'LPG'];
            // Bilder stehen als JSON-String in der DB z.B. ["data:image/jpeg;base64,..."]
            // ?? '[]' als Fallback falls das Feld NULL ist, sonst würde json_decode einen Fehler werfen
            $imagesArr  = json_decode($listing['images'] ?? '[]', true);
            // Nur das erste Bild als Vorschaubild übernehmen, Platzhalter wenn kein Bild vorhanden
            $imagepath  = !empty($imagesArr[0]) ? $imagesArr[0] : 'https://placehold.co/800x500/1a1a1a/cccccc?text=Kein+Bild';

            // INSERT in die cars-Tabelle, Felder aus listing übernehmen und ggf. transformieren
            Car::create([
                'name'          => $listing['make'] . ' ' . $listing['model'],
                'beschreibung'  => $listing['description'] ?? '',
                'imagepath'     => $imagepath,
                'preis'         => $listing['price'],
                'kategorie'     => 'gebrauchtwagen', // Nutzer-Inserate sind immer Gebrauchtwagen
                'unterkategorie'=> strtolower($listing['type'] ?? ''), // z.B. "limousine", "suv"
                'marke'         => $listing['make'],
                'modell'        => $listing['model'],
                'baujahr'       => $listing['year'],
                // Erst in Map nachschauen; Fallback ucfirst() falls unbekannter Kraftstofftyp
                'kraftstoff'    => $kraftMap[strtolower($listing['fuel'] ?? '')] ?? ucfirst($listing['fuel'] ?? ''),
                'kilometerstand'=> $listing['km'],
                'leistung_ps'   => $listing['power'],
                'antrieb'       => $listing['antrieb'] ?? '',
            ]);
        }

        Listing::updateStatus($key, $status);


        // Lukas message logik (geht nur von admin aus, es gibt keine user zu user) 

        $userId = (int)($listing['user_id'] ?? 0);
        if ($userId > 0 && $oldStatus !== $status && in_array($status, ['genehmigt', 'abgelehnt'], true)) {
            $carLabel = $listing['make'] . ' ' . $listing['model'];
            if ($status === 'genehmigt') {
                Message::create($userId, 'Inserat genehmigt', 'Ihr Inserat "' . $carLabel . '" wurde genehmigt und ist ab sofort online.');
            } else {
                Message::create($userId, 'Inserat abgelehnt', 'Ihr Inserat "' . $carLabel . '" wurde abgelehnt und wird nicht veröffentlicht.');
            }
        }

        $this->json(['success' => true]);
    }

    // Gibt fertiges HTML zurück, JS lädt es per fetch() und setzt es per innerHTML in den Tab
    // Prüft $_SESSION direkt statt requireAdmin(), weil requireAdmin() JSON statt HTML zurückgibt
    public function listingsHtml(): void {
        if (empty($_SESSION['is_admin'])) { $this->emptyMsg('Kein Zugriff.'); return; }

        $inserate     = Listing::getAllRaw();
        $statusLabels = ['eingereicht' => 'Eingereicht', 'genehmigt' => 'Genehmigt', 'abgelehnt' => 'Abgelehnt'];

        if (empty($inserate)) { $this->emptyMsg('Keine eingereichten Inserate.'); return; }

        foreach ($inserate as $ins) {
            // CSS-Klasse je nach Status für die farbige Status-Badge
            $statusKlasse = $ins['status'] === 'genehmigt' ? 'status-fertig'
                          : ($ins['status'] === 'abgelehnt' ? 'status-abgelehnt' : 'status-in_bearbeitung');
            $label  = $statusLabels[$ins['status']] ?? $ins['status'];
            $datum  = date('d.m.Y', strtotime($ins['created_at']));
            // Alle Werte escapen bevor sie in das HTML-Fragment eingebettet werden
            $id     = htmlspecialchars($ins['listing_key']);
            $make   = htmlspecialchars($ins['make']);
            $model  = htmlspecialchars($ins['model']);
            $year   = htmlspecialchars($ins['year']);
            $name   = htmlspecialchars($ins['contact_name']);
            $user   = htmlspecialchars($ins['username']);
            $price  = number_format($ins['price'], 0, ',', '.');
            $km     = htmlspecialchars($ins['km']);
            $fuel   = htmlspecialchars($ins['fuel']);
            $type   = htmlspecialchars($ins['type']);
            $cond   = htmlspecialchars($ins['cond']);
            $desc   = $ins['description']
                ? '<div style="font-size:13px;color:#bdbdbd;margin-bottom:8px;">' . htmlspecialchars($ins['description']) . '</div>'
                : '';
            // Genehmigen/Ablehnen-Buttons nur bei noch nicht bearbeiteten Inseraten
            $actions = '';
            if ($ins['status'] === 'eingereicht') {
                $actions = <<<HTML
                    <div class="admin-order-actions">
                        <button onclick="adminSetInseratStatus('$id','genehmigt')">Genehmigen</button>
                        <button class="btn-reject" onclick="adminSetInseratStatus('$id','abgelehnt')">Ablehnen</button>
                    </div>
                    HTML;
            }

            echo <<<HTML
            <div class="admin-order-card">
                <div class="admin-order-header">
                    <div class="admin-order-car">$make $model ($year)</div>
                    <span class="buchung-status $statusKlasse">$label</span>
                </div>
                <div class="admin-order-meta">
                    <span>Von: <strong>$name</strong></span>
                    <span>Nutzer: <strong>$user</strong></span>
                    <span>Preis: <strong>$price €</strong></span>
                    <span>Datum: $datum</span>
                </div>
                <div class="admin-order-meta" style="margin-top:-8px;">
                    <span>$km km</span><span>$fuel</span><span>$type</span><span>$cond</span>
                </div>
                $desc
                $actions
            </div>
            HTML;
        }
    }

    // ---- Orders (Bookings) ----

    public function getOrders(): void {
        $this->requireAdmin();
        $db     = Database::getInstance();
        // Spalten werden umbenannt (AS) weil JS camelCase-Schlüssel erwartet
        $result = mysqli_query($db,
            'SELECT booking_key AS id, username AS userId, car_id AS carId, car_name AS carName,
                    car_price AS carPrice, status, reason, created_at AS createdAt, updated_at AS updatedAt
             FROM bookings ORDER BY created_at DESC'
        );
        $this->json(['success' => true, 'bookings' => mysqli_fetch_all($result, MYSQLI_ASSOC)]); //alle Zeilen als assoziatives Array zurückgeben
    }

    public function updateOrder(): void {
        $this->requireAdmin();
        // JSON-Body lesen, Formulardaten kommen nicht per $_POST sondern als Raw-JSON
        $input      = json_decode(file_get_contents('php://input'), true) ?? [];
        $bookingKey = $input['id']     ?? '';
        $status     = $input['status'] ?? '';
        $reason     = $input['reason'] ?? '';

        $db = Database::getInstance();

        mysqli_query($db, "UPDATE bookings SET status='$status', reason='$reason', updated_at=NOW() WHERE booking_key='$bookingKey'");

        // user_id steht nicht im Input-Objekt, daher extra Abfrage um die Inbox-Nachricht zuzuordnen
        $bkgRes  = mysqli_query($db, "SELECT user_id, car_name FROM bookings WHERE booking_key='$bookingKey'");
        $booking = mysqli_fetch_assoc($bkgRes);
        if ($booking && (int)$booking['user_id'] > 0) {
            $userId  = (int)$booking['user_id'];
            $carName = $booking['car_name'] ?? 'Ihr Fahrzeug';
            // Status --> Nachrichtentext für die Inbox des Nutzers
            $titles  = ['bestellt' => 'Buchung eingegangen', 'in_bearbeitung' => 'Buchung in Bearbeitung', 'versandt' => 'Fahrzeug bereit', 'fertig' => 'Buchung abgeschlossen', 'storniert' => 'Buchung storniert', 'abgelehnt' => 'Buchung abgelehnt'];
            $bodies  = ['bestellt' => "Ihre Buchung für \"$carName\" wurde erfolgreich aufgenommen.", 'in_bearbeitung' => "Ihre Buchung für \"$carName\" wird aktuell bearbeitet.", 'versandt' => "Ihr Fahrzeug \"$carName\" steht zur Abholung bereit.", 'fertig' => "Ihre Buchung für \"$carName\" wurde erfolgreich abgeschlossen. Vielen Dank!", 'storniert' => "Ihre Buchung für \"$carName\" wurde storniert.", 'abgelehnt' => "Ihre Buchung für \"$carName\" wurde leider abgelehnt." . ($reason !== '' ? " Grund: $reason" : '')];
            if (isset($titles[$status])) Message::create($userId, $titles[$status], $bodies[$status]);
        }

        $this->json(['success' => true]);
    }

    // Gibt fertiges HTML zurück, JS lädt es per fetch() und setzt es per innerHTML in den Tab
    // Prüft $_SESSION direkt statt requireAdmin(), weil requireAdmin() JSON statt HTML zurückgibt
    public function ordersHtml(): void {
        if (empty($_SESSION['is_admin'])) { $this->emptyMsg('Kein Zugriff.'); return; }

        // ?bereich=new|processing|rejected|completed -> SQL-WHERE für die Filterung
        $bereich   = $_GET['bereich'] ?? 'new'; //reiter im admin dashboard, default "new" (bestellt) wenn kein Bereich angegeben
        $filterMap = ['new' => "status='bestellt'", 'processing' => "status IN('in_bearbeitung','versandt')", 'rejected' => "status IN('abgelehnt','storniert')", 'completed' => "status='fertig'"];

        if (!isset($filterMap[$bereich])) { $this->emptyMsg('Unbekannter Bereich.'); return; }

        $db        = Database::getInstance();
        $result    = mysqli_query($db, "SELECT booking_key AS id, username AS userId, car_name AS carName, car_price AS carPrice, status, reason, created_at AS createdAt FROM bookings WHERE {$filterMap[$bereich]} ORDER BY created_at DESC");
        $buchungen = $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];

        $statusLabels = ['bestellt' => 'Bestellt', 'in_bearbeitung' => 'In Bearbeitung', 'versandt' => 'Versandt, aber nicht erhalten', 'fertig' => 'Fertig', 'storniert' => 'Storniert', 'abgelehnt' => 'Abgelehnt'];

        if (empty($buchungen)) { $this->emptyMsg('Keine Aufträge.'); return; }

        foreach ($buchungen as $b) {
            $datum  = date('d.m.Y', strtotime($b['createdAt']));
            $label  = $statusLabels[$b['status']] ?? $b['status'];
            $id     = htmlspecialchars($b['id']);
            $status = htmlspecialchars($b['status']);
            $car    = htmlspecialchars($b['carName']);
            $user   = htmlspecialchars($b['userId']);
            $price  = number_format($b['carPrice'], 0, ',', '.');
            $reason = $b['reason']
                ? '<div class="buchung-reason">Grund: ' . htmlspecialchars($b['reason']) . '</div>'
                : '';
            // Aktionsbuttons je nach aktuellem Status (Workflow: bestellt -> in_bearbeitung -> versandt -> fertig)
            $actions = '';
            if ($b['status'] === 'bestellt') {
                $actions = <<<HTML
                    <div class="admin-order-actions">
                        <button onclick="adminSetStatus('$id','in_bearbeitung')">In Bearbeitung</button>
                        <button class="btn-reject" onclick="adminRejectOrder('$id')">Ablehnen</button>
                    </div>
                    HTML;
            } elseif ($b['status'] === 'in_bearbeitung') {
                $actions = <<<HTML
                    <div class="admin-order-actions">
                        <button onclick="adminSetStatus('$id','versandt')">Als versandt markieren</button>
                        <button onclick="adminSetStatus('$id','fertig')">Fertigstellen</button>
                        <button class="btn-reject" onclick="adminRejectOrder('$id')">Ablehnen</button>
                    </div>
                    HTML;
            } elseif ($b['status'] === 'versandt') {
                $actions = <<<HTML
                    <div class="admin-order-actions">
                        <button onclick="adminSetStatus('$id','fertig')">Als erhalten markieren</button>
                    </div>
                    HTML;
            }

            echo <<<HTML
            <div class="admin-order-card">
                <div class="admin-order-header">
                    <div class="admin-order-car">$car</div>
                    <span class="buchung-status status-$status">$label</span>
                </div>
                <div class="admin-order-meta">
                    <span>Nutzer: <strong>$user</strong></span>
                    <span>Preis: <strong>$price €</strong></span>
                    <span>Datum: $datum</span>
                </div>
                $reason
                $actions
            </div>
            HTML;
        }
    }

    // ---- Users ----

    public function getUsers(): void {
        $this->requireAdmin();
        $this->json(['success' => true, 'users' => User::getAll()]);
    }

    public function updateUser(): void {
        $this->requireAdmin();
        // JSON-Body lesen, Formulardaten kommen nicht per $_POST sondern als Raw-JSON
        $input    = json_decode(file_get_contents('php://input'), true) ?? [];
        $username = $input['username'] ?? '';

        // toggleLock() erwartet eine numerische ID, nicht den Usernamen, erst nachschlagen
        $db  = Database::getInstance();
        $res = mysqli_query($db, "SELECT id FROM users WHERE username = '$username'");
        $row   = mysqli_fetch_assoc($res);
        if (!$row) { $this->json(['success' => false, 'message' => 'Nutzer nicht gefunden']); return; }

        User::toggleLock((int)$row['id']);
        $this->json(['success' => true]);
    }

    // Gibt fertiges HTML zurück, JS lädt es per fetch() und setzt es per innerHTML in den Tab
    // Prüft $_SESSION direkt statt requireAdmin(), weil requireAdmin() JSON statt HTML zurückgibt
    public function usersHtml(): void {
        if (empty($_SESSION['is_admin'])) { $this->emptyMsg('Kein Zugriff.'); return; }

        $db     = Database::getInstance();
        $result = mysqli_query($db, 'SELECT username, is_locked AS locked FROM users ORDER BY created_at ASC');
        $nutzer = $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];

        if (empty($nutzer)) { $this->emptyMsg('Keine registrierten Nutzer.'); return; }

        foreach ($nutzer as $u) {
            $name     = htmlspecialchars($u['username']);
            $gesperrt = (bool)$u['locked'];
            // CSS-Klassen und Button-Text je nach Sperrstatus invertieren
            $statusCls = $gesperrt ? 'user-locked' : 'user-active';
            $statusTxt = $gesperrt ? 'Gesperrt' : 'Aktiv';
            $btnCls    = $gesperrt ? 'btn-unlock' : 'btn-lock';
            $btnTxt    = $gesperrt ? 'Entsperren' : 'Sperren';
            echo <<<HTML
            <div class="admin-user-row">
                <span class="admin-user-name">$name</span>
                <span class="admin-user-status $statusCls">$statusTxt</span>
                <button class="$btnCls" onclick="adminToggleLock('$name')">$btnTxt</button>
            </div>
            HTML;
        }
    }

    // ---- Hilfsmethoden ----

    // Gibt eine einheitliche Leer-/Fehlermeldung aus, die per JS per innerHTML eingefügt wird
    private function emptyMsg(string $text): void {
        echo '<p class="admin-empty">' . htmlspecialchars($text) . '</p>';
    }

    // ---- Cars ----

    public function deleteCar(): void {
        $this->requireAdmin();
        // JSON-Body lesen, Formulardaten kommen nicht per $_POST sondern als Raw-JSON
        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        // Als int casten, verhindert dass ein String direkt in SQL landet
        $id    = (int)($input['id'] ?? 0);
        Car::delete($id);
        $this->json(['success' => true]);
    }

    // Gibt fertiges HTML zurück, JS lädt es per fetch() und setzt es per innerHTML in den Tab
    // Prüft $_SESSION direkt statt requireAdmin(), weil requireAdmin() JSON statt HTML zurückgibt
    public function carsHtml(): void {
        if (empty($_SESSION['is_admin'])) { $this->emptyMsg('Kein Zugriff.'); return; }

        $cars = Car::getAll_admin();
        if (empty($cars)) { $this->emptyMsg('Keine Fahrzeuge vorhanden.'); return; }

        foreach ($cars as $car) {
            $marke  = htmlspecialchars($car['marke']);
            $modell = htmlspecialchars($car['modell']);
            $bj     = htmlspecialchars($car['baujahr']);
            // Als int casten, $iid wird direkt in onclick="..." eingebettet, kein htmlspecialchars nötig
            $iid    = (int)$car['iid'];
            $price  = number_format($car['preis'], 0, ',', '.');
            $km     = number_format($car['kilometerstand'], 0, ',', '.');
            $kraft  = htmlspecialchars($car['kraftstoff']);
            $unkat  = htmlspecialchars(ucfirst($car['unterkategorie']));
            // leistung_ps kann NULL sein, dann kein PS-Element ausgeben
            $ps     = $car['leistung_ps'] ? '<span>' . (int)$car['leistung_ps'] . ' PS</span>' : '';
            echo <<<HTML
            <div class="admin-order-card">
                <div class="admin-order-header">
                    <div class="admin-order-car">$marke $modell ($bj)</div>
                    <span style="font-size:12px;color:#888;">ID: $iid</span>
                </div>
                <div class="admin-order-meta">
                    <span>Preis: <strong>$price €</strong></span>
                    <span>$km km</span><span>$kraft</span><span>$unkat</span>$ps
                </div>
                <div class="admin-order-actions">
                    <button class="btn-reject" onclick="adminDeleteCar($iid, this)">Löschen</button>
                </div>
            </div>
            HTML;
        }
    }
}
